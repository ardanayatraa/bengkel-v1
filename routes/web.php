<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BarangController,
    DashboardController,
    DetailTransaksiController,
    JasaController,
    KonsumenController,
    LaporanBarangController,
    LaporanJasaController,
    LaporanPenjualanController,
    PointController,
    SupplierController,
    TeknisiController,
    TransaksiController,
    TrxBarangMasukController,
    UserController,
    GajiTeknisiController
};


use App\Http\Controllers\LaporanPenjualanBarangController;
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

    Route::post('transaksi/validate-referral', [TransaksiController::class, 'validateReferral'])
        ->name('transaksi.validate-referral');

    Route::resource('gaji-teknisi', GajiTeknisiController::class)->parameters([
        'gaji-teknisi' => 'gaji_teknisi'
    ]);

    Route::post('gaji-teknisi/bayar/{id}', [GajiTeknisiController::class, 'bayarGaji'])
        ->name('gaji-teknisi.bayar');

    Route::post('gaji-teknisi/bayar-semua', [GajiTeknisiController::class, 'bayarSemuaGaji'])
        ->name('gaji-teknisi.bayar-semua');

    Route::post('gaji-teknisi/generate-otomatis', [GajiTeknisiController::class, 'generateGajiOtomatis'])
        ->name('gaji-teknisi.generate-otomatis');

    Route::get('gaji-teknisi/laporan', [GajiTeknisiController::class, 'laporan'])
        ->name('gaji-teknisi.laporan');

Route::prefix('transaksi/{id}')->group(function () {
    Route::post('/bayar', [TransaksiController::class, 'prosesBayar'])->name('transaksi.bayar');
    Route::get('/print', [TransaksiController::class, 'print'])->name('transaksi.print');
    Route::get('/', [TransaksiController::class, 'show'])->name('transaksi.show');
});


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


Route::prefix('laporan')->group(function(){
    Route::get('jual-barang', [LaporanPenjualanBarangController::class, 'index'])
         ->name('laporan.jual.barang');
    Route::get('jual-barang/pdf', [LaporanPenjualanBarangController::class, 'exportPdf'])
         ->name('laporan.jual.barang.pdf');
});


});
