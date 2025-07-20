<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teknisi;

class TeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teknisi::create([
            'nama_teknisi' => 'Ahmad Teknisi',
            'kontak' => '08123456789',
            'persentase_gaji' => 30.00,
        ]);

        Teknisi::create([
            'nama_teknisi' => 'Budi Teknisi',
            'kontak' => '08123456790',
            'persentase_gaji' => 25.00,
        ]);

        Teknisi::create([
            'nama_teknisi' => 'Citra Teknisi',
            'kontak' => '08123456791',
            'persentase_gaji' => 35.00,
        ]);
    }
}
