<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold">Daftar Gaji Teknisi</h2>
            <div class="flex gap-2">
                <a href="{{ route('gaji-teknisi.laporan') }}"
                    class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    Laporan
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Data</h3>
            <form method="GET" action="{{ route('gaji-teknisi.index') }}"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Filter Teknisi -->
                <div>
                    <label for="id_teknisi" class="block text-sm font-medium text-gray-700 mb-1">
                        Teknisi
                    </label>
                    <select name="id_teknisi" id="id_teknisi"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Teknisi</option>
                        @foreach ($teknisis as $teknisi)
                            <option value="{{ $teknisi->id_teknisi }}"
                                {{ request('id_teknisi') == $teknisi->id_teknisi ? 'selected' : '' }}>
                                {{ $teknisi->nama_teknisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status Pembayaran -->
                <div>
                    <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                        Status Pembayaran
                    </label>
                    <select name="status_pembayaran" id="status_pembayaran"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="belum_dibayar"
                            {{ request('status_pembayaran') == 'belum_dibayar' ? 'selected' : '' }}>
                            Belum Dibayar
                        </option>
                        <option value="sudah_dibayar"
                            {{ request('status_pembayaran') == 'sudah_dibayar' ? 'selected' : '' }}>
                            Sudah Dibayar
                        </option>
                    </select>
                </div>

                <!-- Filter Tanggal Mulai -->
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Filter Tanggal Akhir -->
                <div>
                    <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir
                    </label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tombol Filter dan Reset -->
                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Filter
                    </button>
                    <a href="{{ route('gaji-teknisi.index') }}"
                        class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @php
            $gajiBelumDibayar = $gajiTeknisis->where('status_pembayaran', 'belum_dibayar');
            $totalBelumDibayar = $gajiBelumDibayar->sum('jumlah_gaji');
            $countBelumDibayar = $gajiBelumDibayar->count();
        @endphp

        @if ($countBelumDibayar > 0)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <strong>Ada {{ $countBelumDibayar }} gaji yang belum dibayar</strong>
                        <br>
                        Total: <strong>Rp {{ number_format($totalBelumDibayar, 0, ',', '.') }}</strong>
                    </div>
                    <form action="{{ route('gaji-teknisi.bayar-semua') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-red-800 text-white rounded hover:bg-red-900 text-sm"
                            onclick="return confirm('Yakin ingin membayar semua {{ $countBelumDibayar }} gaji sekaligus?')">
                            Bayar Semua
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Informasi Filter Aktif -->
        @if (request()->hasAny(['id_teknisi', 'status_pembayaran', 'tanggal_mulai', 'tanggal_akhir']))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <strong>Filter Aktif:</strong>
                        @if (request('id_teknisi'))
                            <span class="ml-2 px-2 py-1 bg-blue-100 rounded text-sm">
                                Teknisi: {{ $teknisis->find(request('id_teknisi'))->nama_teknisi ?? 'Unknown' }}
                            </span>
                        @endif
                        @if (request('status_pembayaran'))
                            <span class="ml-2 px-2 py-1 bg-blue-100 rounded text-sm">
                                Status:
                                {{ request('status_pembayaran') == 'belum_dibayar' ? 'Belum Dibayar' : 'Sudah Dibayar' }}
                            </span>
                        @endif
                        @if (request('tanggal_mulai'))
                            <span class="ml-2 px-2 py-1 bg-blue-100 rounded text-sm">
                                Dari: {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }}
                            </span>
                        @endif
                        @if (request('tanggal_akhir'))
                            <span class="ml-2 px-2 py-1 bg-blue-100 rounded text-sm">
                                Sampai: {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}
                            </span>
                        @endif
                    </div>
                    <div class="text-sm">
                        Menampilkan {{ $gajiTeknisis->count() }} data
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teknisi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Transaksi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jasa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga Jasa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Persentase
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Gaji
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Kerja
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($gajiTeknisis as $gaji)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $gaji->teknisi->nama_teknisi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    #{{ $gaji->transaksi->id_transaksi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $gaji->jasa->nama_jasa }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    Rp {{ number_format($gaji->harga_jasa, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $gaji->persentase_gaji_formatted }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($gaji->jumlah_gaji, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($gaji->tanggal_kerja)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $gaji->status_pembayaran === 'sudah_dibayar'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $gaji->status_pembayaran_formatted }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('gaji-teknisi.show', $gaji->id_gaji_teknisi) }}"
                                        class="text-blue-600 hover:text-blue-900">Detail</a>
                                    <a href="{{ route('gaji-teknisi.edit', $gaji->id_gaji_teknisi) }}"
                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                    @if ($gaji->status_pembayaran === 'belum_dibayar')
                                        <form action="{{ route('gaji-teknisi.bayar', $gaji->id_gaji_teknisi) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                Bayar
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('gaji-teknisi.destroy', $gaji->id_gaji_teknisi) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Yakin ingin menghapus gaji ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                @if (request()->hasAny(['id_teknisi', 'status_pembayaran', 'tanggal_mulai', 'tanggal_akhir']))
                                    Tidak ada data gaji teknisi yang sesuai dengan filter
                                @else
                                    Tidak ada data gaji teknisi
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($gajiTeknisis->count() > 0)
            <div class="mt-4 bg-gray-50 px-6 py-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Total Gaji:</span>
                        <span class="font-bold text-gray-900">
                            Rp {{ number_format($gajiTeknisis->sum('jumlah_gaji'), 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Belum Dibayar:</span>
                        <span class="font-bold text-yellow-600">
                            Rp
                            {{ number_format($gajiTeknisis->where('status_pembayaran', 'belum_dibayar')->sum('jumlah_gaji'), 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Sudah Dibayar:</span>
                        <span class="font-bold text-green-600">
                            Rp
                            {{ number_format($gajiTeknisis->where('status_pembayaran', 'sudah_dibayar')->sum('jumlah_gaji'), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
