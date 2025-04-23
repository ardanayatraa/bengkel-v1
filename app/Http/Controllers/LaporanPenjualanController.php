<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $search = $request->input('search');

        $query = Transaksi::with(['konsumen', 'barang', 'jasa']);

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

        return view('laporan.penjualan.index', compact('transaksis', 'start', 'end', 'search'));
    }

    public function exportPdf(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $search = $request->input('search');

        $query = Transaksi::with(['konsumen', 'barang', 'jasa']);

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

        $transaksis = $query->orderByDesc('tanggal_transaksi')->get();

        $pdf = Pdf::loadView('laporan.penjualan.pdf', compact('transaksis', 'start', 'end'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-penjualan.pdf');
    }
}
