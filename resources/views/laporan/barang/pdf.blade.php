<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ringkasan Stok Barang {{ $start }}–{{ $end }}</title>
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

        .periode {
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
        }

        th {
            background: #f3f4f6;
        }

        tfoot td {
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
        $chunks = $stockSummary->chunk(25);
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
        <h2>Ringkasan Stok Barang</h2>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
            – {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            — Halaman {{ $i + 1 }}/{{ $chunks->count() }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Barang</th>

                    <th>Stok Awal</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Stok Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($page as $s)
                    <tr>
                        <td>{{ $s->barang->nama_barang }}</td>

                        <td>{{ $s->stok_awal }}</td>
                        <td>{{ $s->masuk }}</td>
                        <td>{{ $s->keluar }}</td>
                        <td>{{ $s->stok_akhir }}</td>
                    </tr>
                @endforeach
            </tbody>
            @if ($i + 1 === $chunks->count())
                <tfoot>
                    <tr>
                        <td colspan="1" style="text-align:right;">TOTAL:</td>
                        <td>{{ $totalStokAwal }}</td>
                        <td>{{ $totalMasuk }}</td>
                        <td>{{ $totalKeluar }}</td>
                        <td>{{ $totalStokAkhir }}</td>
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
