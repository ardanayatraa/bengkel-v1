<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BarangController,
    DetailTransaksiController,
    JasaController,
    KategoriController,
    KonsumenController,
    PointController,
    SupplierController,
    TransaksiController,
    TrxBarangMasukController,
    UserController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resources([
        'barang' => BarangController::class,
        'detail-transaksi' => DetailTransaksiController::class,
        'jasa' => JasaController::class,
        'kategori' => KategoriController::class,
        'konsumen' => KonsumenController::class,
        'point' => PointController::class,
        'supplier' => SupplierController::class,
        'transaksi' => TransaksiController::class,
        'trx-barang-masuk' => TrxBarangMasukController::class,
        'user' => UserController::class,
    ]);

    Route::prefix('laporan')->group(function () {
        Route::get('/jasa', function () {
            return 1;
        })->name('laporan.jasa');

        Route::get('/penjualan', function () {
            return 1;
        })->name('laporan.penjualan');

        Route::get('/barang', function () {
            return 1;
        })->name('laporan.barang');
    });
});
