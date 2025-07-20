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
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'jumlah_point' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'email' => 'nullable|email',
            'kode_referral' => 'nullable|string|max:10|unique:konsumens,kode_referral',
        ]);

        // Set default values
        $validatedData['jumlah_point'] = $validatedData['jumlah_point'] ?? 0;
        $validatedData['keterangan'] = $validatedData['keterangan'] ?? '';

        // Auto generate kode referral jika konsumen adalah member
        if (strtolower($validatedData['keterangan'] ?? '') === 'member') {
            $validatedData['kode_referral'] = $this->generateKodeReferral($validatedData['nama_konsumen']);
        }

        $konsumen = Konsumen::create($validatedData);

        // Jika ada parameter return_to, redirect ke halaman tersebut
        if ($request->has('return_to')) {
            return redirect($request->get('return_to'))
                ->with('success', 'Konsumen berhasil ditambahkan!')
                ->with('new_konsumen_id', $konsumen->id_konsumen);
        }

        // Jika request AJAX, kembalikan JSON response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Konsumen berhasil ditambahkan',
                'konsumen' => $konsumen
            ]);
        }

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
            'no_kendaraan' => 'nullable|string|max:50',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'jumlah_point' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'email' => 'nullable|email',
            'kode_referral' => 'nullable|string|max:10|unique:konsumens,kode_referral,' . $id . ',id_konsumen',
        ]);

        $konsumen = Konsumen::findOrFail($id);

        // Set default values
        $validatedData['jumlah_point'] = $validatedData['jumlah_point'] ?? 0;
        $validatedData['keterangan'] = $validatedData['keterangan'] ?? '';

        // Jika berubah jadi member dan belum ada kode referral
        if (strtolower($validatedData['keterangan'] ?? '') === 'member' && empty($konsumen->kode_referral)) {
            $validatedData['kode_referral'] = $this->generateKodeReferral($validatedData['nama_konsumen']);
        }
        // Jika berubah dari member ke non-member, hapus kode referral
        elseif (strtolower($validatedData['keterangan'] ?? '') !== 'member' && !empty($konsumen->kode_referral)) {
            $validatedData['kode_referral'] = null;
        }

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

    /**
     * Generate unique kode referral
     */
    private function generateKodeReferral($namaKonsumen)
    {
        do {
            // Format: 3 huruf dari nama + 4 digit random
            $namaPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaKonsumen), 0, 3));
            if (strlen($namaPrefix) < 3) {
                $namaPrefix = str_pad($namaPrefix, 3, 'X');
            }

            $kode = $namaPrefix . sprintf('%04d', rand(1000, 9999));
        } while (Konsumen::where('kode_referral', $kode)->exists());

        return $kode;
    }

    /**
     * AJAX endpoint untuk generate ulang kode referral
     */
    public function regenerateReferral(Request $request, $id)
    {
        $konsumen = Konsumen::findOrFail($id);

        if (strtolower($konsumen->keterangan) !== 'member') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya member yang bisa memiliki kode referral'
            ]);
        }

        $newCode = $this->generateKodeReferral($konsumen->nama_konsumen);
        $konsumen->update(['kode_referral' => $newCode]);

        return response()->json([
            'success' => true,
            'new_code' => $newCode,
            'message' => 'Kode referral berhasil diperbarui'
        ]);
    }
}
