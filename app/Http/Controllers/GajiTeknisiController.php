<?php

namespace App\Http\Controllers;

use App\Models\GajiTeknisi;
use App\Models\Teknisi;
use App\Models\Transaksi;
use App\Models\Jasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GajiTeknisiController extends Controller
{
    public function index()
    {
        $gajiTeknisis = GajiTeknisi::with(['teknisi', 'transaksi', 'jasa'])
            ->orderByDesc('tanggal_kerja')
            ->get();

        return view('gaji-teknisi.index', compact('gajiTeknisis'));
    }

    public function create()
    {
        $teknisis = Teknisi::all();
        $transaksis = Transaksi::whereNotNull('id_teknisi')->get();
        $jasas = Jasa::all();

        return view('gaji-teknisi.create', compact('teknisis', 'transaksis', 'jasas'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_teknisi' => 'required|exists:teknisis,id_teknisi',
            'id_transaksi' => 'required|exists:transaksis,id_transaksi',
            'id_jasa' => 'required|exists:jasas,id_jasa',
            'harga_jasa' => 'required|numeric|min:0',
            'persentase_gaji' => 'required|numeric|min:0|max:100',
            'tanggal_kerja' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Hitung jumlah gaji
        $jumlahGaji = ($validatedData['harga_jasa'] * $validatedData['persentase_gaji']) / 100;

        $validatedData['jumlah_gaji'] = $jumlahGaji;

        GajiTeknisi::create($validatedData);

        return redirect()->route('gaji-teknisi.index')
            ->with('success', 'Gaji teknisi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $gajiTeknisi = GajiTeknisi::with(['teknisi', 'transaksi', 'jasa'])->findOrFail($id);

        return view('gaji-teknisi.show', compact('gajiTeknisi'));
    }

    public function edit($id)
    {
        $gajiTeknisi = GajiTeknisi::findOrFail($id);
        $teknisis = Teknisi::all();
        $transaksis = Transaksi::whereNotNull('id_teknisi')->get();
        $jasas = Jasa::all();

        return view('gaji-teknisi.edit', compact('gajiTeknisi', 'teknisis', 'transaksis', 'jasas'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_teknisi' => 'required|exists:teknisis,id_teknisi',
            'id_transaksi' => 'required|exists:transaksis,id_transaksi',
            'id_jasa' => 'required|exists:jasas,id_jasa',
            'harga_jasa' => 'required|numeric|min:0',
            'persentase_gaji' => 'required|numeric|min:0|max:100',
            'tanggal_kerja' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Hitung jumlah gaji
        $jumlahGaji = ($validatedData['harga_jasa'] * $validatedData['persentase_gaji']) / 100;

        $validatedData['jumlah_gaji'] = $jumlahGaji;

        $gajiTeknisi = GajiTeknisi::findOrFail($id);
        $gajiTeknisi->update($validatedData);

        return redirect()->route('gaji-teknisi.index')
            ->with('success', 'Gaji teknisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gajiTeknisi = GajiTeknisi::findOrFail($id);
        $gajiTeknisi->delete();

        return redirect()->route('gaji-teknisi.index')
            ->with('success', 'Gaji teknisi berhasil dihapus.');
    }

    /**
     * Method untuk membayar gaji teknisi
     */
    public function bayarGaji($id)
    {
        $gajiTeknisi = GajiTeknisi::findOrFail($id);
        
        $gajiTeknisi->update([
            'status_pembayaran' => 'sudah_dibayar',
            'tanggal_pembayaran' => now()->toDateString(),
        ]);

        return redirect()->back()
            ->with('success', 'Gaji teknisi berhasil dibayar.');
    }

    /**
     * Method untuk membayar semua gaji teknisi yang belum dibayar
     */
    public function bayarSemuaGaji()
    {
        $gajiBelumDibayar = GajiTeknisi::where('status_pembayaran', 'belum_dibayar')->get();
        
        $count = 0;
        foreach ($gajiBelumDibayar as $gaji) {
            $gaji->update([
                'status_pembayaran' => 'sudah_dibayar',
                'tanggal_pembayaran' => now()->toDateString(),
            ]);
            $count++;
        }

        return redirect()->back()
            ->with('success', "Berhasil membayar {$count} gaji teknisi sekaligus.");
    }

    /**
     * Method untuk generate gaji otomatis dari transaksi
     */
    public function generateGajiOtomatis()
    {
        // Ambil transaksi yang memiliki teknisi dan jasa
        $transaksis = Transaksi::whereNotNull('id_teknisi')
            ->whereNotNull('id_jasa')
            ->get();

        $count = 0;

        foreach ($transaksis as $transaksi) {
            $teknisi = Teknisi::find($transaksi->id_teknisi);
            $jasas = $transaksi->jasaModels();

            foreach ($jasas as $jasa) {
                // Cek apakah sudah ada gaji untuk transaksi dan jasa ini
                $existingGaji = GajiTeknisi::where('id_transaksi', $transaksi->id_transaksi)
                    ->where('id_jasa', $jasa->id_jasa)
                    ->first();

                if (!$existingGaji) {
                    // Hitung gaji berdasarkan persentase teknisi
                    $jumlahGaji = ($jasa->harga_jasa * $teknisi->persentase_gaji) / 100;

                    GajiTeknisi::create([
                        'id_teknisi' => $teknisi->id_teknisi,
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_jasa' => $jasa->id_jasa,
                        'harga_jasa' => $jasa->harga_jasa,
                        'persentase_gaji' => $teknisi->persentase_gaji,
                        'jumlah_gaji' => $jumlahGaji,
                        'tanggal_kerja' => $transaksi->tanggal_transaksi,
                        'status_pembayaran' => 'belum_dibayar',
                    ]);

                    $count++;
                }
            }
        }

        return redirect()->back()
            ->with('success', "Berhasil generate {$count} gaji teknisi.");
    }

    /**
     * Method untuk laporan gaji teknisi
     */
    public function laporan(Request $request)
    {
        $query = GajiTeknisi::with(['teknisi', 'transaksi', 'jasa']);

        // Filter berdasarkan teknisi
        if ($request->filled('id_teknisi')) {
            $query->where('id_teknisi', $request->id_teknisi);
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal_kerja', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->where('tanggal_kerja', '<=', $request->tanggal_akhir);
        }

        $gajiTeknisis = $query->orderByDesc('tanggal_kerja')->get();
        $teknisis = Teknisi::all();

        // Hitung total
        $totalGaji = $gajiTeknisis->sum('jumlah_gaji');
        $totalBelumDibayar = $gajiTeknisis->where('status_pembayaran', 'belum_dibayar')->sum('jumlah_gaji');
        $totalSudahDibayar = $gajiTeknisis->where('status_pembayaran', 'sudah_dibayar')->sum('jumlah_gaji');

        return view('gaji-teknisi.laporan', compact(
            'gajiTeknisis', 
            'teknisis', 
            'totalGaji', 
            'totalBelumDibayar', 
            'totalSudahDibayar'
        ));
    }
}
