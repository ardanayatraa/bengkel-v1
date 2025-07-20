<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teknisis', function (Blueprint $table) {
            $table->decimal('persentase_gaji', 5, 2)->default(30.00)->after('kontak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teknisis', function (Blueprint $table) {
            $table->dropColumn('persentase_gaji');
        });
    }
};
