<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    use HasFactory;

    protected $table = 'konsumens';
    protected $primaryKey = 'id_konsumen';
    protected $fillable = [
        'nama_konsumen', 'no_kendaraan', 'no_telp', 'alamat',
        'jumlah_point', 'keterangan'
    ];

    public function points()
    {
        return $this->hasMany(Point::class, 'id_konsumen');
    }
}
