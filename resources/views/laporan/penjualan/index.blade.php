<x-app-layout>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

        <!-- Header: Search + Filter + Cetak -->
        <div class="flex flex-wrap justify-between gap-4 items-center mb-6">
            <form method="GET" action="{{ route('laporan.penjualan') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Konsumen..."
                    class="px-4 py-2 border rounded-md w-52 focus:ring focus:ring-gray-200">
                <input type="hidden" name="start_date" value="{{ $start }}">
                <input type="hidden" name="end_date" value="{{ $end }}">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Search
                </button>
            </form>

            <form method="GET" action="{{ route('laporan.penjualan') }}" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="date" name="start_date" value="{{ $start }}"
                    class="px-3 py-2 border rounded-md text-sm">
                <span class="text-gray-500">–</span>
                <input type="date" name="end_date" value="{{ $end }}"
                    class="px-3 py-2 border rounded-md text-sm">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Filter
                </button>
            </form>

            <a href="{{ route('laporan.penjualan.pdf', request()->only('start_date', 'end_date', 'search')) }}"
                class="px-4 py-2 border rounded-md bg-white flex items-center gap-2 text-gray-700 hover:bg-gray-100"
                target="_blank">
                <!-- icon PDF -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 9V2h12v7M6 18H4a2 2…" />
                </svg>
                Cetak PDF
            </a>
        </div>

        <!-- Ringkasan Total -->
        <div class="mb-4 text-sm text-gray-700">
            <span class="font-medium">Total Semua:</span>
            <span>Rp {{ number_format($totalAll, 0, ',', '.') }}</span>

            @if ($start || $end || $search)
                <span class="ml-6 font-medium">Total Terfilter:</span>
                <span>Rp {{ number_format($totalFiltered, 0, ',', '.') }}</span>
            @endif
        </div>

        <!-- Tabel Penjualan -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Konsumen</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Produk/Jasa</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Metode</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transaksis as $trx)
                        @php
                            $barangs = $trx->barangModels();
                            $jasas = $trx->jasaModels();
                            $type =
                                $barangs->isNotEmpty() && $jasas->isNotEmpty()
                                    ? 'Barang & Jasa'
                                    : ($barangs->isNotEmpty()
                                        ? 'Barang'
                                        : ($jasas->isNotEmpty()
                                            ? 'Jasa'
                                            : '-'));
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-2">{{ $trx->id_transaksi }}</td>
                            <td class="px-6 py-2">{{ $trx->konsumen->nama_konsumen }}</td>
                            <td class="px-6 py-2 space-y-1">
                                @foreach ($barangs as $b)
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        {{ $b->nama_barang }}
                                    </span>
                                @endforeach
                                @foreach ($jasas as $j)
                                    <span
                                        class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ $j->nama_jasa }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-2">{{ $type }}</td>
                            <td class="px-6 py-2">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</td>
                            <td class="px-6 py-2">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-2">{{ ucfirst($trx->metode_pembayaran) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                Tidak ada data penjualan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($transaksis->count())
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-6 py-3 text-right font-medium">Total Terfilter:</td>
                            <td class="px-6 py-3 font-semibold">Rp {{ number_format($totalFiltered, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between text-sm text-gray-600">
            <div>
                @if ($transaksis->total())
                    Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }} dari
                    {{ $transaksis->total() }} data
                @else
                    Menampilkan 0 data
                @endif
            </div>
            <div>{{ $transaksis->withQueryString()->links() }}</div>
        </div>
    </div>
</x-app-layout>
