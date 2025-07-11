<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Barang;
use App\Models\Jasa;
use App\Models\Point;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['konsumen','teknisi','points'])
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
        $v = $request->validate([
            'id_konsumen'         => 'required|exists:konsumens,id_konsumen',
            'id_teknisi'          => 'nullable|exists:teknisis,id_teknisi',
            'id_barang'           => 'nullable|array',
            'id_barang.*'         => 'exists:barangs,id_barang',
            'qty_barang.*'        => 'integer|min:1',
            'id_jasa'             => 'nullable|array',
            'id_jasa.*'           => 'exists:jasas,id_jasa',
            'tanggal_transaksi'   => 'required|date',
            'metode_pembayaran'   => 'required|string',
            'status_service'      => 'required|in:proses,selesai,diambil',
            'estimasi_pengerjaan' => 'nullable|string|max:191',
            'uang_diterima'       => 'required|numeric|min:0',
            'redeem_points'       => 'nullable|integer|min:0',
            'kode_referral'       => 'nullable|string|max:10', // tambahan untuk referral
        ]);

        $konsumen = Konsumen::findOrFail($v['id_konsumen']);

        // Build JSON barang=>qty
        $barangJson = [];
        foreach ($v['id_barang'] ?? [] as $id) {
            $barangJson[$id] = $v['qty_barang'][$id] ?? 1;
        }

        // Hitung total barang
        $totalBarang = 0;
        foreach ($barangJson as $id => $qty) {
            $harga = Barang::findOrFail($id)->harga_jual;
            $totalBarang += $harga * $qty;
        }

        // Hitung total jasa
        $totalJasa = collect($v['id_jasa'] ?? [])
            ->map(fn($i) => Jasa::findOrFail($i)->harga_jasa)
            ->sum();

        // Subtotal sebelum diskon
        $subtotal = $totalBarang + $totalJasa;

        // Hitung diskon poin: 10pt → Rp10.000
        $diskonPoin = 0;
        if (
            strtolower($konsumen->keterangan) === 'member'
            && !empty($v['redeem_points'])
            && $v['redeem_points'] <= $konsumen->jumlah_point
            && $v['redeem_points'] % 10 === 0
        ) {
            $diskonPoin = ($v['redeem_points'] / 10) * 10000;
            $konsumen->decrement('jumlah_point', $v['redeem_points']);
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

                // Tandai bahwa konsumen ini sudah menggunakan kode referral tersebut
                $konsumen->tandaiReferralDigunakan($v['kode_referral']);
            }
        }

        // Total akhir setelah semua diskon
        $total = max(0, $subtotal - $diskonPoin - $diskonReferral);

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'id_konsumen'              => $v['id_konsumen'],
            'id_teknisi'               => $v['id_teknisi'] ?? null,
            'id_barang'                => $barangJson,
            'id_jasa'                  => $v['id_jasa'] ?? [],
            'tanggal_transaksi'        => $v['tanggal_transaksi'],
            'metode_pembayaran'        => $v['metode_pembayaran'],
            'status_service'           => $v['status_service'],
            'estimasi_pengerjaan'      => $v['estimasi_pengerjaan'] ?? null,
            'total_harga'              => $total,
            'uang_diterima'            => $v['uang_diterima'],
            'id_user'                  => Auth::id(),
            'kode_referral_digunakan'  => $kodeReferralDigunakan,
            'diskon_referral'          => $diskonReferral,
        ]);

        // Tambah poin baru untuk member yang melakukan jasa
        if (
            strtolower($konsumen->keterangan) === 'member'
            && !empty($v['id_jasa'])
        ) {
            Point::create([
                'id_konsumen'  => $konsumen->id_konsumen,
                'id_transaksi' => $transaksi->id_transaksi,
                'tanggal'      => now()->toDateString(),
                'jumlah_point' => 1,
            ]);
            $konsumen->increment('jumlah_point', 1);
        }

        // Berikan poin reward untuk pemberi kode referral (langsung increment tanpa Point model)
        if ($konsumenPemberiReferral) {
            $konsumenPemberiReferral->increment('jumlah_point', 1);
        }

        return redirect()->route('transaksi.index')
                         ->with('success','Transaksi berhasil disimpan.');
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

        $konsumen = Konsumen::findOrFail($v['id_konsumen']);

        // Build JSON barang=>qty
        $barangJson = [];
        foreach ($v['id_barang'] ?? [] as $id) {
            $barangJson[$id] = $v['qty_barang'][$id] ?? 1;
        }

        // Hitung subtotal
        $totalBarang = array_reduce(array_keys($barangJson), fn($sum,$id) =>
            $sum + Barang::findOrFail($id)->harga_jual * $barangJson[$id], 0
        );
        $totalJasa = collect($v['id_jasa'] ?? [])
            ->map(fn($i) => Jasa::findOrFail($i)->harga_jasa)
            ->sum();

        $subtotal = $totalBarang + $totalJasa;

        // Hitung diskon poin (sama logika store)
        $diskonPoin = 0;
        if (
            strtolower($konsumen->keterangan) === 'member'
            && !empty($v['redeem_points'])
            && $v['redeem_points'] <= $konsumen->jumlah_point
            && $v['redeem_points'] % 10 === 0
        ) {
            $diskonPoin = ($v['redeem_points'] / 10) * 10000;
        }

        // Ambil transaksi yang ada untuk mempertahankan diskon referral
        $transaksi = Transaksi::findOrFail($id);
        $diskonReferral = $transaksi->diskon_referral;

        $total = max(0, $subtotal - $diskonPoin - $diskonReferral);

        // Update transaksi (kecuali data referral yang sudah ada)
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
            'uang_diterima'       => $v['uang_diterima'],
        ]);

        return redirect()->route('transaksi.index')
                         ->with('success','Transaksi berhasil diperbarui.');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi','points'])
            ->findOrFail($id);

        $barangs   = $transaksi->barangWithQty();
        $jasas     = $transaksi->jasaModels();
        $subtotal  = $barangs->sum('subtotal') + collect($jasas)->sum(fn($j)=>$j->harga_jasa);
        $diskonPoin = $transaksi->point_discount;
        $diskonReferral = $transaksi->diskon_referral;
        $kembalian = $transaksi->uang_diterima - $transaksi->total_harga;
        $sisaPoint = $transaksi->konsumen->jumlah_point;
        $diskon = $diskonPoin + $diskonReferral;
        $konsumenPemberiReferral = $transaksi->konsumenPemberiReferral();

        return view('transaksi.show', compact(
            'transaksi','barangs','jasas','diskon',
            'subtotal','diskonPoin','diskonReferral','kembalian','sisaPoint','konsumenPemberiReferral'
        ));
    }

    public function print($id)
    {
        $transaksi = Transaksi::with(['konsumen','teknisi','points'])
            ->findOrFail($id);

        $barangs  = $transaksi->barangWithQty();
        $jasas    = $transaksi->jasaModels();

        // Hitung subtotal dan diskon
        $subtotal  = $barangs->sum('subtotal') + collect($jasas)->sum(fn($j)=>$j->harga_jasa);
        $diskonPoin = $transaksi->point_discount;
        $diskonReferral = $transaksi->diskon_referral;
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
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
