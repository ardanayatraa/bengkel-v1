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
                    <!-- Lama: Total Transaksi Saya -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Transaksi Saya</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $transaksiCount }}</p>
                        </div>
                    </div>

                    <!-- Lama: Jumlah Konsumen -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Jumlah Konsumen</h3>
                            <p class="text-3xl font-bold text-indigo-600">{{ $konsumenCount }}</p>
                        </div>
                    </div>

                    <!-- Baru: Total Service Hari Ini -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Service Hari Ini</h3>
                            <p class="text-3xl font-bold text-yellow-600">
                                Rp {{ number_format($serviceTotalToday, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Baru: Total Barang/Sparepart -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Barang</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $barangCount }}</p>
                        </div>
                    </div>
                @else
                    <!-- Lama: Jumlah Supplier -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Jumlah Supplier</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $supplierCount }}</p>
                        </div>
                    </div>

                    <!-- Lama: Transaksi Barang Masuk -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Transaksi Barang Masuk</h3>
                            <p class="text-3xl font-bold text-green-600">{{ $barangMasukCount }}</p>
                        </div>
                    </div>

                    <!-- Lama: Jumlah User -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Jumlah User</h3>
                            <p class="text-3xl font-bold text-indigo-600">{{ $userCount }}</p>
                        </div>
                    </div>

                    <!-- Baru: Jumlah Konsumen -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Jumlah Konsumen</h3>
                            <p class="text-3xl font-bold text-indigo-600">{{ $konsumenCount }}</p>
                        </div>
                    </div>

                    <!-- Baru: Total Service Hari Ini -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Service Hari Ini</h3>
                            <p class="text-3xl font-bold text-yellow-600">
                                Rp {{ number_format($serviceTotalToday, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Baru: Total Barang/Sparepart -->
                    <div class="bg-white border shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Barang</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $barangCount }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
