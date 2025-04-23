<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if ($isKasir)
                    <!-- Total Transaksi -->
                    <div class="bg-white border overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-2">Total Transaksi Saya</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $transaksiCount }}</p>
                        </div>
                    </div>

                    <!-- Total Konsumen -->
                    <div class="bg-white border overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-2">Jumlah Konsumen</h3>
                            <p class="text-3xl font-bold text-indigo-600">{{ $konsumenCount }}</p>
                        </div>
                    </div>
                @else
                    <!-- Jumlah Supplier -->
                    <div class="bg-white border overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-2">Jumlah Supplier</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $supplierCount }}</p>
                        </div>
                    </div>

                    <!-- Barang Masuk -->
                    <div class="bg-white border overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-2">Transaksi Barang Masuk</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $barangMasukCount }}</p>
                        </div>
                    </div>

                    <!-- Jumlah User -->
                    <div class="bg-white border overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-2">Jumlah User</h3>
                            <p class="text-3xl font-bold text-indigo-600">{{ $userCount }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
