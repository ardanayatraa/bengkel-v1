<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use HasFactory;

    protected $table = 'teknisis';
    protected $primaryKey = 'id_teknisi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_teknisi',
        'kontak',
        'persentase_gaji',
    ];

    protected $casts = [
        'persentase_gaji' => 'decimal:2',
    ];

    /**
     * Relasi: Teknisi memiliki banyak gaji
     */
    public function gajiTeknisis()
    {
        return $this->hasMany(GajiTeknisi::class, 'id_teknisi');
    }

    /**
     * Relasi: Teknisi memiliki banyak transaksi
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_teknisi');
    }

    /**
     * Accessor: format persentase gaji
     */
    public function getPersentaseGajiFormattedAttribute()
    {
        return $this->persentase_gaji . '%';
    }

    /**
     * Method untuk menghitung total gaji yang belum dibayar
     */
    public function getTotalGajiBelumDibayarAttribute()
    {
        return $this->gajiTeknisis()->belumDibayar()->sum('jumlah_gaji');
    }

    /**
     * Method untuk menghitung total gaji yang sudah dibayar
     */
    public function getTotalGajiSudahDibayarAttribute()
    {
        return $this->gajiTeknisis()->sudahDibayar()->sum('jumlah_gaji');
    }

    /**
     * Method untuk menghitung total gaji keseluruhan
     */
    public function getTotalGajiAttribute()
    {
        return $this->gajiTeknisis()->sum('jumlah_gaji');
    }
}
