<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Barang;
use App\Models\Jasa;
use App\Models\GajiTeknisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['konsumen','teknisi'])
            ->orderByDesc('tanggal_transaksi')
            ->get();

        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        $jasas     = Jasa::all();
        $teknisis  = \App\Models\Teknisi::all();

        return view('transaksi.create', compact('konsumens','barangs','jasas','teknisis'));
    }

    /**
     * AJAX endpoint untuk validasi kode referral
     */
    public function validateReferral(Request $request)
    {
        $kodeReferral = $request->input('kode_referral');
        $idKonsumen = $request->input('id_konsumen');

        if (empty($kodeReferral)) {
            return response()->json(['valid' => false, 'message' => 'Kode referral tidak boleh kosong']);
        }

        $validation = Konsumen::validateKodeReferral($kodeReferral, $idKonsumen);

        return response()->json($validation);
    }

    public function store(Request $request)
    {
        // Validasi input
        $v = $request->validate([
            'id_konsumen'         => 'required|exists:konsumens,id_konsumen',
            'id_teknisi'          => 'nullable|exists:teknisis,id_teknisi',
            'id_barang'           => 'nullable|array',
            'id_barang.*'         => 'exists:barangs,id_barang',
            'qty_barang'          => 'nullable|array',
            'qty_barang.*'        => 'integer|min:1',
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
            'uang_diterima'       => 'required|numeric|min:0',
            'redeem_points'       => 'nullable|integer|min:0',
            'kode_referral'       => 'nullable|string|max:10',
        ]);

        // Build JSON barang=>qty dengan cara yang benar
        $barangJson = [];
        if (!empty($v['id_barang']) && !empty($v['qty_barang'])) {
            foreach ($v['id_barang'] as $idBarang) {
                // Pastikan qty ada dan valid
                $qty = isset($v['qty_barang'][$idBarang]) ? (int)$v['qty_barang'][$idBarang] : 1;
                if ($qty > 0) {
                    $barangJson[$idBarang] = $qty;
                }
            }
        }

        // Validasi stok sebelum memulai transaksi
        $errors = [];
        foreach ($barangJson as $idBarang => $qty) {
            $barang = Barang::findOrFail($idBarang);
            if ($barang->stok < $qty) {
                $errors[] = "Stok barang {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok}, diminta: {$qty}";
            }
        }

        if (count($errors) > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $errors]);
        }

        // Gunakan database transaction untuk memastikan konsistensi data
        try {
            return DB::transaction(function () use ($v, $barangJson) {
                $konsumen = Konsumen::findOrFail($v['id_konsumen']);

                // Hitung total barang
                $totalBarang = 0;
                foreach ($barangJson as $idBarang => $qty) {
                    $barang = Barang::findOrFail($idBarang);
                    $totalBarang += $barang->harga_jual * $qty;
                }

                // Hitung total jasa
                $totalJasa = 0;
                if (!empty($v['id_jasa'])) {
                    foreach ($v['id_jasa'] as $idJasa) {
                        $jasa = Jasa::findOrFail($idJasa);
                        $totalJasa += $jasa->harga_jasa;
                    }
                }

                // Subtotal sebelum diskon
                $subtotal = $totalBarang + $totalJasa;

                // Hitung diskon poin: 10pt → Rp10.000
                $diskonPoin = 0;
                $redeemedPoints = 0;
                if (
                    strtolower($konsumen->keterangan) === 'member'
                    && !empty($v['redeem_points'])
                    && $v['redeem_points'] <= $konsumen->jumlah_point
                    && $v['redeem_points'] % 10 === 0
                    && $v['redeem_points'] > 0
                ) {
                    $redeemedPoints = (int)$v['redeem_points'];
                    $diskonPoin = ($redeemedPoints / 10) * 10000;
                }

                // Proses kode referral
                $diskonReferral = 0;
                $kodeReferralDigunakan = null;
                $konsumenPemberiReferral = null;

                if (!empty($v['kode_referral'])) {
                    $validation = Konsumen::validateKodeReferral($v['kode_referral'], $konsumen->id_konsumen);

                    if ($validation['valid']) {
                        $diskonReferral = $validation['diskon'];
                        $kodeReferralDigunakan = $v['kode_referral'];
                        $konsumenPemberiReferral = $validation['konsumen_pemberi'];
                    }
                }

                // Total akhir setelah semua diskon
                $total = max(0, $subtotal - $diskonPoin - $diskonReferral);

                // Tentukan status pembayaran dan hitung kembalian
                $uangDiterima = (float)$v['uang_diterima'];
                $statusPembayaran = $uangDiterima >= $total ? 'lunas' : 'belum bayar';
                $kembalian = $uangDiterima >= $total ? $uangDiterima - $total : 0;

                // Siapkan data untuk disimpan
                $transaksiData = [
                    'id_konsumen'              => $v['id_konsumen'],
                    'id_teknisi'               => $v['id_teknisi'] ?? null,
                    'id_barang'                => $barangJson,
                    'id_jasa'                  => $v['id_jasa'] ?? [],
                    'tanggal_transaksi'        => $v['tanggal_transaksi'],
                    'metode_pembayaran'        => $v['metode_pembayaran'],
                    'status_service'           => $v['status_service'],
                    'estimasi_pengerjaan'      => $v['estimasi_pengerjaan'] ?? null,
                    'total_harga'              => $total,
                    'uang_diterima'            => $uangDiterima,
                    'status_pembayaran'        => $statusPembayaran,
                    'kembalian'                => $kembalian,
                    'id_user'                  => Auth::id(),
                    'kode_referral_digunakan'  => $kodeReferralDigunakan,
                    'diskon_referral'          => $diskonReferral,
                ];

                // Tambahkan point_discount hanya jika kolom ada di tabel
                try {
                    $columns = Schema::getColumnListing('transaksis');
                    if (in_array('point_discount', $columns)) {
                        $transaksiData['point_discount'] = $diskonPoin;
                    }
                } catch (\Exception $e) {
                    // Jika gagal cek kolom, skip point_discount
                    Log::warning('Cannot check columns for point_discount', ['error' => $e->getMessage()]);
                }

                // Simpan transaksi
                $transaksi = Transaksi::create($transaksiData);

                // Log data yang disimpan untuk debugging
                Log::info('Transaksi disimpan', [
                    'id_transaksi' => $transaksi->id_transaksi,
                    'subtotal' => $subtotal,
                    'diskon_poin' => $diskonPoin,
                    'diskon_referral' => $diskonReferral,
                    'total_harga' => $total,
                    'kode_referral' => $kodeReferralDigunakan
                ]);

                // KURANGI STOK BARANG
                foreach ($barangJson as $idBarang => $qty) {
                    $barang = Barang::findOrFail($idBarang);
                    $barang->decrement('stok', $qty);
                }

                // Kurangi poin yang di-redeem untuk member
                if ($redeemedPoints > 0) {
                    $this->updateKonsumenPoints($konsumen, -$redeemedPoints);
                }

                // Tandai kode referral sudah digunakan
                if ($kodeReferralDigunakan && $konsumenPemberiReferral) {
                    $konsumen->tandaiReferralDigunakan($kodeReferralDigunakan);
                }

                // Tambah poin baru untuk member yang melakukan jasa
                if (
                    strtolower($konsumen->keterangan) === 'member'
                    && !empty($v['id_jasa'])
                ) {
                    // $this->updateKonsumenPoints($konsumen, 1);
                }

                // Berikan poin reward untuk pemberi kode referral
                if ($konsumenPemberiReferral) {
                    $this->updateKonsumenPoints($konsumenPemberiReferral, 1);
                }

                // Buat gaji teknisi otomatis jika ada teknisi dan jasa
                if ($v['id_teknisi'] && !empty($v['id_jasa'])) {
                    $teknisi = \App\Models\Teknisi::findOrFail($v['id_teknisi']);

                    foreach ($v['id_jasa'] as $idJasa) {
                        $jasa = Jasa::findOrFail($idJasa);
                        $jumlahGaji = ($jasa->harga_jasa * $teknisi->persentase_gaji) / 100;

                        GajiTeknisi::create([
                            'id_teknisi' => $teknisi->id_teknisi,
                            'id_transaksi' => $transaksi->id_transaksi,
                            'id_jasa' => $jasa->id_jasa,
                            'harga_jasa' => $jasa->harga_jasa,
                            'persentase_gaji' => $teknisi->persentase_gaji,
                            'jumlah_gaji' => $jumlahGaji,
                            'tanggal_kerja' => $v['tanggal_transaksi'],
                            'status_pembayaran' => 'belum_dibayar',
                        ]);
                    }
                }

                // Log transaksi berhasil
                Log::info('Transaksi berhasil disimpan', [
                    'id_transaksi' => $transaksi->id_transaksi,
                    'konsumen' => $konsumen->nama_konsumen,
                    'total' => $total
                ]);

                return redirect()->route('transaksi.index')
                    ->with('success', 'Transaksi berhasil disimpan.');
            });

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error saat menyimpan transaksi: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        $jasas     = Jasa::all();
        $teknisis  = \App\Models\Teknisi::all();

        return view('transaksi.edit', compact('transaksi','konsumens','barangs','jasas','teknisis'));
    }

    public function update(Request $request, $id)
    {
        $v = $request->validate([
            'id_konsumen'         => 'required|exists:konsumens,id_konsumen',
            'id_teknisi'          => 'nullable|exists:teknisis,id_teknisi',
            'id_barang'           => 'nullable|array',
            'id_barang.*'         => 'exists:barangs,id_barang',
            'qty_barang'          => 'nullable|array',
            'qty_barang.*'        => 'integer|min:1',
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
            'uang_diterima'       => 'required|numeric|min:0',
            'redeem_points'       => 'nullable|integer|min:0',
        ]);

        // Build JSON barang=>qty dengan perbaikan yang sama
        $barangJson = [];
        if (!empty($v['id_barang']) && !empty($v['qty_barang'])) {
            foreach ($v['id_barang'] as $idBarang) {
                $qty = isset($v['qty_barang'][$idBarang]) ? (int)$v['qty_barang'][$idBarang] : 1;
                if ($qty > 0) {
                    $barangJson[$idBarang] = $qty;
                }
            }
        }

        // Validasi stok sebelum memulai transaksi
        $errors = [];
        foreach ($barangJson as $idBarang => $qty) {
            $barang = Barang::findOrFail($idBarang);
            if ($barang->stok < $qty) {
                $errors[] = "Stok barang {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok}, diminta: {$qty}";
            }
        }

        if (count($errors) > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $errors]);
        }

        try {
            return DB::transaction(function () use ($v, $id, $barangJson) {
                $konsumen = Konsumen::findOrFail($v['id_konsumen']);
                $transaksi = Transaksi::findOrFail($id);

                // Hitung subtotal
                $totalBarang = 0;
                foreach ($barangJson as $idBarang => $qty) {
                    $barang = Barang::findOrFail($idBarang);
                    $totalBarang += $barang->harga_jual * $qty;
                }

                $totalJasa = 0;
                if (!empty($v['id_jasa'])) {
                    foreach ($v['id_jasa'] as $idJasa) {
                        $jasa = Jasa::findOrFail($idJasa);
                        $totalJasa += $jasa->harga_jasa;
                    }
                }

                $subtotal = $totalBarang + $totalJasa;

                // Hitung diskon poin (sama logika store)
                $diskonPoin = 0;
                if (
                    strtolower($konsumen->keterangan) === 'member'
                    && !empty($v['redeem_points'])
                    && $v['redeem_points'] <= $konsumen->jumlah_point
                    && $v['redeem_points'] % 10 === 0
                    && $v['redeem_points'] > 0
                ) {
                    $diskonPoin = ($v['redeem_points'] / 10) * 10000;
                }

                // Pertahankan diskon referral yang sudah ada
                $diskonReferral = $transaksi->diskon_referral ?? 0;

                $total = max(0, $subtotal - $diskonPoin - $diskonReferral);

                // Tentukan status pembayaran
                $uangDiterima = (float)$v['uang_diterima'];
                $statusPembayaran = $uangDiterima >= $total ? 'lunas' : 'belum bayar';
                $kembalian = $uangDiterima >= $total ? $uangDiterima - $total : 0;

                // Update transaksi
                $transaksi->update([
                    'id_konsumen'         => $v['id_konsumen'],
                    'id_teknisi'          => $v['id_teknisi'] ?? null,
                    'id_barang'           => $barangJson,
                    'id_jasa'             => $v['id_jasa'] ?? [],
                    'tanggal_transaksi'   => $v['tanggal_transaksi'],
                    'metode_pembayaran'   => $v['metode_pembayaran'],
                    'status_service'      => $v['status_service'],
                    'estimasi_pengerjaan' => $v['estimasi_pengerjaan'] ?? null,
                    'total_harga'         => $total,
                    'uang_diterima'       => $uangDiterima,
                    'status_pembayaran'   => $statusPembayaran,
                    'kembalian'           => $kembalian,
                ]);

                return redirect()->route('transaksi.index')
                    ->with('success', 'Transaksi berhasil diperbarui.');
            });

        } catch (\Exception $e) {
            Log::error('Error saat update transaksi: ' . $e->getMessage(), [
                'transaksi_id' => $id,
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui transaksi: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi'])
            ->findOrFail($id);

        $barangs   = $transaksi->barangWithQty();
        $jasas     = $transaksi->jasaModels();

        // Hitung subtotal dari barang dan jasa
        $subtotalBarang = $barangs->sum('subtotal');
        $subtotalJasa = collect($jasas)->sum(fn($j)=>$j->harga_jasa);
        $subtotal = $subtotalBarang + $subtotalJasa;

        // PERBAIKAN: Ambil diskon referral langsung dari atribut transaksi
        $diskonReferral = $transaksi->getAttribute('diskon_referral') ?? 0;

        // PERBAIKAN: Hitung diskon poin dengan benar
        // Cek apakah ada kolom point_discount di database
        $attributes = $transaksi->getAttributes();
        if (array_key_exists('point_discount', $attributes) && $transaksi->point_discount !== null) {
            $diskonPoin = $transaksi->point_discount;
        } else {
            // Hitung dari selisih: subtotal - diskon_referral - total_harga = diskon_poin
            $diskonPoin = max(0, $subtotal - $diskonReferral - $transaksi->total_harga);
        }

        $kembalian = max(0, $transaksi->uang_diterima - $transaksi->total_harga);
        $sisaPoint = $transaksi->konsumen->jumlah_point;
        $diskon = $diskonPoin + $diskonReferral;

        // PERBAIKAN: Cek konsumen pemberi referral dengan cara yang lebih aman
        $konsumenPemberiReferral = null;
        if ($transaksi->kode_referral_digunakan) {
            $konsumenPemberiReferral = \App\Models\Konsumen::where('kode_referral', $transaksi->kode_referral_digunakan)->first();
        }

        return view('transaksi.show', compact(
            'transaksi','barangs','jasas','diskon',
            'subtotal','diskonPoin','diskonReferral','kembalian','sisaPoint','konsumenPemberiReferral'
        ));
    }

    public function print($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi'])
            ->findOrFail($id);

        $barangs  = $transaksi->barangWithQty();
        $jasas    = $transaksi->jasaModels();

        // Hitung subtotal dan diskon
        $subtotal  = $barangs->sum('subtotal') + collect($jasas)->sum(fn($j)=>$j->harga_jasa);
        $diskonPoin = $transaksi->point_discount ?? 0;
        $diskonReferral = $transaksi->diskon_referral ?? 0;
        $kembalian = $transaksi->uang_diterima - $transaksi->total_harga;
        $sisaPoint = $transaksi->konsumen->jumlah_point;
        $diskon = $diskonPoin + $diskonReferral;
        $konsumenPemberiReferral = $transaksi->konsumenPemberiReferral();

        // Lebar kertas: 80 mm → poin (1 mm ≈ 2.83465 pt)
        $widthPt = 80 * 3.83465;

        // Hitung tinggi: header(8mm) + tiap baris ~8mm
        $lineCount = 6                     // header & info
                   + $barangs->count()
                   + $jasas->count()
                   + 6;                    // footer & totals (tambah 1 untuk referral)
        $heightMm  = 8 + ($lineCount * 8);
        $heightPt  = $heightMm * 2.83465;

        $pdf = Pdf::loadView('transaksi.print', compact(
            'transaksi','barangs','jasas','diskon',
            'subtotal','diskonPoin','diskonReferral','kembalian','sisaPoint','konsumenPemberiReferral'
        ))
        ->setPaper([0, 0, $widthPt, $heightPt]);

        return $pdf->stream("nota_{$transaksi->id_transaksi}.pdf");
    }

    public function destroy($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $transaksi = Transaksi::findOrFail($id);

                // Ambil data transaksi sebelum dihapus
                $konsumen = $transaksi->konsumen;
                $konsumenPemberiReferral = $transaksi->konsumenPemberiReferral();

                // Kembalikan stok barang
                if (!empty($transaksi->id_barang)) {
                    foreach ($transaksi->id_barang as $idBarang => $qty) {
                        $barang = Barang::find($idBarang);
                        if ($barang) {
                            $barang->increment('stok', $qty);
                        }
                    }
                }

                // Kembalikan point yang sudah digunakan (redeem points)
                $diskonPoin = $this->getPointDiscountFromTransaksi($transaksi);
                if ($diskonPoin > 0) {
                    $redeemedPoints = ($diskonPoin / 10000) * 10;
                    $this->updateKonsumenPoints($konsumen, $redeemedPoints);
                }

                // Kurangi point yang diberikan untuk jasa (jika ada)
                if (
                    strtolower($konsumen->keterangan) === 'member'
                    && !empty($transaksi->id_jasa)
                ) {
                    $this->updateKonsumenPoints($konsumen, -1);
                }

                // Kurangi point reward untuk pemberi referral (jika ada)
                if ($konsumenPemberiReferral) {
                    $this->updateKonsumenPoints($konsumenPemberiReferral, -1);
                }

                // Hapus gaji teknisi terkait
                GajiTeknisi::where('id_transaksi', $transaksi->id_transaksi)->delete();

                // Hapus transaksi
                $transaksi->delete();

                return redirect()
                    ->route('transaksi.index')
                    ->with('success', 'Transaksi berhasil dihapus.');
            });

        } catch (\Exception $e) {
            Log::error('Error saat hapus transaksi: ' . $e->getMessage(), [
                'transaksi_id' => $id,
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }
    }

    /**
     * Safely update konsumen points
     */
    private function updateKonsumenPoints($konsumen, $pointChange)
    {
        try {
            // Refresh model untuk memastikan data terbaru
            $konsumen->refresh();

            // Update points dengan cara yang aman
            $currentPoints = $konsumen->jumlah_point ?? 0;
            $newPoints = max(0, $currentPoints + $pointChange); // Tidak boleh negatif

            $konsumen->update(['jumlah_point' => $newPoints]);

            Log::info("Points updated", [
                'konsumen_id' => $konsumen->id_konsumen,
                'old_points' => $currentPoints,
                'change' => $pointChange,
                'new_points' => $newPoints
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to update points", [
                'konsumen_id' => $konsumen->id_konsumen ?? 'unknown',
                'point_change' => $pointChange,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get point discount value from transaksi with better logic
     */
    private function getPointDiscountFromTransaksi($transaksi)
    {
        // Cek apakah ada kolom point_discount
        $attributes = $transaksi->getAttributes();
        if (array_key_exists('point_discount', $attributes) && $transaksi->point_discount !== null) {
            return $transaksi->point_discount;
        }

        // Fallback: hitung dari subtotal - diskon_referral - total_harga
        try {
            $barangs = $transaksi->barangWithQty();
            $jasas = $transaksi->jasaModels();
            $subtotal = $barangs->sum('subtotal') + collect($jasas)->sum('harga_jasa');
            $diskonReferral = $transaksi->getAttribute('diskon_referral') ?? 0;

            return max(0, $subtotal - $diskonReferral - $transaksi->total_harga);
        } catch (\Exception $e) {
            Log::error('Failed to calculate point discount', [
                'transaksi_id' => $transaksi->id_transaksi,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    public function prosesBayar(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $request->validate([
            'uang_diterima' => 'required|numeric|min:' . $transaksi->total_harga,
        ]);

        $uangDiterima = $request->input('uang_diterima');
        $kembalian = $uangDiterima - $transaksi->total_harga;

        $transaksi->update([
            'status_pembayaran' => 'lunas',
            'uang_diterima'     => $uangDiterima,
            'kembalian'         => $kembalian,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diproses.');
    }
}
