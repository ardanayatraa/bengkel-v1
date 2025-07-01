<x-app-layout>
    <div class="mx-auto w-full sm:px-6 lg:px-8 py-6">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-red-100 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-red-800 flex items-center gap-2">
                    <!-- icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-800" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6"/></svg>
                    Detail Transaksi
                </h2>
                <div class="space-x-2">
                    <a href="{{ route('transaksi.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Kembali</a>
                    <a href="{{ route('transaksi.print', $transaksi->id_transaksi) }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" target="_blank">Cetak PDF</a>
                </div>
            </div>
            <div class="px-6 py-8">
                {{-- Metadata --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div><label class="block text-sm font-medium text-gray-600">ID</label><p class="mt-1 text-gray-800">#{{ $transaksi->id_transaksi }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-600">Tanggal</label><p class="mt-1 text-gray-800">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-600">Konsumen</label><p class="mt-1 text-gray-800">{{ $transaksi->konsumen->nama_konsumen }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-600">Metode Bayar</label><p class="mt-1 text-gray-800">{{ ucfirst($transaksi->metode_pembayaran) }}</p></div>
                    </div>
                    <div class="space-y-4">
                        <div><label class="block text-sm font-medium text-gray-600">Teknisi</label><p class="mt-1 text-gray-800">{{ $transaksi->teknisi->nama_teknisi ?? '–' }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-600">Poin Diberikan</label><p class="mt-1 text-gray-800">{{ $transaksi->points->sum('jumlah_point') }} Point</p></div>
                    </div>
                </div>

                @php
                    $totalBarang = $barangs->sum('subtotal');
                    $totalJasa   = $jasas->sum(fn($j) => $j->harga_jasa);
                    $subtotal     = $totalBarang + $totalJasa;
                    $diskon       = max(0, $subtotal - $transaksi->total_harga);
                    $grandTotal   = $transaksi->total_harga;
                    $sisaPoint    = $transaksi->konsumen->jumlah_point;
                @endphp

                {{-- Tabel Barang --}}
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-2">Daftar Barang</h3>
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2 text-left">Nama</th>
                                <th class="border p-2 text-center">Qty</th>
                                <th class="border p-2 text-right">Harga</th>
                                <th class="border p-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangs as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-2">{{ $item->model->nama_barang }}</td>
                                    <td class="border p-2 text-center">{{ $item->qty }}</td>
                                    <td class="border p-2 text-right">Rp {{ number_format($item->model->harga_jual,0,',','.') }}</td>
                                    <td class="border p-2 text-right">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="border p-2 text-center text-gray-500">Tidak ada barang</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="font-semibold bg-gray-50">
                                <td colspan="3" class="border p-2 text-right">Total Barang:</td>
                                <td class="border p-2 text-right">Rp {{ number_format($totalBarang,0,',','.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Tabel Jasa --}}
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-2">Daftar Jasa</h3>
                    @if($jasas->count())
                        <table class="w-full border-collapse">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border p-2 text-left">Nama</th>
                                    <th class="border p-2 text-center">Qty</th>
                                    <th class="border p-2 text-right">Harga</th>
                                    <th class="border p-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jasas as $j)
                                    @php $sub = $j->harga_jasa; @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="border p-2">{{ $j->nama_jasa }}</td>
                                        <td class="border p-2 text-center">1</td>
                                        <td class="border p-2 text-right">Rp {{ number_format($j->harga_jasa,0,',','.') }}</td>
                                        <td class="border p-2 text-right">Rp {{ number_format($sub,0,',','.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-semibold bg-gray-50">
                                    <td colspan="3" class="border p-2 text-right">Total Jasa:</td>
                                    <td class="border p-2 text-right">Rp {{ number_format($totalJasa,0,',','.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <p class="text-gray-800">–</p>
                    @endif
                </div>

                {{-- Ringkasan --}}
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <table class="w-full">
                        <tbody>
                            <tr>
                                <td class="text-right pr-4">Subtotal:</td>
                                <td class="text-right font-medium">Rp {{ number_format($subtotal,0,',','.') }}</td>
                            </tr>
                            @if($diskon > 0)
                                <tr>
                                    <td class="text-right pr-4 text-red-600">Diskon (10 poin):</td>
                                    <td class="text-right font-medium text-red-600">- Rp {{ number_format($diskon,0,',','.') }}</td>
                                </tr>
                            @endif
                            <tr class="font-bold">
                                <td class="text-right pr-4">Total Bayar:</td>
                                <td class="text-right">Rp {{ number_format($grandTotal,0,',','.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-right pr-4">Sisa Poin:</td>
                                <td class="text-right">{{ $sisaPoint }} poin</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
