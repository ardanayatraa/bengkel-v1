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
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a4 4 0 00-8 0v2m-2 0h12l-1 13H6L5 9h12z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium opacity-90">Transaksi Hari Ini</h4>
                                        <p class="text-2xl font-bold">{{ $transaksiTodayCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Hari Ini -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium opacity-90">Total Service Hari Ini</h4>
                                        <p class="text-2xl font-bold">Rp
                                            {{ number_format($serviceTotalToday, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Konsumen Baru Hari Ini -->
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium opacity-90">Konsumen Baru</h4>
                                        <p class="text-2xl font-bold">{{ $konsumenTodayCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pendapatan Hari Ini -->
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium opacity-90">Total Pendapatan</h4>
                                        <p class="text-2xl font-bold">Rp
                                            {{ number_format($pendapatanTodayTotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Transaksi Hari Ini -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a4 4 0 00-8 0v2m-2 0h12l-1 13H6L5 9h12z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium opacity-90">Transaksi Hari Ini</h4>
                                        <p class="text-2xl font-bold">{{ $transaksiTodayCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Hari Ini -->
                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium opacity-90">Total Service Hari Ini</h4>
                                        <p class="text-2xl font-bold">Rp
                                            {{ number_format($serviceTotalToday, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barang Masuk Hari Ini -->
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V9a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0011.586 5H8a2 2 0 00-2 2v4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium opacity-90">Barang Masuk Hari Ini</h4>
                                        <p class="text-2xl font-bold">{{ $barangMasukTodayCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pendapatan Hari Ini -->
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium opacity-90">Total Pendapatan</h4>
                                        <p class="text-2xl font-bold">Rp
                                            {{ number_format($pendapatanTodayTotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a4 4 0 00-8 0v2m-2 0h12l-1 13H6L5 9h12z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Transaksi</h4>
                                        <p class="text-2xl font-bold text-gray-900">{{ $transaksiCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Konsumen -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5.121 17.804A10.97 10.97 0 0112 15c2.21 0 4.253.64 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Konsumen</h4>
                                        <p class="text-2xl font-bold text-gray-900">{{ $konsumenCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Barang -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Barang</h4>
                                        <p class="text-2xl font-bold text-gray-900">{{ $barangCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Pendapatan -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Pendapatan</h4>
                                        <p class="text-2xl font-bold text-gray-900">Rp
                                            {{ number_format($pendapatanTotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Total Supplier -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V9a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0011.586 5H8a2 2 0 00-2 2v4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Supplier</h4>
                                        <p class="text-2xl font-bold text-gray-900">{{ $supplierCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Barang Masuk -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Barang Masuk</h4>
                                        <p class="text-2xl font-bold text-gray-900">{{ $barangMasukCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total User -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5.121 17.804A10.97 10.97 0 0112 15c2.21 0 4.253.64 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total User</h4>
                                        <p class="text-2xl font-bold text-gray-900">{{ $userCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Konsumen -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5.121 17.804A10.97 10.97 0 0112 15c2.21 0 4.253.64 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Konsumen</h4>
                                        <p class="text-2xl font-bold text-gray-900">{{ $konsumenCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Barang -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Barang</h4>
                                        <p class="text-2xl font-bold text-gray-900">{{ $barangCount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Pendapatan -->
                        <div class="bg-white border shadow-sm rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-500">Total Pendapatan</h4>
                                        <p class="text-2xl font-bold text-gray-900">Rp
                                            {{ number_format($pendapatanTotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
