<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            ['nama_barang' => 'Seal Depan Vario', 'harga_beli' => 13000, 'harga_jual' => 15000],
            ['nama_barang' => 'Seal Belakang Vario', 'harga_beli' => 8000, 'harga_jual' => 10000],
            ['nama_barang' => 'Seal Depan Beat', 'harga_beli' => 13000, 'harga_jual' => 15000],
            ['nama_barang' => 'Seal Belakang Beat', 'harga_beli' => 8000, 'harga_jual' => 10000],
            ['nama_barang' => 'Seal Depan Nmax', 'harga_beli' => 15000, 'harga_jual' => 17000],
            ['nama_barang' => 'Seal Belakang Nmax', 'harga_beli' => 13000, 'harga_jual' => 15000],
            ['nama_barang' => 'Seal Depan Supra', 'harga_beli' => 13000, 'harga_jual' => 15000],
            ['nama_barang' => 'Seal Belakang Supra', 'harga_beli' => 8000, 'harga_jual' => 10000],
            ['nama_barang' => 'As Ukuran 8 MM', 'harga_beli' => 13000, 'harga_jual' => 15000],
            ['nama_barang' => 'As Ukuran 10 MM', 'harga_beli' => 18000, 'harga_jual' => 20000],
            ['nama_barang' => 'As Ukuran 12 MM', 'harga_beli' => 28000, 'harga_jual' => 30000],
            ['nama_barang' => 'As Ukuran 14 MM', 'harga_beli' => 45000, 'harga_jual' => 50000],
        ];

        foreach ($barangs as $barang) {
            Barang::create([
                'id_supplier' => 0,
                'id_kategori' => 0,
                'stok' => 0,
                'keterangan' => null,
                'nama_barang' => $barang['nama_barang'],
                'harga_beli' => $barang['harga_beli'],
                'harga_jual' => $barang['harga_jual'],
            ]);
        }
    }
}
