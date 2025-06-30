<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <!-- Header + Tombol aksi -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold border-b border-gray-200 pb-4">
                Ringkasan Stok Barang
            </h2>
            <div class="space-x-2">
                <!-- Tombol Kembali -->
                <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Kembali
                </a>
                <!-- Tombol Cetak PDF -->
                <a href="{{ route('laporan.barang.pdf', request()->only('start_date', 'end_date', 'search')) }}"
                    target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Cetak PDF
                </a>
            </div>
        </div>

        <!-- Filter (jika perlu) -->
        <div class="flex flex-wrap gap-4 mb-4">
            <form method="GET" action="{{ route('laporan.barang') }}" class="flex items-center gap-2">

                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="px-3 py-2 border rounded-md text-sm">
                <span class="text-gray-500">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="px-3 py-2 border rounded-md text-sm">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Tabel Ringkasan Stok -->
        <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $s->barang->nama_barang }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ $s->stok_akhir + $s->keluar - $s->masuk }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ $s->masuk }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ $s->keluar }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ $s->stok_akhir }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
