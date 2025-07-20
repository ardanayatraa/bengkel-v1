<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold">Detail Gaji Teknisi</h2>
            <div class="flex gap-2">
                <a href="{{ route('gaji-teknisi.edit', $gajiTeknisi->id_gaji_teknisi) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Edit
                </a>
                <a href="{{ route('gaji-teknisi.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-red-800 text-white px-6 py-4">
                <h3 class="text-lg font-semibold">Gaji #{{ $gajiTeknisi->id_gaji_teknisi }}</h3>
                <p class="text-red-200">Detail informasi gaji teknisi</p>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informasi Teknisi -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-3">Informasi Teknisi</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">Nama Teknisi:</span>
                                <p class="font-medium">{{ $gajiTeknisi->teknisi->nama_teknisi }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Persentase Gaji:</span>
                                <p class="font-medium">{{ $gajiTeknisi->persentase_gaji }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Transaksi -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-3">Informasi Transaksi</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">ID Transaksi:</span>
                                <p class="font-medium">#{{ $gajiTeknisi->transaksi->id_transaksi }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Konsumen:</span>
                                <p class="font-medium">{{ $gajiTeknisi->transaksi->konsumen->nama_konsumen ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Tanggal Transaksi:</span>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($gajiTeknisi->transaksi->tanggal_transaksi)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Jasa -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-3">Informasi Jasa</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">Nama Jasa:</span>
                                <p class="font-medium">{{ $gajiTeknisi->jasa->nama_jasa }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Harga Jasa:</span>
                                <p class="font-medium">Rp {{ number_format($gajiTeknisi->harga_jasa, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Gaji -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-3">Informasi Gaji</h4>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">Jumlah Gaji:</span>
                                <p class="font-medium text-lg text-red-800">Rp {{ number_format($gajiTeknisi->jumlah_gaji, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Tanggal Kerja:</span>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($gajiTeknisi->tanggal_kerja)->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Status Pembayaran:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $gajiTeknisi->status_pembayaran === 'sudah_dibayar' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $gajiTeknisi->status_pembayaran_formatted }}
                                </span>
                            </div>
                            @if($gajiTeknisi->tanggal_pembayaran)
                                <div>
                                    <span class="text-sm text-gray-600">Tanggal Pembayaran:</span>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($gajiTeknisi->tanggal_pembayaran)->format('d/m/Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                @if($gajiTeknisi->keterangan)
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-2">Keterangan</h4>
                        <p class="text-gray-700">{{ $gajiTeknisi->keterangan }}</p>
                    </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="mt-6 flex gap-2">
                    @if($gajiTeknisi->status_pembayaran === 'belum_dibayar')
                        <form action="{{ route('gaji-teknisi.bayar', $gajiTeknisi->id_gaji_teknisi) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Bayar Gaji
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('gaji-teknisi.destroy', $gajiTeknisi->id_gaji_teknisi) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" 
                                onclick="return confirm('Yakin ingin menghapus gaji ini?')">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 