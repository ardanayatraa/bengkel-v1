<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BarangController,
    DashboardController,
    DetailTransaksiController,
    JasaController,
    KategoriController,
    KonsumenController,
    LaporanBarangController,
    LaporanJasaController,
    LaporanPenjualanController,
    PointController,
    SupplierController,
    TeknisiController,
    TransaksiController,
    TrxBarangMasukController,
    UserController
};

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes dengan parameter disesuaikan
    Route::resource('barang', BarangController::class)->parameters([
        'barang' => 'barang'
    ]);

    Route::resource('detail-transaksi', DetailTransaksiController::class)->parameters([
        'detail-transaksi' => 'detail_transaksi'
    ]);

    Route::resource('jasa', JasaController::class)->parameters([
        'jasa' => 'jasa'
    ]);

    Route::resource('kategori', KategoriController::class)->parameters([
        'kategori' => 'kategori'
    ]);

    Route::resource('konsumen', KonsumenController::class)->parameters([
        'konsumen' => 'konsumen'
    ]);

    Route::get('konsumen/{konsumen}/cetak-kartu', [App\Http\Controllers\KonsumenController::class, 'cetakKartu'])
    ->name('konsumen.cetak-kartu');


    Route::resource('point', PointController::class)->parameters([
        'point' => 'point'
    ]);

    Route::resource('supplier', SupplierController::class)->parameters([
        'supplier' => 'supplier'
    ]);

    Route::resource('teknisi', TeknisiController::class);

    Route::resource('transaksi', TransaksiController::class)->parameters([
        'transaksi' => 'transaksi'
    ]);
    Route::get('transaksi/{id}', [TransaksiController::class, 'show'])
    ->name('transaksi.show');
Route::get('transaksi/{id}/print', [TransaksiController::class, 'print'])
    ->name('transaksi.print');

    Route::resource('trx-barang-masuk', TrxBarangMasukController::class)->parameters([
        'trx-barang-masuk' => 'trx_barang_masuk'
    ]);

    Route::get('trx-barang-masuk/{id}/cetak', [TrxBarangMasukController::class, 'cetak'])->name('trx-barang-masuk.cetak');


    Route::resource('user', UserController::class)->parameters([
        'user' => 'user'
    ]);

    // Group laporan


    Route::get('/laporan/jasa', [LaporanJasaController::class, 'index'])->name('laporan.jasa');
    Route::get('/laporan/jasa/pdf', [LaporanJasaController::class, 'exportPdf'])->name('laporan.jasa.pdf');

    Route::get('/laporan/barang', [LaporanBarangController::class, 'index'])->name('laporan.barang');
Route::get('/laporan/barang/pdf', [LaporanBarangController::class, 'exportPdf'])->name('laporan.barang.pdf');


Route::get('/laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
Route::get('/laporan/penjualan/pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.penjualan.pdf');

});
