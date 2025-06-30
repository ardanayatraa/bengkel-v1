<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status_service', ['proses', 'selesai', 'diambil'])
                  ->default('proses')
                  ->after('metode_pembayaran');
            $table->string('estimasi_pengerjaan')
                  ->nullable()
                  ->after('status_service');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('estimasi_pengerjaan');
            $table->dropColumn('status_service');
        });
    }
};
