<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanBarangController extends Controller
{
    public function index(Request $request)
    {
        $start     = $request->input('start_date');
        $end       = $request->input('end_date');
        $search    = $request->input('search');
        $category  = $request->input('category');



        // Base query: hanya Transaksi yang punya id_barang JSON non-empty
        $base = Transaksi::with(['konsumen','kasir'])
            ->whereJsonLength('id_barang','>',0);

        // Hitung total tanpa filter
        $totalAll = $base->get()->sum->calculated_total;

        // Clone untuk filter
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


        // Hitung total setelah filter
        $totalFiltered = $filtered->get()->sum->calculated_total;

        // Paginate & sertakan query string
        $transaksis = $filtered->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search','category'));

        return view('laporan.jual-barang.index', compact(
            'transaksis','start','end','search','category',
            'totalAll','totalFiltered'
        ));
    }

    public function exportPdf(Request $request)
    {
        $start     = $request->input('start_date');
        $end       = $request->input('end_date');
        $search    = $request->input('search');
        $category  = $request->input('category');

        $base = Transaksi::with(['konsumen','kasir'])
            ->whereJsonLength('id_barang','>',0);

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


        $transaksis    = $base->orderByDesc('tanggal_transaksi')->get();
        $totalFiltered = $transaksis->sum->calculated_total;

        $pdf = Pdf::loadView('laporan.jual-barang.pdf', compact(
            'transaksis','start','end','search','category','totalFiltered'
        ))
        ->setPaper('a4','landscape');

        return $pdf->stream("laporan-penjualan-barang_{$start}_to_{$end}.pdf");
    }
}
