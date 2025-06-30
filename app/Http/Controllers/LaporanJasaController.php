<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanJasaController extends Controller
{
    /**
     * Tampilkan halaman laporan jasa dengan filter tanggal dan pencarian konsumen.
     */
    public function index(Request $request)
    {
        $start  = $request->input('start_date');
        $end    = $request->input('end_date');
        $search = $request->input('search');

        $query = Transaksi::with('konsumen')
            // pastikan transaksi punya satu atau lebih jasa
            ->whereJsonLength('id_jasa', '>', 0);

        if ($start) {
            $query->whereDate('tanggal_transaksi', '>=', $start);
        }

        if ($end) {
            $query->whereDate('tanggal_transaksi', '<=', $end);
        }

        if ($search) {
            $query->whereHas('konsumen', function ($q) use ($search) {
                $q->where('nama_konsumen', 'like', "%{$search}%");
            });
        }

        $transaksis = $query
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search'));

        return view('laporan.jasa.index', compact('transaksis','start','end','search'));
    }

    /**
     * Export PDF laporan jasa sesuai filter.
     */
    public function exportPdf(Request $request)
    {
        $start  = $request->input('start_date', Carbon::today()->toDateString());
        $end    = $request->input('end_date',   Carbon::today()->toDateString());
        $search = $request->input('search');

        $query = Transaksi::with('konsumen')
            ->whereJsonLength('id_jasa', '>', 0)
            ->whereDate('tanggal_transaksi', '>=', $start)
            ->whereDate('tanggal_transaksi', '<=', $end);

        if ($search) {
            $query->whereHas('konsumen', function ($q) use ($search) {
                $q->where('nama_konsumen', 'like', "%{$search}%");
            });
        }

        $transaksis = $query
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $pdf = Pdf::loadView('laporan.jasa.pdf', compact('transaksis','start','end'))
                  ->setPaper('a4','landscape');

        return $pdf->download("laporan-transaksi-jasa_{$start}_to_{$end}.pdf");
    }
}
