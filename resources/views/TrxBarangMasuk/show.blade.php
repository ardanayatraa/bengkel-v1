<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center border pr-4 justify-between">
            <h2 class="text-lg font-semibold text-red-800 py-4 pl-6 pr-8 flex items-center gap-2">
                Detail Transaksi Barang Masuk
            </h2>
        </div>
        <div class="border p-4 md:p-6">
            <!-- Transaction Details -->
            <div class="mb-4">
                <p><strong>Id Transaksi Brg Masuk:</strong> {{ $trxBarangMasuk->id_trx_barang_masuk }}</p>
                <p><strong>Tanggal Masuk:</strong>
                    {{ \Carbon\Carbon::parse($trxBarangMasuk->tanggal_masuk)->translatedFormat('l, d/m/Y') }}</p>
                <p><strong>Nama Supplier:</strong> {{ $trxBarangMasuk->nama_supplier }}</p>
            </div>

            <!-- Items Table -->
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Nama Barang</th>
                        <th class="border border-gray-300 px-4 py-2">Jumlah</th>
                        <th class="border border-gray-300 px-4 py-2">Harga</th>
                        <th class="border border-gray-300 px-4 py-2">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trxBarangMasuk->barang as $barang)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $barang->nama_barang }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $barang->jumlah }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ number_format($barang->jumlah * $barang->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold">
                        <td colspan="3" class="border border-gray-300 px-4 py-2 text-right">Total</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ number_format($trxBarangMasuk->barang->sum(fn($barang) => $barang->jumlah * $barang->harga), 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Buttons -->
            <div class="mt-4 flex justify-between">
                <a href="{{ route('trx-barang-masuk.index') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded">Kembali</a>
                <button onclick="window.print()" class="bg-orange-500 text-white px-4 py-2 rounded">Cetak</button>
            </div>
        </div>
    </div>
</x-app-layout>
