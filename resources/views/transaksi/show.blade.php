<x-app-layout>
    <div class="mx-auto w-full sm:px-6 lg:px-8 py-6">
        {{-- Card Container --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            {{-- Header --}}
            <div class="bg-red-100 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-red-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-800" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                    </svg>
                    Detail Transaksi
                </h2>
                <div class="space-x-2">
                    <a href="{{ route('transaksi.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        Kembali
                    </a>
                    <a href="{{ route('transaksi.print', $transaksi->id_transaksi) }}"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" target="_blank">
                        Cetak PDF
                    </a>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">ID Transaksi</label>
                            <p class="mt-1 text-gray-800">#{{ $transaksi->id_transaksi }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Tanggal</label>
                            <p class="mt-1 text-gray-800">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Konsumen</label>
                            <p class="mt-1 text-gray-800">{{ $transaksi->konsumen->nama_konsumen }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Metode Pembayaran</label>
                            <p class="mt-1 text-gray-800">{{ ucfirst($transaksi->metode_pembayaran) }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Barang</label>
                            @if ($barangs->count())
                                <ul class="mt-1 text-gray-800 list-disc list-inside">
                                    @foreach ($barangs as $barang)
                                        <li>{{ $barang->nama_barang }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mt-1 text-gray-800">–</p>
                            @endif
                        </div>

                       <div>
                        <label class="block text-sm font-medium text-gray-600">Jasa</label>
                        @if ($jasas->count())
                            <ul class="mt-1 text-gray-800 list-disc list-inside">
                                @foreach ($jasas as $jasa)
                                    <li>{{ $jasa->nama_jasa }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-1 text-gray-800">–</p>
                        @endif
                    </div>


                        <div>
                            <label class="block text-sm font-medium text-gray-600">Total Harga</label>
                            <p class="mt-1 text-gray-800 text-lg font-semibold">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </p>
                        </div>
                        @if ($transaksi->points->count())
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Poin Diberikan</label>
                                <p class="mt-1 text-gray-800">{{ $transaksi->points->sum('jumlah_point') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
