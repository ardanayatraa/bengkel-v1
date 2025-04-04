<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Adds the Core Table Styles -->
    @rappasoftTableStyles
    <!-- Adds any relevant Third-Party Styles (Used for DateRangeFilter (Flatpickr) and NumberRangeFilter) -->
    @rappasoftTableThirdPartyStyles
    <!-- Styles -->
    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="font-poppins antialiased">

    <!-- Top Navbar -->
    <nav class="bg-red-800 text-white  shadow-sm border-r px-4 py-4 fixed top-0 left-0 w-full z-50">
        <div class="flex justify-between items-center">
            <!-- Logo and mobile menu button -->
            <div class="flex items-center">
                <button onclick="toggleMobileMenu()" class="mr-2 md:hidden text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="font-semibold text-xl">Ari ShockBreaker</div>
            </div>

            <!-- Admin greeting and logout -->
            <div class="flex items-center space-x-4">
                <span class="text-white font-medium">Halo Admin</span>
                <button class="text-white hover:text-red-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden flex-col mt-2 space-y-1 md:hidden">
            <a href="#" class="py-2 px-4 text-white font-medium flex items-center justify-between">
                <span>Dashboard</span>
                <span class="bg-white text-red-800 text-xs px-2 py-1 rounded-full">Active</span>
            </a>
            <a href="#" class="py-2 px-4 text-white hover:bg-red-700/30">Profile</a>
            <a href="#" class="py-2 px-4 text-white hover:bg-red-700/30">Settings</a>
        </div>
    </nav>

    <div class="flex flex-col md:flex-row ">
        <!-- Sidebar - hidden on mobile by default -->
        <aside id="sidebar"
            class="hidden pt-16 md:block w-full md:w-64 bg-white shadow-sm p-2 md:min-h-screen border-r">
            <ul class="space-y-1">
                <div class="p-6 rounded-2xl mt-2 text-center mb-8 shadow bg-white dark:bg-gray-900">
                    <h2 class="text-sm font-semibold text-gray-800 dark:text-white">Ari Shockbreaker Motor</h2>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                        <span id="tanggal"></span>
                    </p>
                </div>

                <script>
                    document.getElementById('tanggal').innerText = new Date().toLocaleDateString('id-ID', {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                </script>

                <ul class="space-y-2">

                    {{-- Dashboard --}}
                    <li class="relative">
                        <a href="/dashboard"
                            class="flex items-center px-4 py-3 {{ request()->is('dashboard') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('dashboard') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('dashboard') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <script>
                        function toggleDropdown(id) {
                            const dropdown = document.getElementById(id);
                            const icon = document.getElementById(id + 'Icon');

                            dropdown.classList.toggle('hidden');
                            icon.classList.toggle('rotate-180');
                        }
                    </script>

                    {{-- Data Barang (Group) --}}
                    <li class="relative">
                        <button type="button"
                            class="flex items-center w-full px-4 py-3 {{ request()->is('barang*') || request()->is('trx-barang-masuk*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10"
                            onclick="toggleDropdown('barangDropdown')">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('barang*') || request()->is('trx-barang-masuk*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('barang*') || request()->is('trx-barang-masuk*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <span class="font-medium flex-1 text-left">Data Barang</span>
                            <svg class="w-4 h-4 ml-auto transition-transform duration-300" id="barangDropdownIcon"
                                :class="open ? 'rotate-180' : 'rotate-0'" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <ul id="barangDropdown"
                            class="mt-2 space-y-1 pl-14 {{ request()->is('barang*') || request()->is('trx-barang-masuk*') ? '' : 'hidden' }}">
                            <li>
                                <a href="/barang"
                                    class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('barang*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                    </svg>
                                    Barang
                                </a>
                            </li>
                            <li>
                                <a href="/trx-barang-masuk"
                                    class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('trx-barang-masuk*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                    Trx Barang Masuk
                                </a>
                        </ul>
                    </li>


                    {{-- Jasa --}}
                    <li class="relative">
                        <a href="/jasa"
                            class="flex items-center px-4 py-3 {{ request()->is('jasa*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('jasa*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('jasa*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                            <span class="font-medium">Jasa</span>
                        </a>
                    </li>

                    {{-- Kategori --}}
                    <li class="relative">
                        <a href="/kategori"
                            class="flex items-center px-4 py-3 {{ request()->is('kategori*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('kategori*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('kategori*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <span class="font-medium">Kategori</span>
                        </a>
                    </li>

                    {{-- Konsumen --}}
                    <li class="relative">
                        <a href="/konsumen"
                            class="flex items-center px-4 py-3 {{ request()->is('konsumen*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('konsumen*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('konsumen*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A10.97 10.97 0 0112 15c2.21 0 4.253.64 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="font-medium">Konsumen</span>
                        </a>
                    </li>

                    {{-- Point --}}
                    <li class="relative">
                        <a href="/point"
                            class="flex items-center px-4 py-3 {{ request()->is('point*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('point*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('point*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.358 4.183a1 1 0 00.95.69h4.396c.969 0 1.371 1.24.588 1.81l-3.561 2.587a1 1 0 00-.364 1.118l1.358 4.183c.3.921-.755 1.688-1.538 1.118l-3.561-2.587a1 1 0 00-1.176 0l-3.561 2.587c-.783.57-1.838-.197-1.538-1.118l1.358-4.183a1 1 0 00-.364-1.118L2.707 9.61c-.783-.57-.38-1.81.588-1.81h4.396a1 1 0 00.95-.69l1.358-4.183z" />
                                </svg>
                            </div>
                            <span class="font-medium">Point</span>
                        </a>
                    </li>

                    {{-- Transaksi --}}
                    <li class="relative">
                        <a href="/transaksi"
                            class="flex items-center px-4 py-3 {{ request()->is('transaksi*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('transaksi*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('transaksi*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a4 4 0 00-8 0v2m-2 0h12l-1 13H6L5 9h12z" />
                                </svg>
                            </div>
                            <span class="font-medium">Transaksi</span>
                        </a>
                    </li>


                    {{-- Supplier --}}
                    <li class="relative">
                        <a href="/supplier"
                            class="flex items-center px-4 py-3 {{ request()->is('supplier*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('supplier*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('supplier*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V9a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0011.586 5H8a2 2 0 00-2 2v4" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13h14v6H5z" />
                                </svg>
                            </div>
                            <span class="font-medium">Supplier</span>
                        </a>
                    </li>

                    {{-- User --}}
                    <li class="relative">
                        <a href="/user"
                            class="flex items-center px-4 py-3 {{ request()->is('user*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('user*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('user*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A10.97 10.97 0 0112 15c2.21 0 4.253.64 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="font-medium">User</span>
                        </a>
                    </li>

                    <script>
                        function toggleDropdown(id) {
                            const dropdown = document.getElementById(id);
                            const icon = document.getElementById(id + 'Icon');

                            dropdown.classList.toggle('hidden');
                            icon.classList.toggle('rotate-180');
                        }
                    </script>

                    {{-- Laporan (Group) --}}
                    <li class="relative">
                        <button type="button"
                            class="flex items-center w-full px-4 py-3 {{ request()->is('laporan-jasa*') || request()->is('laporan-penjualan*') || request()->is('laporan-barang*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10"
                            onclick="toggleDropdown('laporanMenuDropdown')">
                            <div
                                class="p-1.5 rounded-full mr-3 {{ request()->is('laporan-jasa*') || request()->is('laporan-penjualan*') || request()->is('laporan-barang*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                <svg class="w-4 h-4 {{ request()->is('laporan-jasa*') || request()->is('laporan-penjualan*') || request()->is('laporan-barang*') ? 'text-white' : 'text-gray-800' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H3V4zm0 4h18v2H3V8zm0 4h18v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z" />
                                </svg>
                            </div>
                            <span class="font-medium flex-1 text-left">Laporan</span>
                            <svg class="w-4 h-4 ml-auto transition-transform duration-300"
                                id="laporanMenuDropdownIcon" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <ul id="laporanMenuDropdown"
                            class="mt-2 space-y-1 pl-14 {{ request()->is('laporan-jasa*') || request()->is('laporan-penjualan*') || request()->is('laporan-barang*') ? '' : 'hidden' }}">
                            <li>
                                <a href="/laporan-jasa"
                                    class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('laporan-jasa*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16h8M8 12h8m-6-8h.01M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    Laporan Jasa
                                </a>
                            </li>
                            <li>
                                <a href="/laporan-penjualan"
                                    class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('laporan-penjualan*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M9 16h6m-7-4v8m4-8v8m-6-8V4m8 4V4" />
                                    </svg>
                                    Laporan Penjualan
                                </a>
                            </li>
                            <li>
                                <a href="/laporan-barang"
                                    class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('laporan-barang*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-6h13M9 12l-2-2m0 0l-2 2m2-2v12" />
                                    </svg>
                                    Laporan Barang
                                </a>
                            </li>
                        </ul>
                    </li>



                </ul>

            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 mt-14 md:p-8">
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        red: {
                            50: '#fdf2f2',
                            100: '#f9e6e6',
                            200: '#f5c2c2',
                            300: '#e88f8f',
                            400: '#e05c5c',
                            500: '#d12a2a',
                            600: '#b91c1c',
                            700: '#991b1b',
                            800: '#7f1d1d',
                            900: '#5c1616',
                            950: '#450c0c',
                        }
                    }
                }
            }
        }
    </script>

    <script>
        // Simple toggle function for mobile menu
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('block');
        }

        // Simple toggle function for mobile navbar
        function toggleMobileNav() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('flex');
        }
    </script>
    <!-- Adds the Core Table Scripts -->
    @rappasoftTableScripts

    <!-- Adds any relevant Third-Party Scripts (e.g. Flatpickr) -->
    @rappasoftTableThirdPartyScripts
    @livewireScripts
</body>

</html>
