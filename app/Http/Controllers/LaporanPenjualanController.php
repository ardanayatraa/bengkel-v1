<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan (barang & jasa) dengan filter.
     */
    public function index(Request $request)
    {
        $start  = $request->input('start_date');
        $end    = $request->input('end_date');
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

        $transaksis = $query
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search'));

        return view('laporan.penjualan.index', compact('transaksis','start','end','search'));
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

        $transaksis = $query
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $pdf = Pdf::loadView('laporan.penjualan.pdf', compact('transaksis','start','end'))
                  ->setPaper('a4','landscape');

        return $pdf->download("laporan-penjualan_{$start}_to_{$end}.pdf");
    }
}
