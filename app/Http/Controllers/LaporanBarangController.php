<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\TrxBarangMasuk;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBarangController extends Controller
{
    /**
     * Tampilkan halaman laporan barang dengan ringkasan stok.
     */
    public function index(Request $request)
    {
        $start  = $request->input('start_date');
        $end    = $request->input('end_date');
        $search = $request->input('search');

        // Query transaksi yang punya barang (JSON-array id_barang)
        $transaksiQuery = Transaksi::with('konsumen')
            ->whereJsonLength('id_barang', '>', 0)
            ->when($start, fn($q) => $q->whereDate('tanggal_transaksi','>=',$start))
            ->when($end,   fn($q) => $q->whereDate('tanggal_transaksi','<=',$end))
            ->when($search, fn($q) =>
                $q->whereHas('konsumen', fn($q2) =>
                    $q2->where('nama_konsumen','like',"%{$search}%")
                )
            );

        $transaksis = $transaksiQuery
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search'));

        // Ringkasan stok per barang
        $stockSummary = Barang::all()->map(function($b) use($start,$end) {
            // Masuk dari trx_barang_masuks
            $masuk = TrxBarangMasuk::where('id_barang',$b->id_barang)
                ->when($start, fn($q)=> $q->whereDate('tanggal_masuk','>=',$start))
                ->when($end,   fn($q)=> $q->whereDate('tanggal_masuk','<=',$end))
                ->sum('jumlah');

            // Keluar: hitung berapa transaksi mengandung barang ini
            $keluar = Transaksi::whereJsonContains('id_barang',$b->id_barang)
                ->when($start, fn($q)=> $q->whereDate('tanggal_transaksi','>=',$start))
                ->when($end,   fn($q)=> $q->whereDate('tanggal_transaksi','<=',$end))
                ->count();

            return (object)[
                'barang'     => $b,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => $b->stok,
            ];
        });

        return view('laporan.barang.index', compact(
            'transaksis','start','end','search','stockSummary'
        ));
    }

    /**
     * Export PDF laporan barang.
     */
    public function exportPdf(Request $request)
    {
        $start  = $request->input('start_date', Carbon::today()->toDateString());
        $end    = $request->input('end_date',   Carbon::today()->toDateString());
        $search = $request->input('search');

        $transaksis = Transaksi::with('konsumen')
            ->whereJsonLength('id_barang','>',0)
            ->when($start, fn($q)=> $q->whereDate('tanggal_transaksi','>=',$start))
            ->when($end,   fn($q)=> $q->whereDate('tanggal_transaksi','<=',$end))
            ->when($search, fn($q)=>
                $q->whereHas('konsumen', fn($q2)=>
                    $q2->where('nama_konsumen','like',"%{$search}%")
                )
            )
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $stockSummary = Barang::all()->map(function($b) use($start,$end) {
            $masuk = TrxBarangMasuk::where('id_barang',$b->id_barang)
                ->whereDate('tanggal_masuk','>=',$start)
                ->whereDate('tanggal_masuk','<=',$end)
                ->sum('jumlah');
            $keluar = Transaksi::whereJsonContains('id_barang',$b->id_barang)
                ->whereDate('tanggal_transaksi','>=',$start)
                ->whereDate('tanggal_transaksi','<=',$end)
                ->count();
            return (object)[
                'barang'     => $b,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => $b->stok,
            ];
        });

        $pdf = Pdf::loadView('laporan.barang.pdf', compact(
            'transaksis','start','end','stockSummary'
        ))->setPaper('a4','landscape');

        return $pdf->download("laporan-transaksi-barang_{$start}_to_{$end}.pdf");
    }
}
