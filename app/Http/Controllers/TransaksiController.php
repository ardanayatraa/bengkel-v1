<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Barang;
use App\Models\Jasa;
use App\Models\Teknisi;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
        ]);

        if (!empty($v['id_jasa']) && empty(trim($v['estimasi_pengerjaan']))) {
            return back()->withInput()
                         ->withErrors(['estimasi_pengerjaan'=>'Estimasi wajib diisi jika ada jasa.']);
        }

        $k = Konsumen::findOrFail($v['id_konsumen']);

        // hitung total harga
        $total = collect($v['id_barang'] ?? [])
                    ->map(fn($i)=>Barang::find($i)->harga_jual)->sum()
               +  collect($v['id_jasa'] ?? [])
                    ->map(fn($i)=>Jasa::find($i)->harga_jasa)->sum();

        // diskon member
        if (strtolower($k->keterangan)==='member' && $k->jumlah_point >= 10) {
            $total -= 10000;
            $k->decrement('jumlah_point', 10);
        }

        $transaksi = Transaksi::create([
            'id_konsumen'         => $v['id_konsumen'],
            'id_teknisi'          => $v['id_teknisi']          ?? null,
            'id_barang'           => $v['id_barang']           ?? [],
            'id_jasa'             => $v['id_jasa']             ?? [],
            'tanggal_transaksi'   => $v['tanggal_transaksi'],
            'metode_pembayaran'   => $v['metode_pembayaran'],
            'status_service'      => $v['status_service'],
            'estimasi_pengerjaan' => $v['estimasi_pengerjaan'] ?? null,
            'total_harga'         => $total,
            'id_user'             => Auth::id(),
        ]);

        if (strtolower($k->keterangan)==='member' && !empty($v['id_jasa'])) {
            Point::create([
                'id_konsumen'   => $k->id_konsumen,
                'id_transaksi'  => $transaksi->id_transaksi,
                'tanggal'       => now()->toDateString(),
                'jumlah_point'  => 1,
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
            'id_konsumen'         => 'required|exists:konsumens,id_konsumen',
            'id_teknisi'          => 'nullable|exists:teknisis,id_teknisi',
            'id_barang'           => 'nullable|array',
            'id_barang.*'         => 'exists:barangs,id_barang',
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
        ]);

        if (!empty($v['id_jasa']) && empty(trim($v['estimasi_pengerjaan']))) {
            return back()->withInput()
                         ->withErrors(['estimasi_pengerjaan'=>'Estimasi wajib diisi jika ada jasa.']);
        }

        $k = Konsumen::findOrFail($v['id_konsumen']);

        $total = collect($v['id_barang'] ?? [])
                    ->map(fn($i)=>Barang::find($i)->harga_jual)->sum()
               +  collect($v['id_jasa'] ?? [])
                    ->map(fn($i)=>Jasa::find($i)->harga_jasa)->sum();

        if (strtolower($k->keterangan)==='member' && $k->jumlah_point >= 10) {
            $total -= 10000;
        }

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'id_konsumen'         => $v['id_konsumen'],
            'id_teknisi'          => $v['id_teknisi']          ?? null,
            'id_barang'           => $v['id_barang']           ?? [],
            'id_jasa'             => $v['id_jasa']             ?? [],
            'tanggal_transaksi'   => $v['tanggal_transaksi'],
            'metode_pembayaran'   => $v['metode_pembayaran'],
            'status_service'      => $v['status_service'],
            'estimasi_pengerjaan' => $v['estimasi_pengerjaan'] ?? null,
            'total_harga'         => $total,
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
    $transaksi = Transaksi::with(['konsumen', 'teknisi', 'points'])->findOrFail($id);
    
    // Ambil model Barang & Jasa dari array ID yang disimpan
    $barangs = $transaksi->barangModels();
    $jasas   = $transaksi->jasaModels();

    return view('transaksi.show', compact('transaksi', 'barangs', 'jasas'));
}


    public function print($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi','points'])->findOrFail($id);

        // Ambil semua barang dan jasa
        $barangs = $transaksi->barangModels();
        $jasas   = $transaksi->jasaModels();

        $pdf = Pdf::loadView('transaksi.print', compact('transaksi', 'barangs', 'jasas'))
                ->setPaper('a4','portrait');
        
        return $pdf->download("transaksi_{$transaksi->id_transaksi}.pdf");
    }

}
