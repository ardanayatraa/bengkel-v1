<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    use HasFactory;

    protected $table = 'jasas';
    protected $primaryKey = 'id_jasa';
    protected $fillable = ['nama_jasa', 'harga_jasa', 'keterangan'];
}
