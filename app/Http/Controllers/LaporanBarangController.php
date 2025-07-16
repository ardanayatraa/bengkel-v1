<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\TrxBarangMasuk;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBarangController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan menggunakan timezone app
        $today = now()->toDateString();

        // Jika tidak diisi, default ke hari ini (app timezone)
        $start  = $request->input('start_date', $today);
        $end    = $request->input('end_date',   $today);
        $search = $request->input('search');

        // Transaksi barang
        $transaksis = Transaksi::with('konsumen')
            ->whereJsonLength('id_barang','>',0)
            ->when($search, fn($q)=>
                $q->whereHas('konsumen', fn($q2)=>
                    $q2->where('nama_konsumen','like',"%{$search}%")
                )
            )
            ->whereDate('tanggal_transaksi','>=',$start)
            ->whereDate('tanggal_transaksi','<=',$end)
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search'));

        // Ringkasan stok per barang
        $stockSummary = Barang::all()->map(function($b) use($start, $end) {
            // Barang masuk di periode
            $masuk = TrxBarangMasuk::where('id_barang',$b->id_barang)
                ->whereDate('tanggal_masuk','>=',$start)
                ->whereDate('tanggal_masuk','<=',$end)
                ->sum('jumlah');

            // Barang keluar (qty) di periode - FIXED
            $keluar = $this->calculateBarangKeluar($b->id_barang, $start, $end);

            $stokAkhir = $b->stok;
            $stokAwal  = $stokAkhir - $masuk + $keluar;

            return (object)[
                'barang'     => $b,
                'stok_awal'  => $stokAwal,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => $stokAkhir,
            ];
        });

        $totalStokAwal  = $stockSummary->sum('stok_awal');
        $totalMasuk     = $stockSummary->sum('masuk');
        $totalKeluar    = $stockSummary->sum('keluar');
        $totalStokAkhir = $stockSummary->sum('stok_akhir');

        return view('laporan.barang.index', compact(
            'transaksis','start','end','search',
            'stockSummary',
            'totalStokAwal','totalMasuk','totalKeluar','totalStokAkhir'
        ));
    }

    public function exportPdf(Request $request)
    {
        $today  = now()->toDateString();
        $start  = $request->input('start_date', $today);
        $end    = $request->input('end_date',   $today);
        $search = $request->input('search');

        $transaksis = Transaksi::with('konsumen')
            ->whereJsonLength('id_barang','>',0)
            ->whereDate('tanggal_transaksi','>=',$start)
            ->whereDate('tanggal_transaksi','<=',$end)
            ->when($search, fn($q)=>
                $q->whereHas('konsumen', fn($q2)=>
                    $q2->where('nama_konsumen','like',"%{$search}%")
                )
            )
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $stockSummary = Barang::all()->map(function($b) use($start, $end) {
            $masuk = TrxBarangMasuk::where('id_barang',$b->id_barang)
                ->whereDate('tanggal_masuk','>=',$start)
                ->whereDate('tanggal_masuk','<=',$end)
                ->sum('jumlah');

            // Barang keluar (qty) di periode - FIXED
            $keluar = $this->calculateBarangKeluar($b->id_barang, $start, $end);

            $stokAkhir = $b->stok;
            $stokAwal  = $stokAkhir - $masuk + $keluar;

            return (object)[
                'barang'     => $b,
                'stok_awal'  => $stokAwal,
                'masuk'      => $masuk,
                'keluar'     => $keluar,
                'stok_akhir' => $stokAkhir,
            ];
        });

        $totalStokAwal  = $stockSummary->sum('stok_awal');
        $totalMasuk     = $stockSummary->sum('masuk');
        $totalKeluar    = $stockSummary->sum('keluar');
        $totalStokAkhir = $stockSummary->sum('stok_akhir');

        $pdf = Pdf::loadView('laporan.barang.pdf', compact(
            'transaksis','start','end','search',
            'stockSummary',
            'totalStokAwal','totalMasuk','totalKeluar','totalStokAkhir'
        ))
        ->setPaper('a4','landscape');

        return $pdf->stream("ringkasan-stok-barang_{$start}_to_{$end}.pdf");
    }

    /**
     * Calculate barang keluar with proper JSON handling
     */
    private function calculateBarangKeluar($idBarang, $start, $end)
    {
        // Get all transactions in date range that have items
        $transaksis = Transaksi::whereJsonLength('id_barang', '>', 0)
            ->whereDate('tanggal_transaksi', '>=', $start)
            ->whereDate('tanggal_transaksi', '<=', $end)
            ->get();

        $totalKeluar = 0;

        foreach ($transaksis as $trx) {
            // Since id_barang is already cast as array, we can use it directly
            $items = $trx->id_barang;

            if (is_array($items) && isset($items[$idBarang])) {
                $totalKeluar += (int) $items[$idBarang];
            }
        }

        return $totalKeluar;
    }

    /**
     * Alternative method using Laravel's JSON query methods
     */
    private function calculateBarangKeluarAlternative($idBarang, $start, $end)
    {
        // Method 1: Using whereJsonContains - for exact key match
        $keluar1 = Transaksi::whereJsonContains('id_barang->'.  $idBarang, null)
            ->whereDate('tanggal_transaksi', '>=', $start)
            ->whereDate('tanggal_transaksi', '<=', $end)
            ->get()
            ->sum(function($trx) use ($idBarang) {
                $items = $trx->id_barang;
                return isset($items[$idBarang]) ? (int) $items[$idBarang] : 0;
            });

        // Method 2: Using raw SQL with JSON_EXTRACT
        $keluar2 = Transaksi::whereRaw("JSON_EXTRACT(id_barang, '$.\"$idBarang\"') IS NOT NULL")
            ->whereDate('tanggal_transaksi', '>=', $start)
            ->whereDate('tanggal_transaksi', '<=', $end)
            ->get()
            ->sum(function($trx) use ($idBarang) {
                $items = $trx->id_barang;
                return isset($items[$idBarang]) ? (int) $items[$idBarang] : 0;
            });

        // Method 3: Using whereJsonLength for the specific key
        $keluar3 = Transaksi::where(function($q) use ($idBarang) {
                $q->whereJsonLength('id_barang', '>', 0)
                  ->whereRaw("JSON_EXTRACT(id_barang, '$.\"$idBarang\"') IS NOT NULL");
            })
            ->whereDate('tanggal_transaksi', '>=', $start)
            ->whereDate('tanggal_transaksi', '<=', $end)
            ->get()
            ->sum(function($trx) use ($idBarang) {
                $items = $trx->id_barang;
                return isset($items[$idBarang]) ? (int) $items[$idBarang] : 0;
            });

        return $keluar1; // Choose the method that works best
    }
}
