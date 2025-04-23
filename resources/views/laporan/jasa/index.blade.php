<x-app-layout>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header: Search + Filter + Cetak -->
        <div class="flex flex-wrap justify-between gap-4 items-center mb-6">
            <!-- Form Search Konsumen -->
            <form method="GET" action="{{ route('laporan.jasa') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Konsumen..."
                    class="px-4 py-2 border border-gray-300 rounded-md w-52 focus:outline-none focus:ring-2 focus:ring-gray-200">

                <!-- keep filter if any -->
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">

                <button type="submit" class="px-4 py-2 bg-blue-600 text-green-200 rounded-md hover:bg-blue-700">
                    Search
                </button>
            </form>

            <!-- Form Filter Tanggal -->
            <form method="GET" action="{{ route('laporan.jasa') }}" class="flex items-center gap-2">
                <!-- keep search if any -->
                <input type="hidden" name="search" value="{{ request('search') }}">

                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <span class="text-gray-500">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm">

                <button type="submit" class="px-4 py-2 bg-green-600 text-text-green-200 rounded-md hover:bg-green-700">
                    Filter
                </button>
            </form>

            <!-- Tombol Cetak PDF -->
            <a href="{{ route('laporan.jasa.pdf', request()->only('start_date', 'end_date', 'search')) }}"
                class="px-4 py-2 border border-gray-300 rounded-md bg-white flex items-center gap-2 text-gray-700 hover:bg-gray-100"
                target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 18v4h12v-4M6 14h12" />
                </svg>
                Cetak PDF
            </a>
        </div>

        <!-- Table Transaksi Jasa -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">ID
                            TRANSAKSI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">NAMA
                            KONSUMEN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">NAMA JASA
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">TANGGAL
                            TRANSAKSI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">TOTAL HARGA
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">METODE
                            PEMBAYARAN</th>

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transaksis as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-2">{{ $trx->id_transaksi }}</td>
                            <td class="px-6 py-2">{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                            <td class="px-6 py-2">{{ $trx->jasa->nama_jasa ?? '-' }}</td>
                            <td class="px-6 py-2">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-2">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-2">{{ $trx->metode_pembayaran ?? '-' }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                <p class="text-lg">No items found, try to broaden your search</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="mt-4 text-sm text-gray-600">
            @if ($transaksis->total() > 0)
                Menampilkan {{ $transaksis->firstItem() }} - {{ $transaksis->lastItem() }} dari total
                {{ $transaksis->total() }} data
            @else
                Menampilkan 0 hasil
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transaksis->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
