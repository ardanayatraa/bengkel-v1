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
            vertical-align: top;
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

        .badge {
            display: inline-block;
            padding: 2px 6px;
            margin: 1px;
            font-size: 10px;
            border-radius: 4px;
            background-color: #bfdbfe;
            color: #1e3a8a;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
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
        $chunks = $transaksis->chunk(25);
        $totalHarga = $transaksis->sum('total_harga');
    @endphp

    @foreach ($chunks as $page => $chunk)
        <h2>Laporan Transaksi Barang</h2>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
            - {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
            — Halaman {{ $page + 1 }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Konsumen</th>
                    <th>Daftar Barang</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                    <th>Metode</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $trx)
                    <tr>
                        <td>{{ $trx->id_transaksi }}</td>
                        <td>{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                        <td>
                            @forelse($trx->barangModels() as $b)
                                <span class="badge">{{ $b->nama_barang }}</span>
                            @empty
                                -
                            @endforelse
                        </td>
                        <td>{{ $trx->tanggal_transaksi->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>{{ $trx->metode_pembayaran }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($loop->last)
            {{-- Total transaksi --}}
            <div class="total">
                Total Transaksi: Rp {{ number_format($totalHarga, 0, ',', '.') }}
            </div>

            {{-- Ringkasan Stok --}}
            <div class="section-title">Ringkasan Stok Barang</div>
            <table>
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Stok Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockSummary as $s)
                        <tr>
                            <td>{{ $s->barang->nama_barang }}</td>
                            <td>{{ $s->masuk }}</td>
                            <td>{{ $s->keluar }}</td>
                            <td>{{ $s->stok_akhir }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>
