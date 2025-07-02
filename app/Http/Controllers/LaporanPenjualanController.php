<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan (barang & jasa) dengan filter dan total.
     */
    public function index(Request $request)
    {
        $start  = $request->input('start_date');
        $end    = $request->input('end_date');
        $search = $request->input('search');

        // Basis: semua transaksi
        $baseQuery = Transaksi::with('konsumen');

        // Total semua (tanpa filter)
        $totalAll = (clone $baseQuery)->sum('total_harga');

        // Clone untuk filter
        $filtered = (clone $baseQuery);
        if ($start) {
            $filtered->whereDate('tanggal_transaksi', '>=', $start);
        }
        if ($end) {
            $filtered->whereDate('tanggal_transaksi', '<=', $end);
        }
        if ($search) {
            $filtered->whereHas('konsumen', fn($q) =>
                $q->where('nama_konsumen', 'like', "%{$search}%")
            );
        }

        // Total terfilter
        $totalFiltered = $filtered->sum('total_harga');

        // Ambil daftar
        $transaksis = $filtered
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search'));

        return view('laporan.penjualan.index', compact(
            'transaksis','start','end','search','totalAll','totalFiltered'
        ));
    }

    /**
     * Export PDF laporan penjualan sesuai filter.
     */
    public function exportPdf(Request $request)
    {
        $start  = $request->input('start_date', Carbon::today()->toDateString());
        $end    = $request->input('end_date',   Carbon::today()->toDateString());
        $search = $request->input('search');

        $query = Transaksi::with('konsumen');
        if ($start) {
            $query->whereDate('tanggal_transaksi', '>=', $start);
        }
        if ($end) {
            $query->whereDate('tanggal_transaksi', '<=', $end);
        }
        if ($search) {
            $query->whereHas('konsumen', fn($q) =>
                $q->where('nama_konsumen', 'like', "%{$search}%")
            );
        }

        $transaksis     = $query->orderByDesc('tanggal_transaksi')->get();
        $totalFiltered  = $transaksis->sum('total_harga');

        $pdf = Pdf::loadView('laporan.penjualan.pdf', compact(
            'transaksis','start','end','search','totalFiltered'
        ))
        ->setPaper('a4','landscape');

       return $pdf->stream("laporan-transaksi-jasa_{$start}_to_{$end}.pdf");
    }
}
