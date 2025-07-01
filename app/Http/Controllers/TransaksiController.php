<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Barang;
use App\Models\Jasa;
use App\Models\Teknisi;
use App\Models\Point;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['konsumen','teknisi','points'])->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        $jasas     = Jasa::all();
        $teknisis  = Teknisi::all();

        return view('transaksi.create', compact('konsumens','barangs','jasas','teknisis'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_konsumen'         => 'required|exists:konsumens,id_konsumen',
            'id_teknisi'          => 'nullable|exists:teknisis,id_teknisi',
            'id_barang'           => 'nullable|array',
            'id_barang.*'         => 'exists:barangs,id_barang',
            'qty_barang'          => 'nullable|array',
            'qty_barang.*'        => 'integer|min:1',
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
        ]);

        // validasi estimasi jika ada jasa
        if (! empty($v['id_jasa']) && empty(trim($v['estimasi_pengerjaan']))) {
            return back()->withInput()
                         ->withErrors(['estimasi_pengerjaan'=>'Estimasi wajib diisi jika ada jasa.']);
        }

        $k = Konsumen::findOrFail($v['id_konsumen']);

        // bangun JSON barang => qty
        $barangJson = [];
        foreach ($v['id_barang'] ?? [] as $id) {
            $barangJson[$id] = $v['qty_barang'][$id] ?? 1;
        }

        // hitung subtotal barang
        $totalBarang = collect($barangJson)
            ->sum(function($qty, $id) {
                return Barang::find($id)->harga_jual * $qty;
            });

        // hitung subtotal jasa
        $totalJasa = collect($v['id_jasa'] ?? [])
            ->sum(fn($i) => Jasa::find($i)->harga_jasa);

        $subtotal = $totalBarang + $totalJasa;

        // diskon 10k jika member & poin >= 10
        $diskon = 0;
        if (strtolower($k->keterangan) === 'member' && $k->jumlah_point >= 10) {
            $diskon = 10000;
            $k->decrement('jumlah_point', 10);
            Point::create([
                'id_konsumen'  => $k->id_konsumen,
                'id_transaksi' => null,
                'tanggal'      => now()->toDateString(),
                'jumlah_point' => -10,
            ]);
        }

        $totalAkhir = $subtotal - $diskon;

        $t = Transaksi::create([
            'id_konsumen'         => $k->id_konsumen,
            'id_teknisi'          => $v['id_teknisi'] ?? null,
            'id_barang'           => $barangJson,
            'id_jasa'             => $v['id_jasa'] ?? [],
            'tanggal_transaksi'   => $v['tanggal_transaksi'],
            'metode_pembayaran'   => $v['metode_pembayaran'],
            'status_service'      => $v['status_service'],
            'estimasi_pengerjaan' => $v['estimasi_pengerjaan'] ?? null,
            'total_harga'         => $totalAkhir,
            'id_user'             => Auth::id(),
        ]);

        // catat poin positif jika ada jasa
        if (strtolower($k->keterangan) === 'member' && ! empty($v['id_jasa'])) {
            Point::create([
                'id_konsumen'  => $k->id_konsumen,
                'id_transaksi' => $t->id_transaksi,
                'tanggal'      => now()->toDateString(),
                'jumlah_point' => 1,
            ]);
        }

        return redirect()->route('transaksi.index')
                         ->with('success','Transaksi berhasil disimpan.');
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        $jasas     = Jasa::all();
        $teknisis  = Teknisi::all();

        return view('transaksi.edit', compact('transaksi','konsumens','barangs','jasas','teknisis'));
    }

    public function update(Request $request, $id)
    {
        $v = $request->validate([
            // sama seperti store, tanpa pengurangan poin
            'id_konsumen'         => 'required|exists:konsumens,id_konsumen',
            'id_teknisi'          => 'nullable|exists:teknisis,id_teknisi',
            'id_barang'           => 'nullable|array',
            'id_barang.*'         => 'exists:barangs,id_barang',
            'qty_barang'          => 'nullable|array',
            'qty_barang.*'        => 'integer|min:1',
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
        ]);

        if (! empty($v['id_jasa']) && empty(trim($v['estimasi_pengerjaan']))) {
            return back()->withInput()
                         ->withErrors(['estimasi_pengerjaan'=>'Estimasi wajib diisi jika ada jasa.']);
        }

        // rebuild & hitung total sama seperti di store (tanpa decrement poin)
        $barangJson = [];
        foreach ($v['id_barang'] ?? [] as $id) {
            $barangJson[$id] = $v['qty_barang'][$id] ?? 1;
        }
        $totalBarang = collect($barangJson)
            ->sum(fn($qty, $id) => Barang::find($id)->harga_jual * $qty);
        $totalJasa = collect($v['id_jasa'] ?? [])
            ->sum(fn($i) => Jasa::find($i)->harga_jasa);
        $subtotal = $totalBarang + $totalJasa;
        $diskon   = 0;
        $k = Konsumen::findOrFail($v['id_konsumen']);
        if (strtolower($k->keterangan) === 'member' && $k->jumlah_point >= 10) {
            $diskon = 10000;
        }
        $totalAkhir = $subtotal - $diskon;

        Transaksi::findOrFail($id)->update([
            'id_konsumen'         => $v['id_konsumen'],
            'id_teknisi'          => $v['id_teknisi'] ?? null,
            'id_barang'           => $barangJson,
            'id_jasa'             => $v['id_jasa'] ?? [],
            'tanggal_transaksi'   => $v['tanggal_transaksi'],
            'metode_pembayaran'   => $v['metode_pembayaran'],
            'status_service'      => $v['status_service'],
            'estimasi_pengerjaan' => $v['estimasi_pengerjaan'] ?? null,
            'total_harga'         => $totalAkhir,
        ]);

        return redirect()->route('transaksi.index')
                         ->with('success','Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Transaksi::findOrFail($id)->delete();
        return redirect()->route('transaksi.index')
                         ->with('success','Transaksi berhasil dihapus.');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi','points'])->findOrFail($id);
        $barangs   = $transaksi->barangWithQty();
        $jasas     = $transaksi->jasaModels();
        return view('transaksi.show', compact('transaksi','barangs','jasas'));
    }

    public function print($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi','points'])->findOrFail($id);
        $barangs   = $transaksi->barangWithQty();
        $jasas     = $transaksi->jasaModels();

        // subtotal sebelum diskon
        $subtotalBarang = $barangs->sum('subtotal');
        $subtotalJasa   = collect($jasas)->sum(fn($j) => $j->harga_jasa);
        $subtotal       = $subtotalBarang + $subtotalJasa;

        // diskon = selisih subtotal & total_harga
        $diskon  = max(0, $subtotal - $transaksi->total_harga);

        // poin tersisa
        $sisaPoint = $transaksi->konsumen->jumlah_point;

        $pdf = Pdf::loadView('transaksi.print', compact(
            'transaksi','barangs','jasas','subtotal','diskon','sisaPoint'
        ))
        ->setPaper('a4','portrait');

        return $pdf->download("transaksi_{$transaksi->id_transaksi}.pdf");
    }
}
