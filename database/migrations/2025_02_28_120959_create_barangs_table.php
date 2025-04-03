<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel barangs.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->unsignedBigInteger('id_supplier');
            $table->unsignedBigInteger('id_kategori');
            $table->string('nama_barang');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->integer('stok')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel barangs.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
