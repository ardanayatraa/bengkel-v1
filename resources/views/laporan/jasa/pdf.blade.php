<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Jasa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        h2 {
            text-align: center;
            margin-bottom: 0;
        }

        .tanggal {
            font-size: 11px;
            text-align: center;
            margin-top: 4px;
        }

        .page-break {
            page-break-after: always;
        }

        .total {
            margin-top: 20px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>

    @php
        $chunks     = $transaksis->chunk(25); // maksimal 25 per halaman
        $totalHarga = $transaksis->sum('total_harga');
    @endphp

    @foreach ($chunks as $index => $chunk)
        <h2>Laporan Transaksi Jasa</h2>
        <div class="tanggal">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
            – {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            &mdash; Halaman {{ $index + 1 }}
            @if(!empty($search))
                <br><em>Pencarian: “{{ $search }}”</em>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>No Polisi</th>
                    <th>Daftar Jasa</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $trx)
                    <tr>
                        <td>{{ $trx->id_transaksi }}</td>
                        <td>{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                        <td>{{ $trx->konsumen->no_kendaraan ?? '-' }}</td>
                        <td>
                            @foreach ($trx->jasaModels() as $jasa)
                                {{ $jasa->nama_jasa }}@if (!$loop->last), @endif
                            @endforeach
                        </td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $trx->metode_pembayaran ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($loop->last)
            <div class="total">
                Total Harga: Rp {{ number_format($totalHarga, 0, ',', '.') }}
            </div>
        @else
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>
