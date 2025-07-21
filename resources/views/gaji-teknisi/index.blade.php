<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold">Daftar Gaji Teknisi</h2>
            <div class="flex gap-2">
                {{-- <form action="{{ route('gaji-teknisi.generate-otomatis') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Generate Otomatis
                    </button>
                </form> --}}
                <form action="{{ route('gaji-teknisi.bayar-semua') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-800 text-white rounded hover:bg-red-900" 
                            onclick="return confirm('Yakin ingin membayar semua gaji yang belum dibayar?')">
                        Bayar Semua
                    </button>
                </form>
                <a href="{{ route('gaji-teknisi.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Tambah Gaji
                </a>
                <a href="{{ route('gaji-teknisi.laporan') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    Laporan
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @php
            $gajiBelumDibayar = $gajiTeknisis->where('status_pembayaran', 'belum_dibayar');
            $totalBelumDibayar = $gajiBelumDibayar->sum('jumlah_gaji');
            $countBelumDibayar = $gajiBelumDibayar->count();
        @endphp

        @if($countBelumDibayar > 0)
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
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
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
                                    
                                    @if($gaji->status_pembayaran === 'belum_dibayar')
                                        <form action="{{ route('gaji-teknisi.bayar', $gaji->id_gaji_teknisi) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                Bayar
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('gaji-teknisi.destroy', $gaji->id_gaji_teknisi) }}" method="POST" class="inline">
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
                                Tidak ada data gaji teknisi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout> 