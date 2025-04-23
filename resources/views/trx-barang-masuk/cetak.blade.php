<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Barang Masuk</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .header p {
            margin: 0;
            font-size: 13px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info table {
            width: 100%;
            font-size: 12px;
        }

        .info td {
            padding: 4px 0;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.items th,
        table.items td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        table.items th {
            background-color: #f2f2f2;
        }

        .summary {
            margin-top: 20px;
            font-weight: bold;
        }

        .signature {
            margin-top: 60px;
            text-align: right;
        }

        .signature p {
            margin-bottom: 60px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h1>Nota Barang Masuk</h1>
        <p><strong>Ari Shockbreaker</strong></p>
        <p>Jl. Contoh Alamat No. 123, Denpasar</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <!-- Informasi Transaksi -->
    <div class="info">
        <table>
            <tr>
                <td><strong>ID Transaksi:</strong> {{ $trx->id_trx_barang_masuk }}</td>
                <td><strong>Tanggal Masuk:</strong>
                    {{ \Carbon\Carbon::parse($trx->tanggal_masuk)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Supplier:</strong> {{ $trx->barang->supplier->nama_supplier ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>No Telp:</strong> {{ $trx->barang->supplier->no_telp ?? '-' }}</td>
                <td><strong>Alamat:</strong> {{ $trx->barang->supplier->alamat ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Barang -->
    <table class="items">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $trx->barang->nama_barang }}</td>
                <td>Rp {{ number_format($trx->barang->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($trx->barang->harga_jual, 0, ',', '.') }}</td>
                <td>{{ $trx->jumlah }}</td>
                <td>Rp {{ number_format($trx->jumlah * $trx->barang->harga_beli, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Total -->
    <div class="summary">
        <p>Total Harga: Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Denpasar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>__________________________</p>
        <p style="font-size: 11px;">Petugas</p>
    </div>

</body>

</html>
