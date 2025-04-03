<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'id_user', 'id_konsumen', 'id_barang', 'id_jasa', 'id_point',
        'tanggal_transaksi', 'total_harga', 'metode_pembayaran', 'jumlah_point'
    ];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function jasa()
    {
        return $this->belongsTo(Jasa::class, 'id_jasa');
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'id_point');
    }
}
