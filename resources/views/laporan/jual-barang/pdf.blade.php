<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Barang {{ $start }}–{{ $end }}</title>
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
            vertical-align: top;
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

        .kop {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .kop-logo {
            width: 70px;
        }

        .kop-info {
            text-align: right;
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
        $logo = public_path('assets/img/logo.png');
        $logoData = file_exists($logo) ? base64_encode(file_get_contents($logo)) : null;
        $mime = $logoData ? mime_content_type($logo) : '';
        $chunks = $transaksis->chunk(20);
    @endphp

    <table class="kop">
        <tr>
            <td class="kop-logo">
                @if ($logoData)
                    <img src="data:{{ $mime }};base64,{{ $logoData }}" style="height:60px;">
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
        <h2>Laporan Penjualan Barang</h2>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
            – {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            — Halaman {{ $i + 1 }}/{{ $chunks->count() }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kasir</th>
                    <th>Pelanggan</th>
                    <th>No Polisi</th>

                    <th>Barang (qty & subtotal)</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($page as $trx)
                    <tr>
                        <td>{{ $trx->id_transaksi }}</td>
                        <td>{{ $trx->kasir->nama_user ?? '-' }}</td>
                        <td>{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                        <td>{{ $trx->konsumen->no_kendaraan ?? '-' }}</td>

                        <td>
                            @foreach ($trx->barangWithQty() as $b)
                                {{ $b->model->nama_barang }}×{{ $b->qty }}
                                = Rp {{ number_format($b->subtotal, 0, ',', '.') }}<br>
                            @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($trx->calculated_total, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($trx->metode_pembayaran) }}</td>
                    </tr>
                @endforeach
            </tbody>
            @if ($i + 1 === $chunks->count())
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align:right;">Total (terfilter):</td>
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
