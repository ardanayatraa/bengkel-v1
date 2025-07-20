<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <div class="mb-4">
            <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                Detail Teknisi
            </h2>
        </div>
        
        <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Teknisi
                    </label>
                    <p class="text-gray-900 dark:text-gray-100">{{ $teknisi->nama_teknisi }}</p>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kontak
                    </label>
                    <p class="text-gray-900 dark:text-gray-100">{{ $teknisi->kontak ?: '-' }}</p>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Persentase Gaji
                    </label>
                    <p class="text-gray-900 dark:text-gray-100">{{ $teknisi->persentase_gaji_formatted }}</p>
                </div>
            </div>

            <!-- Informasi Gaji -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Gaji</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-blue-800">Total Gaji</h4>
                        <p class="text-2xl font-bold text-blue-900">Rp {{ number_format($teknisi->total_gaji, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="font-medium text-yellow-800">Belum Dibayar</h4>
                        <p class="text-2xl font-bold text-yellow-900">Rp {{ number_format($teknisi->total_gaji_belum_dibayar, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium text-green-800">Sudah Dibayar</h4>
                        <p class="text-2xl font-bold text-green-900">Rp {{ number_format($teknisi->total_gaji_sudah_dibayar, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Daftar Gaji -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold mb-4">Riwayat Gaji</h3>
                @if($teknisi->gajiTeknisis->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Transaksi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jasa
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Gaji
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($teknisi->gajiTeknisis->take(10) as $gaji)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($gaji->tanggal_kerja)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            #{{ $gaji->transaksi->id_transaksi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $gaji->jasa->nama_jasa }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($gaji->jumlah_gaji, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $gaji->status_pembayaran === 'sudah_dibayar' 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $gaji->status_pembayaran_formatted }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada riwayat gaji</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('teknisi.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Kembali
                </a>
                <a href="{{ route('teknisi.edit', $teknisi->id_teknisi) }}"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    Edit
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 