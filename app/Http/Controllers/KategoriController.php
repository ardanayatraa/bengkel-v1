<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Kategori::create($validatedData);
        return redirect()->route('kategori.index')->with('success', 'Kategori created successfully.');
    }

    public function edit(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($validatedData);
        return redirect()->route('kategori.index')->with('success', 'Kategori updated successfully.');
    }

    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori deleted successfully.');
    }
}
