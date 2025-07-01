{{-- resources/views/transaksi/print.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice Transaksi #{{ $transaksi->id_transaksi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            font-size: 16px;
            margin-bottom: 8px;
            border-bottom: 1px solid #666;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #666;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>INVOICE TRANSAKSI</h1>
        <p>No. #{{ $transaksi->id_transaksi }} &bull;
            {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}</p>
    </div>

    {{-- Info umum --}}
    <div class="section">
        <table>
            <tr>
                <th>Konsumen</th>
                <td>{{ $transaksi->konsumen->nama_konsumen }}</td>
                <th>Metode Bayar</th>
                <td>{{ ucfirst($transaksi->metode_pembayaran) }}</td>
            </tr>
        </table>
    </div>

    {{-- Tabel Barang --}}
@if ($barangs->count())
    <div class="section">
        <h2>Detail Barang</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th class="text-right">Harga Satuan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $barang->nama_barang }}</td>
                        <td class="text-right">{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif


    {{-- Tabel Jasa --}}
    @if ($jasas->count())
        <div class="section">
            <h2>Detail Jasa</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Jasa</th>
                        <th class="text-right">Harga (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jasas as $jasa)
                        <tr>
                            <td>{{ $jasa->nama_jasa }}</td>
                            <td class="text-right">{{ number_format($jasa->harga_jasa, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


    {{-- Ringkasan Total --}}
    <div class="section">
        <h2>Ringkasan Pembayaran</h2>
        <table>
            <tbody>
                @php
                $subtotalBarang = $barangs->sum('harga_jual');
                $subtotalJasa   = $jasas->sum('harga_jasa');
                $subtotal       = $subtotalBarang + $subtotalJasa;
                $diskon         = $subtotal - $transaksi->total_harga;
            @endphp


                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                @if ($diskon > 0)
                    <tr>
                        <td>Diskon Member</td>
                        <td class="text-right">- Rp {{ number_format($diskon, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td>Total Bayar</td>
                    <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
                @if ($transaksi->points->count())
                    <tr>
                        <td>Poin Diperoleh</td>
                        <td class="text-right">{{ $transaksi->points->sum('jumlah_point') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div style="text-align:center; font-size:10px; color:#777; margin-top:30px;">
        Terima kasih atas kepercayaan Anda.<br>
    </div>
</body>

</html>
