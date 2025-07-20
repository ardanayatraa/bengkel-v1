<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gaji_teknisis', function (Blueprint $table) {
            $table->id('id_gaji_teknisi');
            $table->unsignedBigInteger('id_teknisi');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_jasa');
            $table->decimal('harga_jasa', 15, 2); // Harga jasa yang dikerjakan
            $table->decimal('persentase_gaji', 5, 2); // Persentase gaji dari harga jasa (misal: 30.00 = 30%)
            $table->decimal('jumlah_gaji', 15, 2); // Jumlah gaji yang diterima
            $table->date('tanggal_kerja');
            $table->enum('status_pembayaran', ['belum_dibayar', 'sudah_dibayar'])->default('belum_dibayar');
            $table->date('tanggal_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisis')->onDelete('cascade');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksis')->onDelete('cascade');
            $table->foreign('id_jasa')->references('id_jasa')->on('jasas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_teknisis');
    }
};
