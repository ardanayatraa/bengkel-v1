<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel points.
     */
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id('id_point');
            $table->unsignedBigInteger('id_konsumen');
            $table->date('tanggal');
            $table->integer('jumlah_point');
            $table->timestamps();

            $table->foreign('id_konsumen')->references('id_konsumen')->on('konsumens')->onDelete('cascade');
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel points.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
