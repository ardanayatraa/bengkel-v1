<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Jasa {{ $start ?? 'Semua' }}–{{ $end ?? 'Semua' }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            font-size: 14px;
            margin: 8px 0;
        }

        .periode,
        .filter-info {
            text-align: center;
            font-size: 11px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 11px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        tfoot td {
            background: #f5f5f5;
            font-weight: bold;
            border-top: 2px solid #999;
        }

        .page-break {
            page-break-after: always;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .kop-logo {
            width: 70px;
        }

        .kop-info {
            text-align: right;
            padding-left: 8px;
            line-height: 1.2;
        }

        .store-name {
            font-size: 16px;
            font-weight: bold;
        }

        .store-address {
            font-size: 11px;
        }
    </style>
</head>

<body>
    @php
        $logoPath = public_path('assets/img/logo.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
        $logoMime = $logoData ? mime_content_type($logoPath) : '';
        $chunks = $transaksis->chunk(25);
    @endphp

    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if ($logoData)
                    <img src="data:{{ $logoMime }};base64,{{ $logoData }}" style="height:60px;">
                @endif
            </td>
            <td class="kop-info">
                <div class="store-name">Ari Shockbreaker Motor</div>
                <div class="store-address">
                    Jl. Mahendradata, Bitera, Gianyar,<br>Kab. Gianyar, Bali 80515
                </div>
            </td>
        </tr>
    </table>

    @foreach ($chunks as $i => $page)
        <h2>Laporan Transaksi Jasa</h2>
        <div class="periode">
            Periode:
            {{ $start ? \Carbon\Carbon::parse($start)->format('d/m/Y') : 'Semua' }}
            – {{ $end ? \Carbon\Carbon::parse($end)->format('d/m/Y') : 'Semua' }}
        </div>
        <div class="filter-info">
            @if ($search)
                Pencarian: “{{ $search }}” &middot;
            @endif
            @if ($isAdmin && $kasirId)
                Kasir: {{ \App\Models\User::find($kasirId)->nama_user }} &middot;
            @endif
            @if ($teknisiId)
                Teknisi: {{ \App\Models\Teknisi::find($teknisiId)->nama_teknisi }}
            @endif
            &mdash; Halaman {{ $i + 1 }}/{{ $chunks->count() }}
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kasir</th>
                    <th>Teknisi</th>
                    <th>Pelanggan</th>
                    <th>No Polisi</th>
                    <th>Daftar Jasa</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                    <th>Metode Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($page as $trx)
                    <tr>
                        <td>{{ $trx->id_transaksi }}</td>
                        <td>{{ $trx->kasir->nama_user ?? '-' }}</td>
                        <td>{{ $trx->teknisi->nama_teknisi ?? '-' }}</td>
                        <td>{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                        <td>{{ $trx->konsumen->no_kendaraan ?? '-' }}</td>
                        <td>
                            @foreach ($trx->jasaModels() as $j)
                                {{ $j->nama_jasa }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($trx->jasaModels()->sum('harga_jasa'), 0, ',', '.') }}</td>
                        <td>{{ ucfirst($trx->metode_pembayaran) }}</td>
                    </tr>
                @endforeach
            </tbody>
            @if ($i + 1 === $chunks->count())
                <tfoot>
                    <tr>
                        <td colspan="7" style="text-align:right;">Total (terfilter):</td>
                        <td>Rp {{ number_format($totalFiltered, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>

        @if ($i + 1 < $chunks->count())
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
