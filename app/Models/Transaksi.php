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
        'id_teknisi',
        'id_konsumen',
        'id_barang',
        'id_jasa',
        'tanggal_transaksi',
        'total_harga',
        'metode_pembayaran',
        'status_service',
        'estimasi_pengerjaan',
        'uang_diterima',
        'status_pembayaran',
        'kode_referral_digunakan',  // kode referral yang digunakan di transaksi ini
        'diskon_referral',          // nominal diskon dari referral
    ];

    protected $casts = [
        'tanggal_transaksi'   => 'date',
        'estimasi_pengerjaan' => 'string',
        'id_barang'           => 'array',
        'id_jasa'             => 'array',
        'diskon_referral'     => 'decimal:2',
    ];

    /**
     * Transaksi dimiliki oleh satu konsumen.
     */
    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }

    /**
     * Ambil koleksi Barang dari JSON array id_barang.
     */
    public function barangModels()
    {
        $raw = $this->id_barang;

        if (is_null($raw)) {
            $ids = [];
        } elseif (! is_array($raw)) {
            $decoded = json_decode($raw, true);
            $ids = is_array($decoded) ? $decoded : [$raw];
        } else {
            $ids = $raw;
        }

        return Barang::whereIn('id_barang', $ids)->get();
    }

    /**
     * Ambil koleksi Jasa dari JSON array id_jasa.
     */
    public function jasaModels()
    {
        $ids = $this->id_jasa;

        if (is_null($ids)) {
            $ids = [];
        }
        elseif (! is_array($ids)) {
            $decoded = json_decode($ids, true);
            $ids = is_array($decoded) ? $decoded : [$ids];
        }

        return \App\Models\Jasa::whereIn('id_jasa', $ids)->get();
    }

    /**
     * Transaksi menghasilkan banyak entri point.
     */
    public function points()
    {
        return $this->hasMany(Point::class, 'id_transaksi');
    }

    // Accessor: total poin ditukar untuk transaksi ini
    public function getRedeemedPointsAttribute(): int
    {
        $neg = $this->points()
                    ->where('jumlah_point','<',0)
                    ->sum('jumlah_point');
        return abs($neg);
    }

    // Accessor: diskon Rupiah dari poin (10pt → Rp10.000)
    public function getPointDiscountAttribute(): int
    {
        return intdiv($this->redeemed_points,10) * 10000;
    }

    // Accessor: total diskon (poin + referral)
    public function getTotalDiscountAttribute(): float
    {
        return $this->point_discount + $this->diskon_referral;
    }

    /**
     * Transaksi dikerjakan oleh satu teknisi.
     */
    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    /**
     * Kembalikan koleksi barang beserta qty-nya dari JSON id_barang.
     */
    public function barangWithQty()
    {
        $out = collect();
        $arr = $this->id_barang ?: [];

        foreach ($arr as $barangId => $qty) {
            $barang = Barang::find($barangId);
            if (! $barang) {
                continue;
            }
            $out->push((object)[
                'model' => $barang,
                'qty'   => $qty,
                'subtotal' => $barang->harga_jual * $qty,
            ]);
        }

        return $out;
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_user')
                    ->where('level', 'kasir');
    }

    /**
     * Accessor: total semua barang di transaksi ini.
     */
    public function getCalculatedTotalAttribute(): int
    {
        return $this->barangWithQty()->sum('subtotal');
    }

    /**
     * Get konsumen pemberi referral jika ada
     */
    public function konsumenPemberiReferral()
    {
        if ($this->kode_referral_digunakan) {
            return Konsumen::where('kode_referral', $this->kode_referral_digunakan)->first();
        }
        return null;
    }

    /**
     * Relasi ke konsumen pemberi referral
     */
    public function pemberiReferral()
    {
        return $this->belongsTo(Konsumen::class, 'kode_referral_digunakan', 'kode_referral');
    }
}
