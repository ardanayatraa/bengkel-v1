<x-app-layout>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

        <!-- Header: Search + Filter + Cetak -->
        <div class="flex flex-wrap justify-between gap-4 items-center mb-6">

            {{-- Pencarian Nama Konsumen / No Polisi --}}
            <form method="GET" action="{{ route('laporan.jasa') }}" class="flex items-center gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari Nama Pelanggan atau No Polisi..."
                    class="px-4 py-2 border border-gray-300 rounded-md w-64 focus:outline-none focus:ring-2 focus:ring-gray-200"
                >
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date"   value="{{ request('end_date') }}">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Cari
                </button>
            </form>

            {{-- Filter Rentang Tanggal --}}
            <form method="GET" action="{{ route('laporan.jasa') }}" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input
                    type="date"
                    name="start_date"
                    value="{{ request('start_date') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm"
                >
                <span class="text-gray-500">–</span>
                <input
                    type="date"
                    name="end_date"
                    value="{{ request('end_date') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm"
                >
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Filter
                </button>
            </form>

            {{-- Tombol Cetak PDF --}}
            <a
                href="{{ route('laporan.jasa.pdf', request()->only('start_date','end_date','search')) }}"
                class="px-4 py-2 border border-gray-300 rounded-md bg-white flex items-center gap-2 text-gray-700 hover:bg-gray-100"
                target="_blank"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">Nama Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">No Polisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">Daftar Jasa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">Metode Bayar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transaksis as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-2">{{ $trx->id_transaksi }}</td>
                            <td class="px-6 py-2">{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                            <td class="px-6 py-2">{{ $trx->konsumen->no_kendaraan ?? '-' }}</td>
                            <td class="px-6 py-2 space-y-1">
                                @foreach ($trx->jasaModels() as $j)
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ $j->nama_jasa }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-2">
                                {{ $trx->tanggal_transaksi->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-2">Rp {{ number_format($trx->total_harga,0,',','.') }}</td>
                            <td class="px-6 py-2">{{ $trx->metode_pembayaran ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                Tidak ada data, coba ubah filter atau pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Info -->
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
                @if($transaksis->total()>0)
                    Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }} dari {{ $transaksis->total() }} data
                @else
                    Menampilkan 0 data
                @endif
            </div>
            <div>
                {{ $transaksis->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
