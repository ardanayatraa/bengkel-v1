<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanJasaController extends Controller
{
    public function index(Request $request)
{
    $start = $request->input('start_date');
    $end = $request->input('end_date');
    $search = $request->input('search');

    $query = Transaksi::with(['konsumen', 'jasa'])
        ->whereNotNull('id_jasa');

    if ($start) {
        $query->whereDate('tanggal_transaksi', '>=', $start);
    }

    if ($end) {
        $query->whereDate('tanggal_transaksi', '<=', $end);
    }

    if ($search) {
        $query->whereHas('konsumen', function ($q) use ($search) {
            $q->whereNotNull('nama_konsumen')
              ->where('nama_konsumen', 'like', '%' . $search . '%');
        });
    }

    $transaksis = $query->orderByDesc('tanggal_transaksi')->paginate(10);

    return view('laporan.jasa.index', compact('transaksis', 'start', 'end', 'search'));
}


    public function exportPdf(Request $request)
    {
        $start = $request->input('start_date', Carbon::now()->format('Y-m-d'));
        $end = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $search = $request->input('search');

        $query = Transaksi::with(['konsumen', 'jasa'])
            ->whereNotNull('id_jasa')
            ->whereDate('tanggal_transaksi', '>=', $start)
            ->whereDate('tanggal_transaksi', '<=', $end);

        if ($search) {
            $query->whereHas('konsumen', function ($q) use ($search) {
                $q->where('nama_konsumen', 'like', '%' . $search . '%');
            });
        }

        $transaksis = $query->orderByDesc('tanggal_transaksi')->get();
        $pdf = Pdf::loadView('laporan.jasa.pdf', compact('transaksis', 'start', 'end'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-jasa.pdf');
    }
}
