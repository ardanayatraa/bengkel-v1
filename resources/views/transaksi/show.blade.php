{{-- resources/views/transaksi/show.blade.php --}}
<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold">Detail Transaksi #{{ $transaksi->id_transaksi }}</h2>
            <a href="{{ route('transaksi.print', $transaksi->id_transaksi) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Print Nota
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div><strong>Konsumen:</strong> {{ $transaksi->konsumen->nama_konsumen }}</div>
                <div><strong>Teknisi:</strong> {{ $transaksi->teknisi?->nama_teknisi ?? '-' }}</div>
                <div><strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</div>
                <div><strong>Metode Bayar:</strong> {{ ucfirst($transaksi->metode_pembayaran) }}</div>
                <div><strong>Status:</strong> {{ ucfirst($transaksi->status_service) }}</div>
                <div><strong>Subtotal:</strong> Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                <div><strong>Diskon:</strong> Rp {{ number_format($diskon, 0, ',', '.') }}</div>
                <div><strong>Total Harga:</strong> Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                <div><strong>Uang Diterima:</strong> Rp {{ number_format($transaksi->uang_diterima, 0, ',', '.') }}
                </div>
                <div><strong>Kembalian:</strong> Rp {{ number_format($kembalian, 0, ',', '.') }}</div>
                <div><strong>Sisa Poin:</strong> {{ $sisaPoint }} poin</div>
            </div>

            @if ($barangs->count())
                <div class="mb-6">
                    <h3 class="font-medium mb-2">Detail Barang</h3>
                    <table class="w-full table-auto border-collapse mb-4">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2">Nama Barang</th>
                                <th class="border px-4 py-2 text-center">Qty</th>
                                <th class="border px-4 py-2 text-right">Harga</th>
                                <th class="border px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item->model->nama_barang }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $item->qty }}</td>
                                    <td class="border px-4 py-2 text-right">
                                        Rp {{ number_format($item->model->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td class="border px-4 py-2 text-right">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($jasas->count())
                <div class="mb-6">
                    <h3 class="font-medium mb-2">Detail Jasa</h3>
                    <table class="w-full table-auto border-collapse mb-4">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border px-4 py-2">Nama Jasa</th>
                                <th class="border px-4 py-2 text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jasas as $jasa)
                                <tr>
                                    <td class="border px-4 py-2">{{ $jasa->nama_jasa }}</td>
                                    <td class="border px-4 py-2 text-right">
                                        Rp {{ number_format($jasa->harga_jasa, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="text-center">
                <a href="{{ route('transaksi.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
