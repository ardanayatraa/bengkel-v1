<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Konsumen extends Model
{
    use HasFactory;

    protected $table = 'konsumens';
    protected $primaryKey = 'id_konsumen';

    protected $fillable = [
        'nama_konsumen',
        'no_kendaraan',
        'no_telp',
        'alamat',
        'jumlah_point',
        'keterangan',
        'kode_referral',
        'referral_used',
    ];

    protected $casts = [
        'referral_used' => 'array', // array berisi kode referral yang sudah pernah digunakan
    ];

    /**
     * Auto generate kode referral saat membuat member baru
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($konsumen) {
            if (strtolower($konsumen->keterangan) === 'member' && empty($konsumen->kode_referral)) {
                $konsumen->kode_referral = $konsumen->generateKodeReferral();
            }
        });

        static::updating(function ($konsumen) {
            if (strtolower($konsumen->keterangan) === 'member' && empty($konsumen->kode_referral)) {
                $konsumen->kode_referral = $konsumen->generateKodeReferral();
            }
            elseif (strtolower($konsumen->keterangan) !== 'member') {
                $konsumen->kode_referral = null;
            }
        });
    }

    /**
     * Generate unique kode referral
     */
    public function generateKodeReferral()
    {
        do {
            // Format: 3 huruf dari nama + 4 digit random
            $namaPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $this->nama_konsumen), 0, 3));
            if (strlen($namaPrefix) < 3) {
                $namaPrefix = str_pad($namaPrefix, 3, 'X');
            }

            $kode = $namaPrefix . sprintf('%04d', rand(1000, 9999));
        } while (self::where('kode_referral', $kode)->exists());

        return $kode;
    }

    /**
     * Cek apakah konsumen sudah pernah menggunakan kode referral tertentu
     */
    public function sudahMenggunakanReferral($kodeReferral)
    {
        $used = $this->referral_used ?? [];
        return in_array($kodeReferral, $used);
    }

    /**
     * Tambah kode referral ke daftar yang sudah digunakan
     */
    public function tandaiReferralDigunakan($kodeReferral)
    {
        $used = $this->referral_used ?? [];
        if (!in_array($kodeReferral, $used)) {
            $used[] = $kodeReferral;
            $this->update(['referral_used' => $used]);
        }
    }

    /**
     * Validasi kode referral - METHOD YANG HILANG INI!
     */
    public static function validateKodeReferral($kodeReferral, $idKonsumenPenerima)
    {
        \Log::info('Validate Kode Referral Input:', [
            'kode_referral' => $kodeReferral,
            'id_konsumen_penerima' => $idKonsumenPenerima
        ]);

        // Cari member yang memiliki kode referral ini
        $konsumenPemberi = self::where('kode_referral', $kodeReferral)
            ->where('keterangan', 'member')
            ->first();

        \Log::info('Pencarian Konsumen Pemberi:', [
            'found' => $konsumenPemberi ? true : false,
            'data' => $konsumenPemberi ? $konsumenPemberi->toArray() : null
        ]);

        if (!$konsumenPemberi) {
            return ['valid' => false, 'message' => 'Kode referral tidak ditemukan atau tidak valid'];
        }

        $konsumenPenerima = self::find($idKonsumenPenerima);
        if (!$konsumenPenerima) {
            return ['valid' => false, 'message' => 'Konsumen tidak ditemukan'];
        }

        \Log::info('Konsumen Penerima:', $konsumenPenerima->toArray());

        // Cek apakah konsumen penerima sudah pernah menggunakan kode ini
        $sudahDigunakan = $konsumenPenerima->sudahMenggunakanReferral($kodeReferral);

        \Log::info('Pengecekan Usage:', [
            'sudah_digunakan' => $sudahDigunakan,
            'referral_used' => $konsumenPenerima->referral_used
        ]);

        if ($sudahDigunakan) {
            return ['valid' => false, 'message' => 'Anda sudah pernah menggunakan kode referral ini sebelumnya'];
        }

        // Cek apakah konsumen penerima bukan pemberi kode itu sendiri
        if ($konsumenPemberi->id_konsumen == $idKonsumenPenerima) {
            return ['valid' => false, 'message' => 'Tidak dapat menggunakan kode referral sendiri'];
        }

        return [
            'valid' => true,
            'konsumen_pemberi' => $konsumenPemberi,
            'diskon' => 5000,
            'message' => 'Kode referral valid! Anda mendapat diskon Rp 5.000'
        ];
    }

    /**
     * Relasi: Konsumen memiliki banyak transaksi.
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_konsumen');
    }

    /**
     * Relasi: Konsumen memiliki banyak log point.
     */
    public function points()
    {
        return $this->hasMany(Point::class, 'id_konsumen');
    }
}
