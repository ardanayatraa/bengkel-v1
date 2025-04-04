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
        $transaksis = Transaksi::with(['konsumen', 'barang', 'jasa', 'point'])->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $konsumens = Konsumen::all();
        $barangs = Barang::all();
        $jasas = Jasa::all();
        $points = Point::all();

        return view('transaksi.create', compact('konsumens', 'barangs', 'jasas', 'points'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_konsumen' => 'required|integer|exists:konsumens,id_konsumen',
            'id_barang' => 'nullable|integer|exists:barangs,id_barang',
            'id_jasa' => 'nullable|integer|exists:jasas,id_jasa',
            'id_point' => 'nullable|integer|exists:points,id_point',
            'tanggal_transaksi' => 'required|date',
            'total_harga' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'jumlah_point' => 'nullable|integer',
        ]);

        $validatedData['id_user'] = Auth::id(); // auto ambil user login

        Transaksi::create($validatedData);
        return redirect()->route('transaksis.index')->with('success', 'Transaksi berhasil dibuat.');
    }

    public function edit(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $konsumens = Konsumen::all();
        $barangs = Barang::all();
        $jasas = Jasa::all();
        $points = Point::all();

        return view('transaksi.edit', compact('transaksi', 'konsumens', 'barangs', 'jasas', 'points'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_konsumen' => 'required|integer|exists:konsumens,id_konsumen',
            'id_barang' => 'nullable|integer|exists:barangs,id_barang',
            'id_jasa' => 'nullable|integer|exists:jasas,id_jasa',
            'id_point' => 'nullable|integer|exists:points,id_point',
            'tanggal_transaksi' => 'required|date',
            'total_harga' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'jumlah_point' => 'nullable|integer',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update($validatedData);
        return redirect()->route('transaksis.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('transaksis.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
