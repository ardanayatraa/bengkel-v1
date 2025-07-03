{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Section: Data Hari Ini -->
            <div>
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Data Hari Ini</h3>
                    <p class="text-gray-600">
                        {{ \Carbon\Carbon::now('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if ($isKasir)
                        <!-- Transaksi Hari Ini -->
                        <a href="{{ route('transaksi.index', ['date' => now()->toDateString()]) }}"
                            class="block bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg rounded-lg hover:opacity-90 transition">
                            <div class="p-6 flex items-center">
                                <i class="fas fa-cash-register w-8 h-8 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium opacity-90">Transaksi Hari Ini</h4>
                                    <p class="text-2xl font-bold">{{ $transaksiTodayCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Service Hari Ini -->
                        <a href="{{ route('laporan.jasa', ['filter' => 'service_today']) }}"
                            class="block bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg rounded-lg hover:opacity-90 transition">
                            <div class="p-6 flex items-center">
                                <i class="fas fa-tools w-8 h-8 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium opacity-90">Total Service Hari Ini</h4>
                                    <p class="text-2xl font-bold">Rp
                                        {{ number_format($serviceTotalToday, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Konsumen Baru Hari Ini -->
                        <a href="{{ route('konsumen.index', ['filter' => 'today']) }}"
                            class="block bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg rounded-lg hover:opacity-90 transition">
                            <div class="p-6 flex items-center">
                                <i class="fas fa-user-plus w-8 h-8 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium opacity-90">Konsumen Baru</h4>
                                    <p class="text-2xl font-bold">{{ $konsumenTodayCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Pendapatan Hari Ini -->
                        <a href="{{ route('laporan.penjualan', ['filter' => 'today']) }}"
                            class="block bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg rounded-lg hover:opacity-90 transition">
                            <div class="p-6 flex items-center">
                                <i class="fas fa-wallet w-8 h-8 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium opacity-90">Total Pendapatan</h4>
                                    <p class="text-2xl font-bold">Rp
                                        {{ number_format($pendapatanTodayTotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <!-- Transaksi Hari Ini -->
                        <a href="{{ route('transaksi.index', ['date' => now()->toDateString()]) }}"
                            class="block bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg rounded-lg hover:opacity-90 transition">
                            <div class="p-6 flex items-center">
                                <i class="fas fa-cash-register w-8 h-8 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium opacity-90">Transaksi Hari Ini</h4>
                                    <p class="text-2xl font-bold">{{ $transaksiTodayCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Service Hari Ini -->
                        <a href="{{ route('laporan.penjualan', ['filter' => 'service_today']) }}"
                            class="block bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg rounded-lg hover:opacity-90 transition">
                            <div class="p-6 flex items-center">
                                <i class="fas fa-tools w-8 h-8 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium opacity-90">Total Service Hari Ini</h4>
                                    <p class="text-2xl font-bold">Rp
                                        {{ number_format($serviceTotalToday, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Barang Masuk Hari Ini -->
                        <a href="{{ route('trx-barang-masuk.index', ['date' => now()->toDateString()]) }}"
                            class="block bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg rounded-lg hover:opacity-90 transition">
                            <div class="p-6 flex items-center">
                                <i class="fas fa-dolly-flatbed w-8 h-8 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium opacity-90">Barang Masuk Hari Ini</h4>
                                    <p class="text-2xl font-bold">{{ $barangMasukTodayCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Pendapatan Hari Ini -->
                        <a href="{{ route('laporan.penjualan', ['filter' => 'today']) }}"
                            class="block bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg rounded-lg hover:opacity-90 transition">
                            <div class="p-6 flex items-center">
                                <i class="fas fa-wallet w-8 h-8 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium opacity-90">Total Pendapatan</h4>
                                    <p class="text-2xl font-bold">Rp
                                        {{ number_format($pendapatanTodayTotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Section: Data Keseluruhan -->
            <div>
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Data Keseluruhan</h3>
                    <p class="text-gray-600">Total keseluruhan data sistem</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if ($isKasir)
                        <!-- Total Transaksi -->
                        <a href="{{ route('transaksi.index') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-receipt w-10 h-10 bg-green-100 rounded-lg p-2 text-green-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Transaksi</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $transaksiCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Total Konsumen -->
                        <a href="{{ route('konsumen.index') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-users w-10 h-10 bg-indigo-100 rounded-lg p-2 text-indigo-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Konsumen</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $konsumenCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Total Barang -->
                        <a href="{{ route('barang.index') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-boxes w-10 h-10 bg-blue-100 rounded-lg p-2 text-blue-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Barang</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $barangCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Total Pendapatan -->
                        <a href="{{ route('laporan.penjualan') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-chart-line w-10 h-10 bg-yellow-100 rounded-lg p-2 text-yellow-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Pendapatan</h4>
                                    <p class="text-2xl font-bold text-gray-900">Rp
                                        {{ number_format($pendapatanTotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                    @else
                        <!-- Total Supplier -->
                        <a href="{{ route('supplier.index') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-truck w-10 h-10 bg-blue-100 rounded-lg p-2 text-blue-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Supplier</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $supplierCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Total Barang Masuk -->
                        <a href="{{ route('trx-barang-masuk.index') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-warehouse w-10 h-10 bg-green-100 rounded-lg p-2 text-green-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Barang Masuk</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $barangMasukCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Total User -->
                        <a href="{{ route('user.index') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-user w-10 h-10 bg-indigo-100 rounded-lg p-2 text-indigo-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total User</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $userCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Total Konsumen -->
                        <a href="{{ route('konsumen.index') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-users w-10 h-10 bg-purple-100 rounded-lg p-2 text-purple-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Konsumen</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $konsumenCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Total Barang -->
                        <a href="{{ route('barang.index') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-boxes w-10 h-10 bg-blue-100 rounded-lg p-2 text-blue-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Barang</h4>
                                    <p class="text-2xl font-bold text-gray-900">{{ $barangCount }}</p>
                                </div>
                            </div>
                        </a>

                        <!-- Total Pendapatan -->
                        <a href="{{ route('laporan.penjualan') }}"
                            class="block bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6 flex items-center">
                                <i
                                    class="fas fa-chart-line w-10 h-10 bg-yellow-100 rounded-lg p-2 text-yellow-600 flex-shrink-0"></i>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Total Pendapatan</h4>
                                    <p class="text-2xl font-bold text-gray-900">Rp
                                        {{ number_format($pendapatanTotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
