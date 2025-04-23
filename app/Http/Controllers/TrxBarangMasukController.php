<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\TrxBarangMasuk;
use Illuminate\Http\Request;

class TrxBarangMasukController extends Controller
{
    public function index()
    {
        $trxBarangMasuk = TrxBarangMasuk::with('barang')->get();
        return view('trx-barang-masuk.index', compact('trxBarangMasuk'));
    }

    public function create()
    {
        $barangs = Barang::all();
        $suppliers = Supplier::all();
        return view('trx-barang-masuk.create', compact('barangs','suppliers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_barang' => 'required|integer|exists:barangs,id_barang',
            'tanggal_masuk' => 'required|date',
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
        return view('trx-barang-masuk.edit', compact('trx', 'barangs'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_barang' => 'required|integer|exists:barangs,id_barang',
            'tanggal_masuk' => 'required|date',

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
        $trx = TrxBarangMasuk::with('barang.supplier')->findOrFail($id);
        return view('trx-barang-masuk.show', compact('trx'));
    }

    public function cetak(string $id)
{
    $trx = TrxBarangMasuk::with('barang.supplier')->findOrFail($id);
    $pdf = Pdf::loadView('trx-barang-masuk.cetak', compact('trx'))->setPaper('A4');
    return $pdf->stream('transaksi-barang-masuk-'.$id.'.pdf');
}

}
