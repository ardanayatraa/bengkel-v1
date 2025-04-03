<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel trx_barang_masuks.
     */
    public function up(): void
    {
        Schema::create('trx_barang_masuks', function (Blueprint $table) {
            $table->id('id_trx_barang_masuk');
            $table->unsignedBigInteger('id_barang');
            $table->date('tanggal_masuk');
            $table->string('nama_supplier');
            $table->integer('jumlah');
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel trx_barang_masuks.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_barang_masuks');
    }
};
