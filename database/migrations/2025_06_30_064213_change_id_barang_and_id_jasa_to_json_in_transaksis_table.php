<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // butuh doctrine/dbal untuk ->change()
        Schema::table('transaksis', function (Blueprint $table) {
            $table->json('id_barang')->nullable()->change();
            $table->json('id_jasa'  )->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_barang')->nullable()->change();
            $table->unsignedBigInteger('id_jasa'  )->nullable()->change();
        });
    }
};
