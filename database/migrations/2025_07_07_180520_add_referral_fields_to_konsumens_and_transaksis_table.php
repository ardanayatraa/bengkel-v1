<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah kolom ke tabel konsumens
        Schema::table('konsumens', function (Blueprint $table) {
            $table->string('kode_referral', 10)->nullable()->unique()->after('keterangan');
            $table->json('referral_used')->nullable()->after('kode_referral');
        });

        // Tambah kolom ke tabel transaksis untuk tracking referral
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('kode_referral_digunakan', 10)->nullable()->after('status_pembayaran');
            $table->decimal('diskon_referral', 10, 2)->default(0)->after('kode_referral_digunakan');
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['kode_referral_digunakan', 'diskon_referral']);
        });

        Schema::table('konsumens', function (Blueprint $table) {
            $table->dropColumn(['kode_referral', 'referral_used']);
        });
    }
};
