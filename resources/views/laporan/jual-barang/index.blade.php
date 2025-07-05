<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow border">
        {{-- FILTER & CETAK --}}
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">

            {{-- Form Filter --}}
            <form method="GET" action="{{ route('laporan.jual.barang') }}" class="flex flex-wrap gap-2 items-center">
                {{-- Pencarian teks --}}
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama/no polisi..."
                    class="px-4 py-2 border rounded w-64">

                {{-- Pilih kategori --}}
                <select name="category" class="px-3 py-2 border rounded">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id_kategori }}" {{ $category == $cat->id_kategori ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>

                {{-- Rentang tanggal --}}
                <input type="date" name="start_date" value="{{ $start }}" class="px-3 py-2 border rounded">
                <span class="mx-1">–</span>
                <input type="date" name="end_date" value="{{ $end }}" class="px-3 py-2 border rounded">

                {{-- Tombol Filter --}}
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
                    Filter
                </button>
            </form>

            {{-- Clear Filter & Cetak PDF --}}
            <div class="flex gap-2">
                {{-- Clear Filter --}}
                <a href="{{ route('laporan.jual.barang') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">
                    Clear Filter
                </a>

                {{-- Cetak PDF --}}
                <a href="{{ route('laporan.jual.barang.pdf', request()->only('search', 'category', 'start_date', 'end_date')) }}"
                    class="px-4 py-2 border rounded flex items-center gap-1" target="_blank">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 2a2 2 0 00-2 2v3h2V4h8v3h2V4a2 2 0 00-2-2H6z" />
                        <path fill-rule="evenodd" d="M4 8h12v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8zm2 2v4h8v-4H6z"
                            clip-rule="evenodd" />
                    </svg>
                    Cetak PDF
                </a>
            </div>
        </div>

        {{-- RINGKASAN --}}
        <div class="mb-4 text-sm">
            <span class="font-medium">Total Semua:</span>
            Rp {{ number_format($totalAll, 0, ',', '.') }}

            @if ($search || $category || $start || $end)
                <span class="ml-6 font-medium">Total Terfilter:</span>
                Rp {{ number_format($totalFiltered, 0, ',', '.') }}
            @endif
        </div>

        {{-- TABEL DATA --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Kasir</th>
                        <th class="px-4 py-2">Pelanggan</th>
                        <th class="px-4 py-2">No Polisi</th>
                        <th class="px-4 py-2">Kategori</th>
                        <th class="px-4 py-2">Barang (qty &amp; subtotal)</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Metode Bayar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $trx->id_transaksi }}</td>
                            <td class="px-4 py-2">{{ $trx->kasir->nama_user ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $trx->konsumen->nama_konsumen ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $trx->konsumen->no_kendaraan ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @php $first = $trx->barangWithQty()->first(); @endphp
                                {{ $first?->model->kategori?->nama_kategori ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                @foreach ($trx->barangWithQty() as $b)
                                    <div>
                                        {{ $b->model->nama_barang }} &times;{{ $b->qty }}
                                        = Rp {{ number_format($b->subtotal, 0, ',', '.') }}
                                    </div>
                                @endforeach
                            </td>
                            <td class="px-4 py-2">{{ $trx->tanggal_transaksi->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 font-semibold">
                                Rp {{ number_format($trx->calculated_total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2">{{ ucfirst($trx->metode_pembayaran) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-gray-400">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($transaksis->count())
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="7" class="text-right px-4 py-3 font-medium">Total:</td>
                            <td class="px-4 py-3 font-semibold">
                                Rp {{ number_format($totalFiltered, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4 flex justify-between text-sm">
            <div>
                Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }} dari
                {{ $transaksis->total() }} data
            </div>
            <div>{{ $transaksis->withQueryString()->links() }}</div>
        </div>
    </div>
</x-app-layout>
