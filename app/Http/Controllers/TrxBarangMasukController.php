<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TrxBarangMasuk;
use Illuminate\Http\Request;

class TrxBarangMasukController extends Controller
{
    public function index()
    {
        $trxBarangMasuk = TrxBarangMasuk::with('barang')->get();
        return view('TrxBarangMasuk.index', compact('trxBarangMasuk'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('TrxBarangMasuk.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_barang' => 'required|integer|exists:barangs,id_barang',
            'tanggal_masuk' => 'required|date',
            'nama_supplier' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
        ]);

        TrxBarangMasuk::create($validatedData);
        return redirect()->route('trx-barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $trx = TrxBarangMasuk::findOrFail($id);
        $barangs = Barang::all();
        return view('TrxBarangMasuk.edit', compact('trx', 'barangs'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_barang' => 'required|integer|exists:barangs,id_barang',
            'tanggal_masuk' => 'required|date',
            'nama_supplier' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $trx = TrxBarangMasuk::findOrFail($id);
        $trx->update($validatedData);
        return redirect()->route('trx-barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $trx = TrxBarangMasuk::findOrFail($id);
        $trx->delete();
        return redirect()->route('trx-barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil dihapus.');
    }

    public function show(string $id)
    {
        $trxBarangMasuk = TrxBarangMasuk::with('barang')->findOrFail($id);
        return view('TrxBarangMasuk.show', compact('trxBarangMasuk'));
    }
}
