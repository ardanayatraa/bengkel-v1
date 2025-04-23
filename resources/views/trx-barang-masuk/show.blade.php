<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center border pr-4 justify-between">
            <h2 class="text-lg font-semibold text-red-800 py-4 pl-6 pr-8 flex items-center gap-2">
                Detail Transaksi Barang Masuk
            </h2>
        </div>

        <div class="bg-white rounded-lg border shadow-sm p-6 space-y-6">
            <!-- Info Transaksi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-800 text-sm">
                <div class="space-y-1">
                    <p><strong>ID Transaksi:</strong> {{ $trx->id_trx_barang_masuk }}</p>
                    <p><strong>Tanggal Masuk:</strong>
                        {{ \Carbon\Carbon::parse($trx->tanggal_masuk)->translatedFormat('l, d M Y') }}</p>
                </div>
                <div class="space-y-1">
                    <p><strong>Total Harga:</strong> Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                    <p><strong>Jumlah Barang:</strong> {{ $trx->jumlah }}</p>
                </div>
            </div>

            <!-- Tabel Barang -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Nama Barang</th>
                            <th class="px-4 py-2 border">Harga Beli</th>
                            <th class="px-4 py-2 border">Harga Jual</th>
                            <th class="px-4 py-2 border">Jumlah</th>
                            <th class="px-4 py-2 border">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $trx->barang->nama_barang }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($trx->barang->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 border">Rp {{ number_format($trx->barang->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 border">{{ $trx->jumlah }}</td>
                            <td class="px-4 py-2 border">Rp
                                {{ number_format($trx->jumlah * $trx->barang->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Info Supplier -->
            @if ($trx->barang->supplier)
                <div class="border-t pt-4 text-sm text-gray-700">
                    <p><strong>Supplier:</strong> {{ $trx->barang->supplier->nama_supplier }}</p>
                    <p><strong>Telp:</strong> {{ $trx->barang->supplier->no_telp }} |
                        <strong>Alamat:</strong> {{ $trx->barang->supplier->alamat }}
                    </p>
                </div>
            @endif

            <!-- Tombol Aksi -->
            <div class="flex justify-between pt-6">
                <a href="{{ route('trx-barang-masuk.index') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                    ← Kembali
                </a>
                <a href="{{ route('trx-barang-masuk.cetak', $trx->id_trx_barang_masuk) }}" target="_blank"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                    Cetak PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
