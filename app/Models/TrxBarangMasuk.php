<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxBarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'trx_barang_masuks';
    protected $primaryKey = 'id_trx_barang_masuk';
    protected $fillable = ['id_barang', 'tanggal_masuk', 'nama_supplier', 'jumlah', 'total_harga'];


    public function barang()
    {
        return $this->hasMany(
            Barang::class,
            'id_barang',
            'id_barang'
        );
    }
}
