<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        // Livewire table akan handle tampilannya
        return view('barang.index');
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $barangs = Barang::all();
        return view('barang.create', compact('suppliers', 'barangs'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_supplier' => 'required|integer|exists:suppliers,id_supplier',
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
    {
        $barang = Barang::findOrFail($id);
        $suppliers = Supplier::all();
        return view('barang.edit', compact('barang', 'suppliers'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'id_supplier' => 'required|integer|exists:suppliers,id_supplier',
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
