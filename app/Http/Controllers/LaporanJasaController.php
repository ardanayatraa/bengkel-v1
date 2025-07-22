<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Teknisi;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Base query: transaksi jasa
        $base = Transaksi::with(['konsumen','kasir','teknisi'])
            ->whereJsonLength('id_jasa','>',0);

        // Non-admin see only their own transactions
        if (! $isAdmin) {
            $base->where('id_user', $user->id_user);
        }

        // Total tanpa filter
        $totalAll = $base->get()
            ->sum(fn($trx) => $trx->jasaModels()->sum('harga_jasa'));

        // Terapkan filter
        $filtered = clone $base;
        if ($start)      $filtered->whereDate('tanggal_transaksi','>=',$start);
        if ($end)        $filtered->whereDate('tanggal_transaksi','<=',$end);
        if ($search)     $filtered->whereHas('konsumen', fn($q)=>
                              $q->where('nama_konsumen','like',"%{$search}%")
                                ->orWhere('no_kendaraan','like',"%{$search}%"));
        if ($isAdmin && $kasirId)   $filtered->where('id_user',    $kasirId);
        if ($teknisiId)             $filtered->where('id_teknisi', $teknisiId);

        // Total setelah filter
        $totalFiltered = $filtered->get()
            ->sum(fn($trx) => $trx->jasaModels()->sum('harga_jasa'));

        // Paginate & preserve filters
        $transaksis = $filtered
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->appends(compact('start','end','search','kasirId','teknisiId'));

        return view('laporan.jasa.index', compact(
            'transaksis','start','end','search',
            'kasirs','teknisis','kasirId','teknisiId',
            'totalAll','totalFiltered','isAdmin'
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
            ->whereJsonLength('id_jasa','>',0);

        if (! $isAdmin) {
            $base->where('id_user', $user->id_user);
        }
        if ($start)      $base->whereDate('tanggal_transaksi','>=',$start);
        if ($end)        $base->whereDate('tanggal_transaksi','<=',$end);
        if ($search)     $base->whereHas('konsumen', fn($q)=>
                              $q->where('nama_konsumen','like',"%{$search}%")
                                ->orWhere('no_kendaraan','like',"%{$search}%"));
        if ($isAdmin && $kasirId)   $base->where('id_user',    $kasirId);
        if ($teknisiId)             $base->where('id_teknisi', $teknisiId);

        $transaksis    = $base->orderByDesc('tanggal_transaksi')->get();

        dd($transaksis);
        $totalFiltered = $transaksis->sum(fn($trx) => $trx->jasaModels()->sum('harga_jasa'));

        $pdf = Pdf::loadView('laporan.jasa.pdf', compact(
            'transaksis','start','end','search',
            'kasirId','teknisiId','totalFiltered','isAdmin'
        ))
        ->setPaper('a4','landscape');

        return $pdf->stream("laporan-transaksi-jasa.pdf");
    }
}
