<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel detail_transaksis.
     */
    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id('id_detail_transaksi');
            $table->unsignedBigInteger('id_transaksi');
            $table->integer('jumlah');
            $table->decimal('total_diskon', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel detail_transaksis.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
