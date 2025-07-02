<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\TrxBarangMasuk;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Barang;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();

        if ($user->level === 'kasir') {
            // Data Keseluruhan
            $transaksiCount = Transaksi::count();
            $konsumenCount = Konsumen::count();
            $barangCount = Barang::count();
            $pendapatanTotal = Transaksi::sum('total_harga');

            // Data Hari Ini
            $transaksiTodayCount = Transaksi::whereDate('tanggal_transaksi', $today)->count();
            $konsumenTodayCount = Konsumen::whereDate('created_at', $today)->count();
            $serviceTotalToday = Transaksi::whereDate('tanggal_transaksi', $today)
                ->whereJsonLength('id_jasa', '>', 0)
                ->sum('total_harga');
            $pendapatanTodayTotal = Transaksi::whereDate('tanggal_transaksi', $today)
                ->sum('total_harga');

            return view('dashboard', compact(
                // Data Keseluruhan
                'transaksiCount',
                'konsumenCount',
                'barangCount',
                'pendapatanTotal',
                // Data Hari Ini
                'transaksiTodayCount',
                'konsumenTodayCount',
                'serviceTotalToday',
                'pendapatanTodayTotal'
            ))->with('isKasir', true);
        }

        // Data Keseluruhan untuk Admin
        $supplierCount = Supplier::count();
        $barangMasukCount = TrxBarangMasuk::count();
        $userCount = User::count();
        $konsumenCount = Konsumen::count();
        $barangCount = Barang::count();
        $pendapatanTotal = Transaksi::sum('total_harga');

        // Data Hari Ini untuk Admin
        $transaksiTodayCount = Transaksi::whereDate('tanggal_transaksi', $today)->count();
        $barangMasukTodayCount = TrxBarangMasuk::whereDate('created_at', $today)->count();
        $serviceTotalToday = Transaksi::whereDate('tanggal_transaksi', $today)
            ->whereJsonLength('id_jasa', '>', 0)
            ->sum('total_harga');
        $pendapatanTodayTotal = Transaksi::whereDate('tanggal_transaksi', $today)
            ->sum('total_harga');

        return view('dashboard', compact(
            // Data Keseluruhan
            'supplierCount',
            'barangMasukCount',
            'userCount',
            'konsumenCount',
            'barangCount',
            'pendapatanTotal',
            // Data Hari Ini
            'transaksiTodayCount',
            'barangMasukTodayCount',
            'serviceTotalToday',
            'pendapatanTodayTotal'
        ))->with('isKasir', false);
    }
}
