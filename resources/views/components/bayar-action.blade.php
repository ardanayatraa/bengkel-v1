@props(['transaksi'])

<div x-data="{ open: false }" class="inline-block">
    @if ($transaksi->status_pembayaran === 'belum bayar')
        <div class="inline-flex items-center gap-2">
            <span
                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
                </svg>
                Belum Bayar
            </span>
            <button @click="open = true"
                class="inline-flex items-center px-3 py-1 bg-red-900 text-xs font-medium rounded-md shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
                Bayar
            </button>


        </div>
    @else
        <div class="inline-block">
            <div
                class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-red-100 to-red-200 border border-red-300 rounded-full">
                <svg class="w-3 h-3 mr-1 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                </svg>
                <span class="text-red-800 font-semibold text-xs">Lunas</span>
            </div>
            @if ($transaksi->uang_diterima || $transaksi->kembalian)
                <div class="mt-1 text-xs text-gray-600 leading-tight">
                    <div>Total: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                    <div>Bayar: Rp {{ number_format($transaksi->uang_diterima, 0, ',', '.') }}</div>
                    <div>Kembali: Rp
                        {{ number_format($transaksi->uang_diterima - $transaksi->total_harga, 0, ',', '.') }}</div>

                </div>
            @endif
        </div>
    @endif

    {{-- MODAL --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">

        <div @click.outside="open = false"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden border border-gray-200">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-red-800 to-red-900 px-5 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-white">Pembayaran</h2>
                        <p class="text-red-100 text-sm">ID: {{ $transaksi->id_transaksi }}</p>
                    </div>
                    <button @click="open = false" class="text-white hover:text-red-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('transaksi.bayar', $transaksi->id_transaksi) }}"
                x-data="{
                    uang: {{ $transaksi->total_harga }},
                    total: {{ $transaksi->total_harga }},
                    get kembali() {
                        return this.uang > this.total ? this.uang - this.total : 0;
                    },
                    get isValid() {
                        return this.uang >= this.total;
                    }
                }" class="p-5 space-y-4">
                @csrf

                {{-- Total --}}
                <div class="bg-gradient-to-r from-red-50 to-red-100 p-4 rounded-xl border border-red-200">
                    <p class="text-sm text-gray-600 font-medium">Total yang harus dibayar</p>
                    <p class="text-xl font-bold text-red-700 mt-1">
                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Uang Diterima --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Uang Diterima</label>
                    <div class="relative">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <span class="text-sm">Rp</span>
                        </div>
                        <input type="number" name="uang_diterima" min="{{ $transaksi->total_harga }}" step="1000"
                            x-model="uang"
                            class="w-full pl-10 pr-4 py-3 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                            :class="{
                                'border-red-300 bg-red-50': !isValid,
                                'border-green-300 bg-green-50': isValid && uang > total
                            }"
                            required>
                    </div>
                    <p x-show="!isValid" class="text-sm text-red-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
                        </svg>
                        Uang kurang dari total pembayaran
                    </p>
                </div>

                {{-- Kembalian --}}
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-4 rounded-xl border border-amber-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-amber-600 rounded-full flex items-center justify-center mr-2">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600 font-medium">Kembalian</span>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-amber-700">
                                Rp <span x-text="kembali.toLocaleString('id-ID')"></span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="open = false"
                        class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" :disabled="!isValid"
                        class="flex-1 px-4 py-2disabled:bg-gray-400  font-medium rounded-lg shadow-lg hover:shadow-xl disabled:shadow-none transform hover:-translate-y-0.5 disabled:translate-y-0 transition-all duration-200 disabled:cursor-not-allowed">
                        <span class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            Konfirmasi Pembayaran
                        </span>
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
