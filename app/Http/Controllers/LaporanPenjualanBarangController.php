<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanBarangController extends Controller
{
    public function index(Request $request)
    {
        $start  = $request->input('start_date');
        $end    = $request->input('end_date');
        $search = $request->input('search');

        // Base query: semua transaksi yang punya barang
        $base = Transaksi::with(['konsumen','kasir'])
            ->whereJsonLength('id_barang','>',0);

        // Total keseluruhan (tanpa filter)
        $totalAll = $base->get()->sum->calculated_total;

        // Terapkan filter hanya jika ada input
        $filtered = clone $base;
        if ($start) {
            $filtered->whereDate('tanggal_transaksi','>=',$start);
        }
        if ($end) {
            $filtered->whereDate('tanggal_transaksi','<=',$end);
        }
        if ($search) {
            $filtered->whereHas('konsumen', function($q) use($search){
                $q->where('nama_konsumen','like',"%{$search}%")
                  ->orWhere('no_kendaraan','like',"%{$search}%");
            });
        }

        // Total setelah filter (atau sama dengan totalAll jika tidak ada filter)
        $totalFiltered = $filtered->get()->sum->calculated_total;

        // Paginate untuk web view
        $transaksis = $filtered
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search'));

        return view('laporan.jual-barang.index', compact(
            'transaksis','start','end','search','totalAll','totalFiltered'
        ));
    }

    public function exportPdf(Request $request)
    {
        $start  = $request->input('start_date');
        $end    = $request->input('end_date');
        $search = $request->input('search');

        // Base query: semua transaksi yang punya barang
        $base = Transaksi::with(['konsumen','kasir'])
            ->whereJsonLength('id_barang','>',0);

        // Terapkan filter hanya jika ada input
        if ($start) {
            $base->whereDate('tanggal_transaksi','>=',$start);
        }
        if ($end) {
            $base->whereDate('tanggal_transaksi','<=',$end);
        }
        if ($search) {
            $base->whereHas('konsumen', function($q) use($search){
                $q->where('nama_konsumen','like',"%{$search}%")
                  ->orWhere('no_kendaraan','like',"%{$search}%");
            });
        }

        // Ambil data (semua atau terfilter)
        $transaksis    = $base->orderByDesc('tanggal_transaksi')->get();
        $totalFiltered = $transaksis->sum->calculated_total;

        // Load PDF
        $pdf = Pdf::loadView('laporan.jual-barang.pdf', compact(
            'transaksis','start','end','search','totalFiltered'
        ))
        ->setPaper('a4','landscape');

        return $pdf->stream("laporan-penjualan-barang.pdf");
    }
}
