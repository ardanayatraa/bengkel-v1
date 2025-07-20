<?php

namespace App\Http\Controllers;

use App\Models\Teknisi;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function index()
    {
        $teknisis = Teknisi::orderBy('nama_teknisi')->paginate(10);
        return view('teknisi.index', compact('teknisis'));
    }

    public function create()
    {
        return view('teknisi.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_teknisi' => 'required|string|max:255',
            'kontak'       => 'nullable|string|max:255',
            'persentase_gaji' => 'required|numeric|min:0|max:100',
        ]);

        Teknisi::create($data);

        return redirect()->route('teknisi.index')
                         ->with('success', 'Teknisi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $teknisi = Teknisi::with(['gajiTeknisis.transaksi', 'gajiTeknisis.jasa'])->findOrFail($id);
        return view('teknisi.show', compact('teknisi'));
    }

    public function edit($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        return view('teknisi.edit', compact('teknisi'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_teknisi' => 'required|string|max:255',
            'kontak'       => 'nullable|string|max:255',
            'persentase_gaji' => 'required|numeric|min:0|max:100',
        ]);

        $teknisi = Teknisi::findOrFail($id);
        $teknisi->update($data);

        return redirect()->route('teknisi.index')
                         ->with('success', 'Teknisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        $teknisi->delete();

        return redirect()->route('teknisi.index')
                         ->with('success', 'Teknisi berhasil dihapus.');
    }
}
