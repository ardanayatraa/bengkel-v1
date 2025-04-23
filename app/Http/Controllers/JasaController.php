<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\Request;

class JasaController extends Controller
{
    public function index()
    {
        $jasa = Jasa::all();
        return view('jasa.index', compact('jasa'));
    }

    public function create()
    {
        return view('jasa.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga_jasa' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        Jasa::create($validatedData);
        return redirect()->route('jasa.index')->with('success', 'Jasa created successfully.');
    }

    public function edit(string $id)
    {
        $jasa = Jasa::findOrFail($id);
        return view('jasa.edit', compact('jasa'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga_jasa' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $jasa = Jasa::findOrFail($id);
        $jasa->update($validatedData);
        return redirect()->route('jasa.index')->with('success', 'Jasa updated successfully.');
    }

    public function destroy(string $id)
    {
        $jasa = Jasa::findOrFail($id);
        $jasa->delete();
        return redirect()->route('jasa.index')->with('success', 'Jasa deleted successfully.');
    }
}
