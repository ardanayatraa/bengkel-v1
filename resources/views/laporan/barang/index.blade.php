<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">

        <!-- Header + Aksi -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold border-b pb-2">Ringkasan Stok Barang</h2>
            <div class="space-x-2">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Kembali</a>
                <a href="{{ route('laporan.barang.pdf', request()->only('start_date', 'end_date', 'search')) }}"
                    target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Cetak PDF
                </a>
            </div>
        </div>

        <!-- Filter Tanggal -->
        <form method="GET" action="{{ route('laporan.barang') }}" class="flex items-center gap-2 mb-4">
            <input type="hidden" name="search" value="{{ $search }}">
            <input type="date" name="start_date" value="{{ $start }}"
                class="px-3 py-2 border rounded text-sm">
            <span class="text-gray-500">–</span>
            <input type="date" name="end_date" value="{{ $end }}" class="px-3 py-2 border rounded text-sm">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Filter
            </button>
        </form>

        <!-- Tabel Ringkasan Stok -->
        <div class="bg-white shadow rounded p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stok Awal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Masuk</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Keluar</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stok Akhir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($stockSummary as $s)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $s->barang->nama_barang }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $s->stok_awal }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $s->masuk }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $s->keluar }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $s->stok_akhir }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td class="px-6 py-3 text-right">TOTAL:</td>
                        <td class="px-6 py-3 text-right">{{ $totalStokAwal }}</td>
                        <td class="px-6 py-3 text-right">{{ $totalMasuk }}</td>
                        <td class="px-6 py-3 text-right">{{ $totalKeluar }}</td>
                        <td class="px-6 py-3 text-right">{{ $totalStokAkhir }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-app-layout>
