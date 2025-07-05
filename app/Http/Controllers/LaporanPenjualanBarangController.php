<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanBarangController extends Controller
{
    public function index(Request $request)
    {
        $start     = $request->input('start_date');
        $end       = $request->input('end_date');
        $search    = $request->input('search');
        $category  = $request->input('category');

        // Untuk dropdown kategori
        $categories = Kategori::orderBy('nama_kategori')->get();

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
        if ($category) {
            // Ambil semua ID barang di kategori ini
            $barangIds = Barang::where('id_kategori',$category)
                ->pluck('id_barang')
                ->toArray();

            // Filter transaksi yang JSON id_barang memiliki salah satu key tersebut
            $filtered->where(function($q) use($barangIds){
                foreach ($barangIds as $bid) {
                    $q->orWhereRaw("JSON_EXTRACT(id_barang, '$.\"{$bid}\"') IS NOT NULL");
                }
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
            'categories','totalAll','totalFiltered'
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
        if ($category) {
            $barangIds = Barang::where('id_kategori',$category)
                ->pluck('id_barang')
                ->toArray();
            $base->where(function($q) use($barangIds){
                foreach ($barangIds as $bid) {
                    $q->orWhereRaw("JSON_EXTRACT(id_barang, '$.\"{$bid}\"') IS NOT NULL");
                }
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
