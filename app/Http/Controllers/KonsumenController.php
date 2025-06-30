<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use Illuminate\Http\Request;

class KonsumenController extends Controller
{
    public function index()
    {
        $konsumens = Konsumen::all();
        return view('konsumen.index', compact('konsumens'));
    }

    public function create()
    {
        return view('konsumen.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_konsumen' => 'required|string|max:255',
            'no_kendaraan' => 'nullable|string|max:50',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jumlah_point' => 'nullable|integer',
            'keterangan' => 'nullable|string',
        ]);

        Konsumen::create($validatedData);
        return redirect()->route('konsumen.index')->with('success', 'Konsumen created successfully.');
    }

    public function edit(string $id)
    {
        $konsumen = Konsumen::findOrFail($id);
        return view('konsumen.edit', compact('konsumen'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_konsumen' => 'required|string|max:255',
            'no_kendaraan' => 'required|string|max:50',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jumlah_point' => 'nullable|integer',
            'keterangan' => 'nullable|string',
        ]);

        $konsumen = Konsumen::findOrFail($id);
        $konsumen->update($validatedData);
        return redirect()->route('konsumen.index')->with('success', 'Konsumen updated successfully.');
    }

    public function destroy(string $id)
    {
        $konsumen = Konsumen::findOrFail($id);
        $konsumen->delete();
        return redirect()->route('konsumen.index')->with('success', 'Konsumen deleted successfully.');
    }

    public function cetakKartu(\App\Models\Konsumen $konsumen)
{

    return view('konsumen.cetak-kartu', compact('konsumen'));
}

}
