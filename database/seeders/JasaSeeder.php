<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jasa;

class JasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jasas = [
            ['nama_jasa' => 'Service Seal Depan Vario', 'harga_jasa' => 110000, 'keterangan' => null],
            ['nama_jasa' => 'Service Seal Belakang Vario', 'harga_jasa' => 100000, 'keterangan' => null],
            ['nama_jasa' => 'Service Seal Depan Beat', 'harga_jasa' => 110000, 'keterangan' => null],
            ['nama_jasa' => 'Service Seal Belakang Beat', 'harga_jasa' => 100000, 'keterangan' => null],
            ['nama_jasa' => 'Service Seal Depan Nmax', 'harga_jasa' => 130000, 'keterangan' => null],
            ['nama_jasa' => 'Service Seal Belakang Nmax', 'harga_jasa' => 110000, 'keterangan' => null],
            ['nama_jasa' => 'Service Seal Depan Supra', 'harga_jasa' => 110000, 'keterangan' => null],
            ['nama_jasa' => 'Service Seal Belakang Supra', 'harga_jasa' => 100000, 'keterangan' => null],
            ['nama_jasa' => 'Service Shock Depan Vario', 'harga_jasa' => 130000, 'keterangan' => null],
            ['nama_jasa' => 'Service Shock Belakang Vario', 'harga_jasa' => 120000, 'keterangan' => null],
        ];

        foreach ($jasas as $jasa) {
            Jasa::create($jasa);
        }
    }
}
