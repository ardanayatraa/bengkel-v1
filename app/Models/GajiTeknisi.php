<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiTeknisi extends Model
{
    use HasFactory;

    protected $table = 'gaji_teknisis';
    protected $primaryKey = 'id_gaji_teknisi';

    protected $fillable = [
        'id_teknisi',
        'id_transaksi',
        'id_jasa',
        'harga_jasa',
        'persentase_gaji',
        'jumlah_gaji',
        'tanggal_kerja',
        'status_pembayaran',
        'tanggal_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kerja' => 'date',
        'tanggal_pembayaran' => 'date',
        'harga_jasa' => 'decimal:2',
        'persentase_gaji' => 'decimal:2',
        'jumlah_gaji' => 'decimal:2',
    ];

    /**
     * Relasi: Gaji teknisi milik satu teknisi
     */
    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    /**
     * Relasi: Gaji teknisi berasal dari satu transaksi
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    /**
     * Relasi: Gaji teknisi untuk satu jasa
     */
    public function jasa()
    {
        return $this->belongsTo(Jasa::class, 'id_jasa');
    }

    /**
     * Scope untuk gaji yang belum dibayar
     */
    public function scopeBelumDibayar($query)
    {
        return $query->where('status_pembayaran', 'belum_dibayar');
    }

    /**
     * Scope untuk gaji yang sudah dibayar
     */
    public function scopeSudahDibayar($query)
    {
        return $query->where('status_pembayaran', 'sudah_dibayar');
    }

    /**
     * Accessor: format status pembayaran
     */
    public function getStatusPembayaranFormattedAttribute()
    {
        return $this->status_pembayaran === 'sudah_dibayar' ? 'Sudah Dibayar' : 'Belum Dibayar';
    }

    /**
     * Accessor: format persentase gaji
     */
    public function getPersentaseGajiFormattedAttribute()
    {
        return $this->persentase_gaji . '%';
    }
}
