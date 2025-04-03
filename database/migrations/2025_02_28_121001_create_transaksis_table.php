<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel transaksis.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_konsumen')->nullable();
            $table->unsignedBigInteger('id_barang')->nullable();
            $table->unsignedBigInteger('id_jasa')->nullable();
            $table->unsignedBigInteger('id_point')->nullable();
            $table->date('tanggal_transaksi');
            $table->decimal('total_harga', 15, 2);
            $table->string('metode_pembayaran');
            $table->integer('jumlah_point')->default(0);
            $table->timestamps();


        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel transaksis.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
