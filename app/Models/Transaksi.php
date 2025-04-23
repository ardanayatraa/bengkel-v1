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
        'id_user',
        'id_konsumen',
        'id_barang',
        'id_jasa',
        'tanggal_transaksi',
        'total_harga',
        'metode_pembayaran',
    ];

    /**
     * Relasi: Transaksi dimiliki oleh satu konsumen.
     */
    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }

    /**
     * Relasi: Transaksi bisa memiliki satu barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    /**
     * Relasi: Transaksi bisa memiliki satu jasa.
     */
    public function jasa()
    {
        return $this->belongsTo(Jasa::class, 'id_jasa');
    }

    /**
     * Relasi: Transaksi menghasilkan banyak entri point.
     */
    public function points()
    {
        return $this->hasMany(Point::class, 'id_transaksi');
    }
}
