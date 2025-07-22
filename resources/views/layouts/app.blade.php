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

        /* Prevent body scroll */
        body {
            overflow: hidden;
            height: 100vh;
        }

        /* Custom scrollbar for webkit browsers */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body class="font-poppins bg-gray-50">
    <div class="flex flex-col h-screen overflow-hidden">
        <!-- Top Navbar - Fixed -->
        <nav class="bg-red-800 text-white shadow-sm border-r px-4 py-4 fixed top-0 left-0 w-full z-50 flex-shrink-0">
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
                    <span class="text-white font-medium">Halo {{ auth()->user()->username }} [
                        {{ auth()->user()->level }} ]</span>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit" class="text-white hover:text-red-400 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
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

        <!-- Main Container - Below navbar -->
        <div class="flex flex-1 pt-16 overflow-hidden">
            <!-- Sidebar - Scrollable -->
            <aside id="sidebar" class="hidden md:flex md:flex-col w-64 flex-shrink-0 bg-white shadow-sm border-r">
                <!-- Sidebar content with independent scroll -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-2">
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

                        @if (auth()->user()->level == 'admin')
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
                                    <svg class="w-4 h-4 ml-auto transition-transform duration-300"
                                        id="barangDropdownIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul id="barangDropdown"
                                    class="mt-2 space-y-1 {{ request()->is('barang*') || request()->is('trx-barang-masuk*') ? '' : 'hidden' }}">
                                    <li>
                                        <a href="/barang"
                                            class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('barang*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
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
                                    </li>
                                </ul>
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


                            {{-- <li class="relative">
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
                            </li> --}}

                            {{-- Teknisi --}}
                            <li class="relative">
                                <a href="/teknisi"
                                    class="flex items-center px-4 py-3 {{ request()->is('teknisi*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                                    <div
                                        class="p-1.5 rounded-full mr-3 {{ request()->is('teknisi*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                        <svg class="w-4 h-4 {{ request()->is('teknisi*') ? 'text-white' : 'text-gray-800' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8a3 3 0 11-6 0 3 3 0 016 0zm6.364 9.364A10.97 10.97 0 0112 15c-2.21 0-4.253.64-5.879 1.804m11.243-.44A10.97 10.97 0 0112 21c-2.21 0-4.253-.64-5.879-1.804m11.243-.44A10.97 10.97 0 0112 21c2.21 0 4.253-.64 5.879-1.804m0-7.192A10.97 10.97 0 0112 15c2.21 0 4.253-.64 5.879-1.804m0-7.192A10.97 10.97 0 0112 9c2.21 0 4.253-.64 5.879-1.804m0-7.192A10.97 10.97 0 0112 .001c2.21-.001 4.253-.64 5.879-1.804" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Teknisi</span>
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
                        @endif


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
                        @if (auth()->user()->level == 'admin')
                            {{-- Gaji Teknisi --}}
                            <li class="relative">
                                <a href="/gaji-teknisi"
                                    class="flex items-center px-4 py-3 {{ request()->is('gaji-teknisi*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10">
                                    <div
                                        class="p-1.5 rounded-full mr-3 {{ request()->is('gaji-teknisi*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                        <svg class="w-4 h-4 {{ request()->is('gaji-teknisi*') ? 'text-white' : 'text-gray-800' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Gaji Teknisi</span>
                                </a>
                            </li>
                        @endif

                        {{-- Laporan (Group) --}}
                        <li class="relative">
                            <button type="button"
                                class="flex items-center w-full px-4 py-3 {{ request()->is('laporan/jasa*') || request()->is('laporan/jual-barang*') || request()->is('laporan/barang*') || request()->is('laporan/penjualan*') ? 'text-red-800 bg-white' : 'text-gray-800' }} rounded-md relative z-10"
                                onclick="toggleDropdown('laporanMenuDropdown')">
                                <div
                                    class="p-1.5 rounded-full mr-3 {{ request()->is('laporan/jasa*') || request()->is('laporan/jual-barang*') || request()->is('laporan/barang*') || request()->is('laporan/penjualan*') ? 'bg-red-700' : 'bg-gray-400' }}">
                                    <svg class="w-4 h-4 {{ request()->is('laporan/jasa*') || request()->is('laporan/jual-barang*') || request()->is('laporan/barang*') || request()->is('laporan/penjualan*') ? 'text-white' : 'text-gray-800' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
                                class="mt-2 space-y-1 {{ request()->is('laporan/jasa*') || request()->is('laporan/jual-barang*') || request()->is('laporan/barang*') || request()->is('laporan/penjualan*') ? '' : 'hidden' }}">

                                {{-- 1. Laporan Pendapatan Jasa --}}
                                <li>
                                    <a href="{{ route('laporan.jasa') }}"
                                        class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('laporan/jasa*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                        Laporan Pendapatan Jasa
                                    </a>
                                </li>

                                {{-- 2. Laporan Pendapatan Barang --}}
                                <li>
                                    <a href="{{ route('laporan.jual.barang') }}"
                                        class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('laporan/jual-barang*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Laporan Pendapatan Barang
                                    </a>
                                </li>

                                {{-- 3. Laporan Stok Barang --}}
                                <li>
                                    <a href="{{ route('laporan.barang') }}"
                                        class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('laporan/barang*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        Laporan Stok Barang
                                    </a>
                                </li>

                                {{-- 4. Laporan Penjualan --}}
                                <li>
                                    <a href="{{ route('laporan.penjualan') }}"
                                        class="flex items-center px-2 py-2 ml-12 rounded-md text-sm {{ request()->is('laporan/penjualan*') ? 'text-red-700 bg-red-100 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Laporan Penjualan
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- Main Content - Scrollable -->
            <main class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50">
                <div class="p-4 md:p-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
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
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const icon = document.getElementById(id + 'Icon');
            dropdown.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // Simple toggle function for mobile menu
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('flex');
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
