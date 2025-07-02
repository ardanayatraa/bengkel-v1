<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\TrxBarangMasuk;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBarangController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan menggunakan timezone app
        $today = now()->toDateString();

        // Jika tidak diisi, default ke hari ini (app timezone)
        $start  = $request->input('start_date', $today);
        $end    = $request->input('end_date',   $today);
        $search = $request->input('search');

        // Transaksi barang
        $transaksis = Transaksi::with('konsumen')
            ->whereJsonLength('id_barang','>',0)
            ->when($search, fn($q)=>
                $q->whereHas('konsumen', fn($q2)=>
                    $q2->where('nama_konsumen','like',"%{$search}%")
                )
            )
            ->whereDate('tanggal_transaksi','>=',$start)
            ->whereDate('tanggal_transaksi','<=',$end)
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search'));

        // Ringkasan stok per barang
        $stockSummary = Barang::all()->map(function($b) use($start, $end) {
            // Barang masuk di periode
            $masuk = TrxBarangMasuk::where('id_barang',$b->id_barang)
                ->whereDate('tanggal_masuk','>=',$start)
                ->whereDate('tanggal_masuk','<=',$end)
                ->sum('jumlah');

            // Barang keluar (qty) di periode
            $keluar = Transaksi::whereJsonContains('id_barang',$b->id_barang)
                ->whereDate('tanggal_transaksi','>=',$start)
                ->whereDate('tanggal_transaksi','<=',$end)
                ->get()
                ->sum(fn($trx) => $trx->id_barang[$b->id_barang] ?? 0);

            $stokAkhir = $b->stok;
            $stokAwal  = $stokAkhir - $masuk + $keluar;

            return (object)[
                'barang'     => $b,
                'stok_awal'  => $stokAwal,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => $stokAkhir,
            ];
        });

        $totalStokAwal  = $stockSummary->sum('stok_awal');
        $totalMasuk     = $stockSummary->sum('masuk');
        $totalKeluar    = $stockSummary->sum('keluar');
        $totalStokAkhir = $stockSummary->sum('stok_akhir');

        return view('laporan.barang.index', compact(
            'transaksis','start','end','search',
            'stockSummary',
            'totalStokAwal','totalMasuk','totalKeluar','totalStokAkhir'
        ));
    }

    public function exportPdf(Request $request)
    {
        $today  = now()->toDateString();
        $start  = $request->input('start_date', $today);
        $end    = $request->input('end_date',   $today);
        $search = $request->input('search');

        $transaksis = Transaksi::with('konsumen')
            ->whereJsonLength('id_barang','>',0)
            ->whereDate('tanggal_transaksi','>=',$start)
            ->whereDate('tanggal_transaksi','<=',$end)
            ->when($search, fn($q)=>
                $q->whereHas('konsumen', fn($q2)=>
                    $q2->where('nama_konsumen','like',"%{$search}%")
                )
            )
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $stockSummary = Barang::all()->map(function($b) use($start, $end) {
            $masuk = TrxBarangMasuk::where('id_barang',$b->id_barang)
                ->whereDate('tanggal_masuk','>=',$start)
                ->whereDate('tanggal_masuk','<=',$end)
                ->sum('jumlah');

            $keluar = Transaksi::whereJsonContains('id_barang',$b->id_barang)
                ->whereDate('tanggal_transaksi','>=',$start)
                ->whereDate('tanggal_transaksi','<=',$end)
                ->get()
                ->sum(fn($trx) => $trx->id_barang[$b->id_barang] ?? 0);

            $stokAkhir = $b->stok;
            $stokAwal  = $stokAkhir - $masuk + $keluar;

            return (object)[
                'barang'     => $b,
                'stok_awal'  => $stokAwal,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => $stokAkhir,
            ];
        });

        $totalStokAwal  = $stockSummary->sum('stok_awal');
        $totalMasuk     = $stockSummary->sum('masuk');
        $totalKeluar    = $stockSummary->sum('keluar');
        $totalStokAkhir = $stockSummary->sum('stok_akhir');

        $pdf = Pdf::loadView('laporan.barang.pdf', compact(
            'transaksis','start','end','search',
            'stockSummary',
            'totalStokAwal','totalMasuk','totalKeluar','totalStokAkhir'
        ))
        ->setPaper('a4','landscape');

        return $pdf->stream("ringkasan-stok-barang_{$start}_to_{$end}.pdf");
    }
}
