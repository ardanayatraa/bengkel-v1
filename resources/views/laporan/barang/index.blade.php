<x-app-layout>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header: Search + Filter + Cetak -->
        <div class="flex flex-wrap justify-between gap-4 items-center mb-6">
            <form method="GET" action="{{ route('laporan.barang') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Konsumen..."
                    class="px-4 py-2 border rounded-md w-52 focus:ring-2 focus:ring-gray-200">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Search
                </button>
            </form>
            <form method="GET" action="{{ route('laporan.barang') }}" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="px-3 py-2 border rounded-md text-sm">
                <span class="text-gray-500">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="px-3 py-2 border rounded-md text-sm">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Filter
                </button>
            </form>
            <a href="{{ route('laporan.barang.pdf', request()->only('start_date', 'end_date', 'search')) }}"
                class="px-4 py-2 border rounded-md bg-white flex items-center gap-2 hover:bg-gray-100" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 18v4h12v-4M6 14h12" />
                </svg>
                Cetak PDF
            </a>
        </div>

        <!-- Tabel Transaksi Barang -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">ID Transaksi</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Konsumen</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Barang</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Metode</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-2">{{ $trx->id_transaksi }}</td>
                            <td class="px-6 py-2">{{ $trx->konsumen->nama_konsumen }}</td>
                            <td class="px-6 py-2">
                                @foreach ($trx->barangModels() as $b)
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        {{ $b->nama_barang }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-2">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</td>
                            <td class="px-6 py-2">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-2">{{ $trx->metode_pembayaran }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Stok -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-2">Ringkasan Stok Barang</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Barang</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase text-right">Masuk</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase text-right">Keluar</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase text-right">Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($stockSummary as $s)
                            <tr>
                                <td class="px-4 py-2">{{ $s->barang->nama_barang }}</td>
                                <td class="px-4 py-2 text-right">{{ $s->masuk }}</td>
                                <td class="px-4 py-2 text-right">{{ $s->keluar }}</td>
                                <td class="px-4 py-2 text-right">{{ $s->stok_akhir }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transaksis->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
