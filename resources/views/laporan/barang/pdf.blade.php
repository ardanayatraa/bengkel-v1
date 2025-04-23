<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Barang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f3f4f6;
        }

        h2 {
            text-align: center;
            margin: 0;
        }

        .periode {
            text-align: center;
            font-size: 12px;
            margin-top: 4px;
        }

        .total {
            margin-top: 10px;
            text-align: right;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @php
        $chunks = $transaksis->chunk(25); // Ganti jumlah per halaman kalau mau
        $totalHarga = $transaksis->sum('total_harga');
    @endphp

    @foreach ($chunks as $index => $chunk)
        <h2>Laporan Transaksi Barang</h2>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            — Halaman {{ $index + 1 }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Konsumen</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                    <th>Jumlah Point</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $trx)
                    <tr>
                        <td>{{ $trx->id_transaksi }}</td>
                        <td>{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                        <td>{{ $trx->barang->nama_barang ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $trx->metode_pembayaran ?? '-' }}</td>
                        <td>{{ $trx->jumlah_point ?? 0 }}</td>
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
