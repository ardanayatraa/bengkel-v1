<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBarangController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $search = $request->input('search');

        $query = Transaksi::with(['konsumen', 'barang'])
            ->whereNotNull('id_barang');

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

        return view('laporan.barang.index', compact('transaksis', 'start', 'end', 'search'));
    }

    public function exportPdf(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $search = $request->input('search');

        $query = Transaksi::with(['konsumen', 'barang'])
            ->whereNotNull('id_barang');

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

        $pdf = Pdf::loadView('laporan.barang.pdf', compact('transaksis', 'start', 'end'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-barang.pdf');
    }
}
