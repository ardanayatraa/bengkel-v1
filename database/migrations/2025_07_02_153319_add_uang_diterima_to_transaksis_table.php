<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk menambah kolom uang_diterima.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // menambahkan kolom uang_diterima setelah total_harga
            $table->decimal('uang_diterima', 15, 2)
                  ->after('total_harga')
                  ->comment('Jumlah uang yang diterima dari konsumen');
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus kolom uang_diterima.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('uang_diterima');
        });
    }
};
