<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan {{ $start }} – {{ $end }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin: 8px 0 4px;
            font-size: 14px;
        }

        .periode,
        .pencarian {
            text-align: center;
            font-size: 11px;
            margin-bottom: 8px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
            margin-bottom: 4px;
        }

        table.data th,
        table.data td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 11px;
            text-align: left;
        }

        table.data th {
            background: #f3f4f6;
        }

        table.data tfoot td {
            background: #f9fafb;
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

        .kop-table td {
            vertical-align: middle;
            padding: 0;
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

    {{-- Kop tanpa border --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if ($logoData)
                    <img src="data:{{ $logoMime }};base64,{{ $logoData }}" alt="Logo"
                        style="height:60px; display:block;">
                @endif
            </td>
            <td class="kop-info">
                <div class="store-name">Ari Shockbreaker Motor</div>
                <div class="store-address">
                    Jl. Mahendradata, Bitera, Kec. Gianyar,<br>
                    Kab. Gianyar, Bali 80515
                </div>
            </td>
        </tr>
    </table>

    @foreach ($chunks as $i => $chunk)
        <h2>Laporan Penjualan</h2>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
            – {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            &mdash; Halaman {{ $i + 1 }}/{{ $chunks->count() }}
        </div>
        @if ($search)
            <div class="pencarian">Pencarian: “{{ $search }}”</div>
        @endif

        <table class="data">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Konsumen</th>
                    <th>Produk/Jasa</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                    <th>Metode</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $trx)
                    @php
                        $barangs = $trx->barangModels();
                        $jasas = $trx->jasaModels();
                        $type =
                            $barangs->isNotEmpty() && $jasas->isNotEmpty()
                                ? 'Barang & Jasa'
                                : ($barangs->isNotEmpty()
                                    ? 'Barang'
                                    : ($jasas->isNotEmpty()
                                        ? 'Jasa'
                                        : '-'));
                    @endphp
                    <tr>
                        <td>{{ $trx->id_transaksi }}</td>
                        <td>{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                        <td>
                            @foreach ($barangs as $b)
                                {{ $b->nama_barang }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                            @if ($barangs->isNotEmpty() && $jasas->isNotEmpty())
                                ,
                            @endif
                            @foreach ($jasas as $j)
                                {{ $j->nama_jasa }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $type }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($trx->metode_pembayaran) }}</td>
                    </tr>
                @endforeach
            </tbody>
            @if ($i + 1 === $chunks->count())
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align:right;">Total Terfilter:</td>
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
