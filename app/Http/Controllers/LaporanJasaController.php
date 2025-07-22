<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Teknisi;
use App\Models\Jasa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanJasaController extends Controller
{
    public function index(Request $request)
    {
        $user      = auth()->user();
        $isAdmin   = $user->level === 'admin';

        $start      = $request->input('start_date');
        $end        = $request->input('end_date');
        $search     = $request->input('search');
        $kasirId    = $request->input('kasir_id');
        $teknisiId  = $request->input('teknisi_id');

        // Dropdown data
        $kasirs    = $isAdmin ? User::where('level','kasir')->orderBy('nama_user')->get() : collect();
        $teknisis  = Teknisi::orderBy('nama_teknisi')->get();

        // Base query: transaksi jasa dengan kondisi yang lebih fleksibel
        $base = Transaksi::with(['konsumen','kasir','teknisi'])
            ->where(function($query) {
                $query->whereNotNull('id_jasa')
                      ->where('id_jasa', '!=', '')
                      ->where('id_jasa', '!=', '[]')
                      ->where('id_jasa', '!=', 'null')
                      ->where('id_jasa', '!=', '[null]');
            });

        // Debug: Cek apakah ada data sama sekali
        $debugCount = Transaksi::count();
        $debugWithJasa = Transaksi::whereNotNull('id_jasa')
                                 ->where('id_jasa', '!=', '')
                                 ->where('id_jasa', '!=', '[]')
                                 ->count();

        // Non-admin see only their own transactions
        if (! $isAdmin) {
            $base->where('id_user', $user->id_user);
        }

        // Total tanpa filter - hitung dengan cara yang lebih aman
        $totalAll = 0;
        $allTransaksis = $base->get();
        foreach($allTransaksis as $trx) {
            $jasaIds = $this->parseJasaIds($trx->id_jasa);
            if (!empty($jasaIds)) {
                $jasas = Jasa::whereIn('id_jasa', $jasaIds)->get();
                $totalAll += $jasas->sum('harga_jasa');
            }
        }

        // Terapkan filter
        $filtered = clone $base;
        if ($start)      $filtered->whereDate('tanggal_transaksi','>=',$start);
        if ($end)        $filtered->whereDate('tanggal_transaksi','<=',$end);
        if ($search)     $filtered->whereHas('konsumen', function($q) use ($search) {
                              $q->where('nama_konsumen','like',"%{$search}%")
                                ->orWhere('no_kendaraan','like',"%{$search}%");
                         });
        if ($isAdmin && $kasirId)   $filtered->where('id_user',    $kasirId);
        if ($teknisiId)             $filtered->where('id_teknisi', $teknisiId);

        // Total setelah filter
        $totalFiltered = 0;
        $filteredTransaksis = $filtered->get();
        foreach($filteredTransaksis as $trx) {
            $jasaIds = $this->parseJasaIds($trx->id_jasa);
            if (!empty($jasaIds)) {
                $jasas = Jasa::whereIn('id_jasa', $jasaIds)->get();
                $totalFiltered += $jasas->sum('harga_jasa');
            }
        }

        // Paginate & preserve filters
        $transaksis = $filtered
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search','kasirId','teknisiId'));

        // Tambahkan data jasa ke setiap transaksi untuk ditampilkan
        $transaksis->getCollection()->transform(function ($transaksi) {
            $jasaIds = $this->parseJasaIds($transaksi->id_jasa);
            $transaksi->jasas = !empty($jasaIds) ? Jasa::whereIn('id_jasa', $jasaIds)->get() : collect();
            return $transaksi;
        });

        return view('laporan.jasa.index', compact(
            'transaksis','start','end','search',
            'kasirs','teknisis','kasirId','teknisiId',
            'totalAll','totalFiltered','isAdmin',
            'debugCount','debugWithJasa' // Tambahan untuk debug
        ));
    }

    public function exportPdf(Request $request)
    {
        $user      = auth()->user();
        $isAdmin   = $user->level === 'admin';

        $start      = $request->input('start_date');
        $end        = $request->input('end_date');
        $search     = $request->input('search');
        $kasirId    = $request->input('kasir_id');
        $teknisiId  = $request->input('teknisi_id');

        $base = Transaksi::with(['konsumen','kasir','teknisi'])
            ->where(function($query) {
                $query->whereNotNull('id_jasa')
                      ->where('id_jasa', '!=', '')
                      ->where('id_jasa', '!=', '[]')
                      ->where('id_jasa', '!=', 'null')
                      ->where('id_jasa', '!=', '[null]');
            });

        if (! $isAdmin) {
            $base->where('id_user', $user->id_user);
        }
        if ($start)      $base->whereDate('tanggal_transaksi','>=',$start);
        if ($end)        $base->whereDate('tanggal_transaksi','<=',$end);
        if ($search)     $base->whereHas('konsumen', function($q) use ($search) {
                              $q->where('nama_konsumen','like',"%{$search}%")
                                ->orWhere('no_kendaraan','like',"%{$search}%");
                         });
        if ($isAdmin && $kasirId)   $base->where('id_user',    $kasirId);
        if ($teknisiId)             $base->where('id_teknisi', $teknisiId);

        $transaksis = $base->orderByDesc('tanggal_transaksi')->get();

        // Tambahkan data jasa ke setiap transaksi
        $transaksis->transform(function ($transaksi) {
            $jasaIds = $this->parseJasaIds($transaksi->id_jasa);
            $transaksi->jasas = !empty($jasaIds) ? Jasa::whereIn('id_jasa', $jasaIds)->get() : collect();
            return $transaksi;
        });

        // Hitung total
        $totalFiltered = 0;
        foreach($transaksis as $trx) {
            $totalFiltered += $trx->jasas->sum('harga_jasa');
        }

        $pdf = Pdf::loadView('laporan.jasa.pdf', compact(
            'transaksis','start','end','search',
            'kasirId','teknisiId','totalFiltered','isAdmin'
        ))
        ->setPaper('a4','landscape');

        return $pdf->stream("laporan-transaksi-jasa.pdf");
    }

    /**
     * Debug method untuk melihat data
     */
    public function debug()
    {
        $transaksis = Transaksi::with(['konsumen','kasir','teknisi'])->limit(5)->get();

        echo "<h3>Debug Data Transaksi:</h3>";
        echo "Total Transaksi: " . Transaksi::count() . "<br><br>";

        foreach($transaksis as $trx) {
            echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
            echo "<strong>ID:</strong> {$trx->id_transaksi}<br>";
            echo "<strong>id_jasa raw:</strong> " . ($trx->id_jasa ?? 'NULL') . "<br>";
            echo "<strong>id_jasa type:</strong> " . gettype($trx->id_jasa) . "<br>";

            $jasaIds = $this->parseJasaIds($trx->id_jasa);
            echo "<strong>Parsed IDs:</strong> " . json_encode($jasaIds) . "<br>";

            if (!empty($jasaIds)) {
                $jasas = Jasa::whereIn('id_jasa', $jasaIds)->get();
                echo "<strong>Jasa found:</strong> " . $jasas->count() . "<br>";
                foreach($jasas as $jasa) {
                    echo "&nbsp;&nbsp;- {$jasa->nama_jasa}: Rp" . number_format($jasa->harga_jasa) . "<br>";
                }
            } else {
                echo "<strong>No jasa IDs found</strong><br>";
            }
            echo "</div>";
        }
    }

    /**
     * Helper method untuk parsing ID jasa dari JSON
     */
    private function parseJasaIds($idJasa)
    {
        if (empty($idJasa) || $idJasa === 'null' || $idJasa === '[]') {
            return [];
        }

        // Jika sudah array
        if (is_array($idJasa)) {
            return array_filter($idJasa, function($id) {
                return !is_null($id) && $id !== '' && $id !== 'null';
            });
        }

        // Jika string JSON
        if (is_string($idJasa)) {
            $decoded = json_decode($idJasa, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_filter($decoded, function($id) {
                    return !is_null($id) && $id !== '' && $id !== 'null';
                });
            }
        }

        return [];
    }
}
