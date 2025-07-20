<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Supplier;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat supplier default
        $supplier = Supplier::first() ?: Supplier::create([
            'nama_supplier' => 'CV. Mitra Motor',
            'no_telp'       => '082112345678',
            'alamat'        => 'Jl. Merdeka No. 10',
        ]);



        // 3. Daftar barang
        $items = [
            ['nama'=>'Seal Depan Vario',   'beli'=>13000,'jual'=>15000],
            ['nama'=>'Seal Belakang Vario','beli'=>8000, 'jual'=>10000],
            ['nama'=>'Seal Depan Beat',    'beli'=>13000,'jual'=>15000],
            ['nama'=>'Seal Belakang Beat', 'beli'=>8000, 'jual'=>10000],
            ['nama'=>'Seal Depan Nmax',    'beli'=>15000,'jual'=>17000],
            ['nama'=>'Seal Belakang Nmax', 'beli'=>13000,'jual'=>15000],
            ['nama'=>'Seal Depan Supra',   'beli'=>13000,'jual'=>15000],
            ['nama'=>'Seal Belakang Supra','beli'=>8000, 'jual'=>10000],
            ['nama'=>'As Ukuran 8 MM',     'beli'=>13000,'jual'=>15000],
            ['nama'=>'As Ukuran 10 MM',    'beli'=>18000,'jual'=>20000],
            ['nama'=>'As Ukuran 12 MM',    'beli'=>28000,'jual'=>30000],
            ['nama'=>'As Ukuran 14 MM',    'beli'=>45000,'jual'=>50000],
        ];

        // 4. Seed Barang
        foreach ($items as $it) {
            Barang::updateOrCreate(
                ['nama_barang' => $it['nama']],
                [
                    'id_supplier' => $supplier->id_supplier,
                    'stok'        => 0,
                    'keterangan'  => null,
                    'harga_beli'  => $it['beli'],
                    'harga_jual'  => $it['jual'],
                ]
            );
        }
    }
}
