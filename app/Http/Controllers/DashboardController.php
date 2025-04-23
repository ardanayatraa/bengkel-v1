<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\TrxBarangMasuk;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Konsumen;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->level === 'kasir') {
            $transaksiCount = Transaksi::where('id_user', $user->id)->count();
            $konsumenCount = Konsumen::count();

            return view('dashboard', [
                'isKasir' => true,
                'transaksiCount' => $transaksiCount,
                'konsumenCount' => $konsumenCount,
            ]);
        }

        $supplierCount = Supplier::count();
        $barangMasukCount = TrxBarangMasuk::count();
        $userCount = User::count();

        return view('dashboard', [
            'isKasir' => false,
            'supplierCount' => $supplierCount,
            'barangMasukCount' => $barangMasukCount,
            'userCount' => $userCount,
        ]);
    }
}
