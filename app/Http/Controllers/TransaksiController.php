<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Konsumen;
use App\Models\Barang;
use App\Models\Jasa;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Tampilkan daftar semua transaksi beserta relasinya.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $transaksis = Transaksi::with(['konsumen', 'barang', 'jasa'])->get();

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Tampilkan form untuk membuat transaksi baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        $jasas     = Jasa::all();

        return view('transaksi.create', compact('konsumens', 'barangs', 'jasas'));
    }

    /**
     * Simpan transaksi baru ke database.
     *
     * - Member dengan ≥10 poin dapat diskon Rp10.000 (otomatis mengurangi 10 poin).
     * - Poin baru (1) hanya diberikan jika transaksi mencakup jasa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'id_konsumen'       => 'required|integer|exists:konsumens,id_konsumen',
            'id_barang'         => 'nullable|integer|exists:barangs,id_barang',
            'id_jasa'           => 'nullable|integer|exists:jasas,id_jasa',
            'tanggal_transaksi' => 'required|date',
            'total_harga'       => 'required|numeric',
            'metode_pembayaran' => 'required|string',
        ]);

        // 2. Ambil model Konsumen
        $konsumen  = Konsumen::findOrFail($validated['id_konsumen']);
        $hargaAwal = $validated['total_harga'];
        $diskon    = 0;

        // 3. Terapkan diskon member jika poin ≥10
        if (strtolower($konsumen->keterangan) === 'member' && $konsumen->jumlah_point >= 10) {
            $diskon    = 10000;
            $hargaAkhir = max(0, $hargaAwal - $diskon);
            $konsumen->decrement('jumlah_point', 10);
        } else {
            $hargaAkhir = $hargaAwal;
        }

        // 4. Gabungkan data menggunakan array_merge
        $dataToCreate = array_merge($validated, [
            'id_user'     => Auth::id(),
            'total_harga' => $hargaAkhir,
        ]);

        // 5. Buat transaksi
        $transaksi = Transaksi::create($dataToCreate);

        // 6. Berikan 1 poin untuk member jika ada jasa
        if (
            strtolower($konsumen->keterangan) === 'member'
            && ! is_null($validated['id_jasa'])
        ) {
            Point::create([
                'id_konsumen'   => $konsumen->id_konsumen,
                'id_transaksi'  => $transaksi->id_transaksi,
                'tanggal'       => now()->toDateString(),
                'jumlah_point'  => 1,
            ]);
            $konsumen->increment('jumlah_point', 1);
        }

        // 7. Redirect dengan pesan sukses
        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Tampilkan form edit untuk transaksi tertentu.
     *
     * @param  int|string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $konsumens = Konsumen::all();
        $barangs   = Barang::all();
        $jasas     = Jasa::all();

        return view('transaksi.edit', compact('transaksi', 'konsumens', 'barangs', 'jasas'));
    }

    /**
     * Update data transaksi di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_konsumen'       => 'required|integer|exists:konsumens,id_konsumen',
            'id_barang'         => 'nullable|integer|exists:barangs,id_barang',
            'id_jasa'           => 'nullable|integer|exists:jasas,id_jasa',
            'tanggal_transaksi' => 'required|date',
            'total_harga'       => 'required|numeric',
            'metode_pembayaran' => 'required|string',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($validated);

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Hapus transaksi dari database.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
