<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Invoice #{{ $transaksi->id_transaksi }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            line-height: 1.2;
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
            background: #fff;
            color: #000;
        }

        .receipt-container {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }

        .store-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .store-address {
            font-size: 9px;
            margin-bottom: 4px;
        }

        .invoice-info {
            font-size: 10px;
            margin-bottom: 3px;
        }

        .customer-info {
            margin-bottom: 8px;
            font-size: 10px;
        }

        .customer-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .items-section {
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 2px 0;
            margin-bottom: 4px;
        }

        .item-row {
            margin-bottom: 3px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
        }

        .qty-price {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .total-section {
            margin-bottom: 8px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .total-final {
            font-weight: bold;
            font-size: 11px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 3px 0;
            margin: 5px 0;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 10px;
        }

        .thank-you {
            margin-bottom: 3px;
        }

        .date-time {
            margin-bottom: 2px;
        }

        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 2mm;
            }

            .receipt-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="store-name">Ari Shockbreaker</div>
            <div class="store-address">
                Jl. Mahendradata, Bitera, Kec. Gianyar, Kab. Gianyar, Bali 80515
            </div>
            <div class="invoice-info">INVOICE #{{ $transaksi->id_transaksi }}</div>
            <div class="invoice-info">
                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <div class="customer-row">
                <span>Konsumen:</span>
                <span>{{ $transaksi->konsumen->nama_konsumen }}</span>
            </div>
            <div class="customer-row">
                <span>Pembayaran:</span>
                <span>{{ ucfirst($transaksi->metode_pembayaran) }}</span>
            </div>
        </div>

        <!-- Items Section -->
        @if ($barangs->count())
            <div class="items-section">
                <div class="section-title">DETAIL BARANG</div>
                @foreach ($barangs as $item)
                    <div class="item-row">
                        <div class="item-name">{{ $item->model->nama_barang }}</div>
                        <div class="item-details">
                            <div class="qty-price">
                                <span>{{ $item->qty }} x
                                    {{ number_format($item->model->harga_jual, 0, ',', '.') }}</span>
                                <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Services Section -->
        @if ($jasas->count())
            <div class="items-section">
                <div class="section-title">DETAIL JASA</div>
                @foreach ($jasas as $jasa)
                    <div class="item-row">
                        <div class="item-name">{{ $jasa->nama_jasa }}</div>
                        <div class="item-details">
                            <div class="qty-price">
                                <span>1 x {{ number_format($jasa->harga_jasa, 0, ',', '.') }}</span>
                                <span>{{ number_format($jasa->harga_jasa, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="separator"></div>

        <!-- Totals -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>

            @if ($diskon > 0)
                <div class="total-row">
                    <span>Diskon :</span>
                    <span>- Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="total-row total-final">
                <span>TOTAL BAYAR:</span>
                <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>

            <div class="total-row">
                <span>Uang Diterima:</span>
                <span>Rp {{ number_format($transaksi->uang_diterima, 0, ',', '.') }}</span>
            </div>

            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
            </div>

            @if (isset($sisaPoint))
                <div class="total-row">
                    <span>Sisa Poin:</span>
                    <span>{{ $sisaPoint }} poin</span>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">TERIMA KASIH</div>
            <div class="thank-you">ATAS KUNJUNGAN ANDA</div>
            <div class="date-time">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>
</body>

</html>
