<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Barang;
use App\Models\Jasa;
use App\Models\Point;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['konsumen','teknisi','points'])
                          ->orderByDesc('tanggal_transaksi')
                          ->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        $jasas     = Jasa::all();
        $teknisis  = \App\Models\Teknisi::all();
        return view('transaksi.create', compact('konsumens','barangs','jasas','teknisis'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_konsumen'         => 'required|exists:konsumens,id_konsumen',
            'id_teknisi'          => 'nullable|exists:teknisis,id_teknisi',
            'id_barang'           => 'nullable|array',
            'id_barang.*'         => 'exists:barangs,id_barang',
            'qty_barang.*'        => 'integer|min:1',
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
            'uang_diterima'       => 'required|numeric|min:0',
            'redeem_points'       => 'nullable|integer|min:0',
        ]);

        $k = Konsumen::findOrFail($v['id_konsumen']);
        $redeem = $v['redeem_points'] ?? 0;

        // validasi poin: kelipatan 10 & tidak lebih dari saldo
        if ($redeem % 10 !== 0 || $redeem > $k->jumlah_point) {
            return back()->withInput()
                         ->withErrors(['redeem_points'=>'Poin tukar harus kelipatan 10 dan tidak melebihi saldo.']);
        }

        // build array barang→qty
        $barangJson = [];
        foreach ($v['id_barang'] ?? [] as $id) {
            $barangJson[$id] = $v['qty_barang'][$id] ?? 1;
        }

        // hitung subtotal barang
        $totalBarang = collect($barangJson)
            ->map(fn($qty,$id)=> Barang::find($id)->harga_jual * $qty)
            ->sum();

        // hitung subtotal jasa
        $totalJasa = collect($v['id_jasa'] ?? [])
            ->map(fn($jid)=> Jasa::find($jid)->harga_jasa)
            ->sum();

        $subtotal = $totalBarang + $totalJasa;

        // diskon dari poin yang ditukar
        $pointDiscount = ($redeem / 10) * 10000;
        $totalAkhir    = $subtotal - $pointDiscount;

        // validasi pembayaran cukup
        if ($v['uang_diterima'] < $totalAkhir) {
            return back()->withInput()
                         ->withErrors(['uang_diterima'=>"Uang diterima minimal Rp ".number_format($totalAkhir,0,',','.')."."]);
        }

        DB::transaction(function() use($v, $k, $barangJson, $totalAkhir, $redeem) {
            // simpan Transaksi
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
                'uang_diterima'       => $v['uang_diterima'],
                'id_user'             => Auth::id(),
            ]);

            // kurangi poin di Konsumen & buat log negatif
            if ($redeem > 0) {
                $k->decrement('jumlah_point', $redeem);

            }


        });

        return redirect()->route('transaksi.index')
                         ->with('success','Transaksi berhasil disimpan.');
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        $jasas     = Jasa::all();
        $teknisis  = \App\Models\Teknisi::all();
        return view('transaksi.edit', compact('transaksi','konsumens','barangs','jasas','teknisis'));
    }

    public function update(Request $request, $id)
    {
        $v = $request->validate([
            'id_konsumen'         => 'required|exists:konsumens,id_konsumen',
            'id_teknisi'          => 'nullable|exists:teknisis,id_teknisi',
            'id_barang'           => 'nullable|array',
            'id_barang.*'         => 'exists:barangs,id_barang',
            'qty_barang.*'        => 'integer|min:1',
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
            'uang_diterima'       => 'required|numeric|min:0',
            'redeem_points'       => 'nullable|integer|min:0',
        ]);

        $k = Konsumen::findOrFail($v['id_konsumen']);
        $redeem = $v['redeem_points'] ?? 0;
        if ($redeem % 10 !== 0 || $redeem > $k->jumlah_point) {
            return back()->withInput()
                         ->withErrors(['redeem_points'=>'Poin tukar harus kelipatan 10 dan tidak melebihi saldo.']);
        }

        // hitung ulang subtotal
        $barangJson = [];
        foreach ($v['id_barang'] ?? [] as $id) {
            $barangJson[$id] = $v['qty_barang'][$id] ?? 1;
        }
        $totalBarang = collect($barangJson)
            ->map(fn($qty,$id)=> Barang::find($id)->harga_jual * $qty)
            ->sum();
        $totalJasa = collect($v['id_jasa'] ?? [])
            ->map(fn($jid)=> Jasa::find($jid)->harga_jasa)
            ->sum();
        $subtotal = $totalBarang + $totalJasa;
        $pointDiscount = ($redeem / 10) * 10000;
        $totalAkhir = $subtotal - $pointDiscount;

        if ($v['uang_diterima'] < $totalAkhir) {
            return back()->withInput()
                         ->withErrors(['uang_diterima'=>"Uang diterima minimal Rp ".number_format($totalAkhir,0,',','.')."."]);
        }

        DB::transaction(function() use($v, $k, $barangJson, $totalAkhir, $redeem, $id) {
            $tr = Transaksi::findOrFail($id);
            $tr->update([
                'id_konsumen'         => $k->id_konsumen,
                'id_teknisi'          => $v['id_teknisi'] ?? null,
                'id_barang'           => $barangJson,
                'id_jasa'             => $v['id_jasa'] ?? [],
                'tanggal_transaksi'   => $v['tanggal_transaksi'],
                'metode_pembayaran'   => $v['metode_pembayaran'],
                'status_service'      => $v['status_service'],
                'estimasi_pengerjaan' => $v['estimasi_pengerjaan'] ?? null,
                'total_harga'         => $totalAkhir,
                'uang_diterima'       => $v['uang_diterima'],
            ]);

            // hapus log poin negatif lama untuk transaksi ini, lalu buat baru
            $k->points()
              ->where('id_transaksi', $id)
              ->where('jumlah_point','<',0)
              ->delete();

            if ($redeem > 0) {
                $k->decrement('jumlah_point', $redeem);
                $k->points()->create([
                    'id_transaksi' => $id,
                    'tanggal'      => Carbon::now()->toDateString(),
                    'jumlah_point' => -$redeem,
                ]);
            }

            // untuk reward jasa, Anda bisa sesuaikan: hapus dulu atau tambahkan jika belum ada
            // …
        });
        return redirect()->route('transaksi.index')
                         ->with('success','Transaksi berhasil diperbarui.');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi','points'])
                               ->findOrFail($id);

        $barangs   = $transaksi->barangWithQty();
        $jasas     = $transaksi->jasaModels();
        $subtotal  = $barangs->sum('subtotal') + collect($jasas)->sum(fn($j)=>$j->harga_jasa);
        $diskon    = $transaksi->point_discount;
        $kembalian = $transaksi->uang_diterima - $transaksi->total_harga;
        $sisaPoint = $transaksi->konsumen->jumlah_point;

        return view('transaksi.show', compact(
            'transaksi','barangs','jasas',
            'subtotal','diskon','kembalian','sisaPoint'
        ));
    }

    public function print($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi','points'])
                               ->findOrFail($id);
        $barangs   = $transaksi->barangWithQty();
        $jasas     = $transaksi->jasaModels();
        $subtotal  = $barangs->sum('subtotal') + collect($jasas)->sum(fn($j)=>$j->harga_jasa);
        $diskon    = $transaksi->point_discount;
        $kembalian = $transaksi->uang_diterima - $transaksi->total_harga;
        $sisaPoint = $transaksi->konsumen->jumlah_point;

        $wPt = 80   * 2.83465;
        $lineCount = 6 + $barangs->count() + $jasas->count() + 6;
        $heightMm  = 8 + ($lineCount * 8);
        $hPt       = $heightMm * 2.83465;

        $pdf = Pdf::loadView('transaksi.print', compact(
            'transaksi','barangs','jasas',
            'subtotal','diskon','kembalian','sisaPoint'
        ))->setPaper([0,0,$wPt,$hPt]);

        return $pdf->download("nota_{$transaksi->id_transaksi}.pdf");
    }

    public function destroy($id)
    {
        Transaksi::findOrFail($id)->delete();
        return redirect()->route('transaksi.index')
                         ->with('success','Transaksi berhasil dihapus.');
    }
}
