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

        // lama
        if ($user->level === 'kasir') {
            $transaksiCount = Transaksi::where('id_user', $user->id)->count();
            $konsumenCount  = Konsumen::count();

            // baru
            $serviceTotalToday = Transaksi::whereDate('tanggal_transaksi', Carbon::today())
                ->whereJsonLength('id_jasa', '>', 0)
                ->sum('total_harga');
            $barangCount       = Barang::count();

            return view('dashboard', compact(
                'transaksiCount',
                'konsumenCount',
                'serviceTotalToday',
                'barangCount'
            ))->with('isKasir', true);
        }

        // lama
        $supplierCount    = Supplier::count();
        $barangMasukCount = TrxBarangMasuk::count();
        $userCount        = User::count();

        // baru
        $konsumenCount     = Konsumen::count();
        $serviceTotalToday = Transaksi::whereDate('tanggal_transaksi', Carbon::today())
            ->whereJsonLength('id_jasa', '>', 0)
            ->sum('total_harga');
        $barangCount       = Barang::count();

        return view('dashboard', compact(
            'supplierCount',
            'barangMasukCount',
            'userCount',
            'konsumenCount',
            'serviceTotalToday',
            'barangCount'
        ))->with('isKasir', false);
    }
}
