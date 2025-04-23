<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $table = 'points';
    protected $primaryKey = 'id_point';

    protected $fillable = [
        'id_konsumen',
        'id_transaksi',
        'tanggal',
        'jumlah_point',
        'keterangan',
    ];

    /**
     * Relasi: Point milik satu konsumen.
     */
    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }

    /**
     * Relasi: Point berasal dari satu transaksi.
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }
}
