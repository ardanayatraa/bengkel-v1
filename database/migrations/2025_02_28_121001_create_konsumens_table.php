<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel konsumens.
     */
    public function up(): void
    {
        Schema::create('konsumens', function (Blueprint $table) {
            $table->id('id_konsumen');
            $table->string('nama_konsumen');
            $table->string('no_kendaraan')->nullable();
            $table->string('no_telp')->nullable();
            $table->text('alamat')->nullable();
            $table->integer('jumlah_point')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel konsumens.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsumens');
    }
};
