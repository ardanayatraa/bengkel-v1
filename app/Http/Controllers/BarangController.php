<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $kategoris = Kategori::all();

        return view('barang.create', compact('suppliers', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_supplier' => 'required|integer',
            'id_kategori' => 'required|integer',
            'nama_barang' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        Barang::create($validatedData);
        return redirect()->route('barang.index')->with('success', 'Barang created successfully.');
    }

    public function edit(string $id)
    {   $suppliers = Supplier::all();
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang','suppliers', 'kategoris'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_supplier' => 'required|integer',
            'id_kategori' => 'required|integer',
            'nama_barang' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($validatedData);
        return redirect()->route('barang.index')->with('success', 'Barang updated successfully.');
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        return redirect()->route('barang.index');
    }
}
