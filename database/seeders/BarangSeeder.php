<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Kategori;

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

        // 2. Buat kategori Seal & As jika belum ada
        $sealCat = Kategori::firstWhere('nama_kategori','Seal')
            ?: Kategori::create([
                'nama_kategori' => 'Seal',
                'keterangan'    => 'Segel motor berbagai tipe',
            ]);
        $asCat = Kategori::firstWhere('nama_kategori','As')
            ?: Kategori::create([
                'nama_kategori' => 'As',
                'keterangan'    => 'As roda dan mesin',
            ]);

        // 3. Daftar barang
        $items = [
            ['nama'=>'Seal Depan Vario',   'beli'=>13000,'jual'=>15000,'cat'=>$sealCat],
            ['nama'=>'Seal Belakang Vario','beli'=>8000, 'jual'=>10000,'cat'=>$sealCat],
            ['nama'=>'Seal Depan Beat',    'beli'=>13000,'jual'=>15000,'cat'=>$sealCat],
            ['nama'=>'Seal Belakang Beat', 'beli'=>8000, 'jual'=>10000,'cat'=>$sealCat],
            ['nama'=>'Seal Depan Nmax',    'beli'=>15000,'jual'=>17000,'cat'=>$sealCat],
            ['nama'=>'Seal Belakang Nmax', 'beli'=>13000,'jual'=>15000,'cat'=>$sealCat],
            ['nama'=>'Seal Depan Supra',   'beli'=>13000,'jual'=>15000,'cat'=>$sealCat],
            ['nama'=>'Seal Belakang Supra','beli'=>8000, 'jual'=>10000,'cat'=>$sealCat],
            ['nama'=>'As Ukuran 8 MM',     'beli'=>13000,'jual'=>15000,'cat'=>$asCat],
            ['nama'=>'As Ukuran 10 MM',    'beli'=>18000,'jual'=>20000,'cat'=>$asCat],
            ['nama'=>'As Ukuran 12 MM',    'beli'=>28000,'jual'=>30000,'cat'=>$asCat],
            ['nama'=>'As Ukuran 14 MM',    'beli'=>45000,'jual'=>50000,'cat'=>$asCat],
        ];

        // 4. Seed Barang
        foreach ($items as $it) {
            Barang::updateOrCreate(
                ['nama_barang' => $it['nama']],
                [
                    'id_supplier' => $supplier->id_supplier,
                    'id_kategori' => $it['cat']->id_kategori,
                    'stok'        => 0,
                    'keterangan'  => null,
                    'harga_beli'  => $it['beli'],
                    'harga_jual'  => $it['jual'],
                ]
            );
        }
    }
}
