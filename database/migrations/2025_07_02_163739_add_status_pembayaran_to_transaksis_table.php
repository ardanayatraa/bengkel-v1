<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status_pembayaran', ['belum bayar','lunas'])
                  ->default('belum bayar')
                  ->after('metode_pembayaran')
                  ->comment('Status pembayaran: lunas atau belum bayar');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('status_pembayaran');
        });
    }
};
