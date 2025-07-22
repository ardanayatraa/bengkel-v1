<x-app-layout>
    <div class="bg-white rounded-lg shadow-sm border p-6">

        {{-- Filters --}}
        <form method="GET" action="{{ route('laporan.jasa') }}" class="flex flex-wrap gap-2 mb-6">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari Nama/No Polisi..."
                class="px-3 py-2 border rounded w-64">

            @if ($isAdmin)
                <select name="kasir_id" class="px-3 py-2 border rounded">
                    <option value="">Semua Kasir</option>
                    @foreach ($kasirs as $k)
                        <option value="{{ $k->id_user }}" @selected($k->id_user == $kasirId)>
                            {{ $k->nama_user }}
                        </option>
                    @endforeach
                </select>
            @endif

            <select name="teknisi_id" class="px-3 py-2 border rounded">
                <option value="">Semua Teknisi</option>
                @foreach ($teknisis as $t)
                    <option value="{{ $t->id_teknisi }}" @selected($t->id_teknisi == $teknisiId)>
                        {{ $t->nama_teknisi }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="start_date" value="{{ $start }}" class="px-3 py-2 border rounded">
            <span class="self-center">–</span>
            <input type="date" name="end_date" value="{{ $end }}" class="px-3 py-2 border rounded">

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                Filter
            </button>

            <a href="{{ route('laporan.jasa.pdf', request()->only('search', 'kasir_id', 'teknisi_id', 'start_date', 'end_date')) }}"
                class="px-4 py-2 border rounded flex items-center gap-1" target="_blank">
                <!-- printer icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0
                   01-2-2v-5a2 2 0 012-2h16a2 2 0
                   012 2v5a2 2 0 01-2 2h-2M6 18v4h12v-4M6 14h12" />
                </svg>
                Cetak PDF
            </a>
        </form>

        {{-- Summary --}}
        <div class="mb-4 text-sm text-gray-700">
            <span class="font-medium">Total Semua Jasa:</span>
            Rp {{ number_format($totalAll, 0, ',', '.') }}

            @if ($start || $end || $search || ($isAdmin && $kasirId) || $teknisiId)
                <span class="ml-6 font-medium">Total Terfilter:</span>
                Rp {{ number_format($totalFiltered, 0, ',', '.') }}
            @endif
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teknisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Polisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Daftar Jasa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode Bayar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-2">{{ $trx->id_transaksi }}</td>
                            <td class="px-6 py-2">{{ $trx->kasir->nama_user ?? '-' }}</td>
                            <td class="px-6 py-2">{{ $trx->teknisi->nama_teknisi ?? '-' }}</td>
                            <td class="px-6 py-2">{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                            <td class="px-6 py-2">{{ $trx->konsumen->no_kendaraan ?? '-' }}</td>
                            <td class="px-6 py-2 space-y-1">
                                @foreach ($trx->jasaModels() as $j)
                                    <span
                                        class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        {{ $j->nama_jasa }}—Rp{{ number_format($j->harga_jasa, 0, ',', '.') }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-2">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</td>
                            <td class="px-6 py-2 font-semibold">
                                Rp {{ number_format($trx->jasaModels()->sum('harga_jasa'), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-2">{{ ucfirst($trx->metode_pembayaran) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center text-gray-400">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($transaksis->count())
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="7" class="px-6 py-3 text-right font-medium">Total:</td>
                            <td class="px-6 py-3 font-semibold">Rp {{ number_format($totalFiltered, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
                @if ($transaksis->total() > 0)
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
