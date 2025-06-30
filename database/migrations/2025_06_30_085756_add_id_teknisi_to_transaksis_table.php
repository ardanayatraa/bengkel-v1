<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // tambahkan kolom id_teknisi
            $table->unsignedBigInteger('id_teknisi')
                  ->nullable()
                  ->after('status_service');

            // constraint foreign key ke tabel teknisis
            $table->foreign('id_teknisi')
                  ->references('id_teknisi')
                  ->on('teknisis')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // hapus foreign key dan kolom
            $table->dropForeign(['id_teknisi']);
            $table->dropColumn('id_teknisi');
        });
    }
};
