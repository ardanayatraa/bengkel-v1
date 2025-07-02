<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow border">
        {{-- Header: Cari, Filter, Print --}}
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <form method="GET" action="{{ route('laporan.jual.barang') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama/no polisi..."
                    class="px-4 py-2 border rounded w-64">
                <input type="hidden" name="start_date" value="{{ $start }}">
                <input type="hidden" name="end_date" value="{{ $end }}">
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Cari</button>
            </form>
            <form method="GET" action="{{ route('laporan.jual.barang') }}" class="flex gap-2">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="date" name="start_date" value="{{ $start }}" class="px-3 py-2 border rounded">
                <span>–</span>
                <input type="date" name="end_date" value="{{ $end }}" class="px-3 py-2 border rounded">
                <button class="px-4 py-2 bg-green-600 text-white rounded">Filter</button>
            </form>
            <a href="{{ route('laporan.jual.barang.pdf', request()->only('start_date', 'end_date', 'search')) }}"
                class="px-4 py-2 border rounded flex items-center gap-1" target="_blank">
                <svg class="h-5 w-5"><!-- printer icon --></svg> Cetak PDF
            </a>
        </div>

        {{-- Ringkasan --}}
        <div class="mb-4 text-sm">
            <span class="font-medium">Total Semua:</span>
            Rp {{ number_format($totalAll, 0, ',', '.') }}
            @if ($start || $end || $search)
                <span class="ml-6 font-medium">Total Terfilter:</span>
                Rp {{ number_format($totalFiltered, 0, ',', '.') }}
            @endif
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Kasir</th>
                        <th class="px-4 py-2">Pelanggan</th>
                        <th class="px-4 py-2">No Polisi</th>
                        <th class="px-4 py-2">Barang (qty & subtotal)</th>
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
                                @foreach ($trx->barangWithQty() as $b)
                                    <div>{{ $b->model->nama_barang }} ×{{ $b->qty }} = Rp
                                        {{ number_format($b->subtotal, 0, ',', '.') }}</div>
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
                            <td colspan="8" class="py-8 text-center text-gray-400">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($transaksis->count())
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="6" class="text-right px-4 py-3 font-medium">Total:</td>
                            <td class="px-4 py-3 font-semibold">Rp {{ number_format($totalFiltered, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex justify-between text-sm">
            <div>
                Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }} dari
                {{ $transaksis->total() }} data
            </div>
            <div>{{ $transaksis->withQueryString()->links() }}</div>
        </div>
    </div>
</x-app-layout>
