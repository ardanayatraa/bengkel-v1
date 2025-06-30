<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ringkasan Stok Barang</title>
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
            padding: 8px;
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
    </style>
</head>

<body>

    <h2>Ringkasan Stok Barang</h2>
    <div class="periode">
        Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
        – {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
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
            @foreach ($stockSummary as $s)
                <tr>
                    <td>{{ $s->barang->nama_barang }}</td>
                    <td>{{ $s->stok_awal ?? $s->masuk - $s->keluar + $s->stok_akhir }}</td>
                    <td>{{ $s->masuk }}</td>
                    <td>{{ $s->keluar }}</td>
                    <td>{{ $s->stok_akhir }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
