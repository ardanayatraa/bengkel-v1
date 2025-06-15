<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Barang;
use App\Models\TrxBarangMasuk;
use Illuminate\Http\Request;

class TrxBarangMasukController extends Controller
{
    public function index()
    {
        $trxBarangMasuk = TrxBarangMasuk::with('barang.supplier')->get();
        return view('trx-barang-masuk.index', compact('trxBarangMasuk'));
    }

    public function create()
    {
        $barangs = Barang::with('supplier')->get();
        return view('trx-barang-masuk.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk'       => 'required|date',
            'id_barang.*'         => 'required|integer|exists:barangs,id_barang',
            'jumlah.*'            => 'required|integer|min:1',
            'total_harga.*'       => 'required|numeric|min:0',
        ]);

        $tanggal = $request->input('tanggal_masuk');

        foreach ($request->input('id_barang') as $i => $barangId) {
            $qty  = $request->input('jumlah')[$i];
            $trx  = TrxBarangMasuk::create([
                'id_barang'     => $barangId,
                'tanggal_masuk' => $tanggal,
                'jumlah'        => $qty,
                'total_harga'   => $request->input('total_harga')[$i],
            ]);

            // Update stok manual
            $barang = Barang::findOrFail($barangId);
            $barang->stok = $barang->stok + $qty;
            $barang->save();
        }

        return redirect()
            ->route('trx-barang-masuk.index')
            ->with('success', 'Transaksi barang masuk berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $trx     = TrxBarangMasuk::findOrFail($id);
        $barangs = Barang::with('supplier')->get();
        return view('trx-barang-masuk.edit', compact('trx', 'barangs'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'id_barang'     => 'required|integer|exists:barangs,id_barang',
            'jumlah'        => 'required|integer|min:1',
            'total_harga'   => 'required|numeric|min:0',
        ]);

        $trx       = TrxBarangMasuk::findOrFail($id);
        $oldQty    = $trx->jumlah;
        $oldBarang = $trx->id_barang;

        $newBarang = $request->input('id_barang');
        $newQty    = $request->input('jumlah');

        // Kurangi stok lama
        $barangLama = Barang::findOrFail($oldBarang);
        $barangLama->stok = $barangLama->stok - $oldQty;
        $barangLama->save();

        // Update transaksi
        $trx->update([
            'tanggal_masuk' => $request->input('tanggal_masuk'),
            'id_barang'     => $newBarang,
            'jumlah'        => $newQty,
            'total_harga'   => $request->input('total_harga'),
        ]);

        // Tambah stok baru
        $barangBaru = Barang::findOrFail($newBarang);
        $barangBaru->stok = $barangBaru->stok + $newQty;
        $barangBaru->save();

        return redirect()
            ->route('trx-barang-masuk.index')
            ->with('success', 'Transaksi barang masuk berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $trx    = TrxBarangMasuk::findOrFail($id);
        $qty    = $trx->jumlah;
        $barang = $trx->id_barang;

        // Kurangi stok
        $b = Barang::findOrFail($barang);
        $b->stok = $b->stok - $qty;
        $b->save();

        $trx->delete();

        return redirect()
            ->route('trx-barang-masuk.index')
            ->with('success', 'Transaksi barang masuk berhasil dihapus.');
    }

    public function show(string $id)
    {
        $trx = TrxBarangMasuk::with('barang.supplier')->findOrFail($id);
        return view('trx-barang-masuk.show', compact('trx'));
    }

    public function cetak(string $id)
    {
        $trx = TrxBarangMasuk::with('barang.supplier')->findOrFail($id);
        $pdf = Pdf::loadView('trx-barang-masuk.cetak', compact('trx'))
                  ->setPaper('A4');
        return $pdf->stream("transaksi-barang-masuk-{$id}.pdf");
    }
}
