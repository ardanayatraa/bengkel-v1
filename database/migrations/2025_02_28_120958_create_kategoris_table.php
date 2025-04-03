<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel kategoris.
     */
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel kategoris.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
