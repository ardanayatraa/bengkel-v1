<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $table = 'points';
    protected $primaryKey = 'id_point';
    protected $fillable = ['id_konsumen', 'tanggal', 'jumlah_point'];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }
}
