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
    public function index()
    {
        $transaksis = Transaksi::with(['konsumen', 'barang', 'jasa'])->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $konsumens = Konsumen::all();
        $barangs = Barang::all();
        $jasas = Jasa::all();

        return view('transaksi.create', compact('konsumens', 'barangs', 'jasas'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_konsumen' => 'required|integer|exists:konsumens,id_konsumen',
            'id_barang' => 'nullable|integer|exists:barangs,id_barang',
            'id_jasa' => 'nullable|integer|exists:jasas,id_jasa',
            'tanggal_transaksi' => 'required|date',
            'total_harga' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
        ]);

        $validatedData['id_user'] = Auth::id();

        $konsumen = Konsumen::findOrFail($request->id_konsumen);
        $totalHarga = $validatedData['total_harga'];
        $diskon = 0;

        // Terapkan diskon jika member dan punya >= 10 poin
        if (strtolower($konsumen->keterangan) === 'member' && $konsumen->jumlah_point >= 10) {
            $diskon = 10000;
            $totalHarga = max(0, $totalHarga - $diskon);
            $konsumen->decrement('jumlah_point', 10);
        }

        $transaksi = Transaksi::create([
            ...$validatedData,
            'total_harga' => $totalHarga,
        ]);

        // Berikan 1 poin untuk member
        if (strtolower($konsumen->keterangan) === 'member') {
            Point::create([
                'id_konsumen' => $konsumen->id_konsumen,
                'id_transaksi' => $transaksi->id_transaksi,
                'tanggal' => now()->toDateString(),
                'jumlah_point' => 1,
            ]);

            $konsumen->increment('jumlah_point', 1);
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $konsumens = Konsumen::all();
        $barangs = Barang::all();
        $jasas = Jasa::all();

        return view('transaksi.edit', compact('transaksi', 'konsumens', 'barangs', 'jasas'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_konsumen' => 'required|integer|exists:konsumens,id_konsumen',
            'id_barang' => 'nullable|integer|exists:barangs,id_barang',
            'id_jasa' => 'nullable|integer|exists:jasas,id_jasa',
            'tanggal_transaksi' => 'required|date',
            'total_harga' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($validatedData);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
