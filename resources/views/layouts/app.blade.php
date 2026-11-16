<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @php
            $linkBase = 'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors';
            $linkActive = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300';
            $linkInactive = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100';
        @endphp

        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Desktop Sidebar -->
            @include('layouts.navigation')

            <!-- Mobile top bar -->
            <div class="md:hidden sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between h-16 px-4">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <x-application-logo class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-200" />
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ config('app.name', 'Percetakan') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile sidebar overlay -->
            <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 md:hidden">
                <div @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/50"></div>
                <aside class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
                    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <x-application-logo class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-200" />
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ config('app.name', 'Percetakan') }}</span>
                        </a>
                        <button @click="sidebarOpen = false" class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700">
                            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <nav class="px-3 py-4 space-y-1">
                        <a href="{{ route('dashboard') }}" class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $linkActive : $linkInactive }}">Dashboard</a>

                        @can('view_pesanan')
                        <a href="{{ route('pesanan.index') }}" class="{{ $linkBase }} {{ request()->routeIs('pesanan.*') ? $linkActive : $linkInactive }}">Pesanan</a>
                        @endcan

                        @can('view_pelanggan')
                        <a href="{{ route('pelanggan.index') }}" class="{{ $linkBase }} {{ request()->routeIs('pelanggan.*') ? $linkActive : $linkInactive }}">Pelanggan</a>
                        @endcan

                        @can('view_pembayaran')
                        <a href="{{ route('pembayaran.index') }}" class="{{ $linkBase }} {{ request()->routeIs('pembayaran.*') ? $linkActive : $linkInactive }}">Pembayaran</a>
                        @endcan

                        @if(auth()->check() && (auth()->user()->can('view_bahan') || auth()->user()->can('view_finishing')))
                        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Bahan & Finishing</p>
                            @can('view_bahan')
                            <a href="{{ route('bahan.index') }}" class="{{ $linkBase }} {{ request()->routeIs('bahan.*') ? $linkActive : $linkInactive }}">Bahan</a>
                            @endcan
                            @can('view_finishing')
                            <a href="{{ route('finishing.index') }}" class="{{ $linkBase }} {{ request()->routeIs('finishing.*') ? $linkActive : $linkInactive }}">Finishing</a>
                            @endcan
                        </div>
                        @endif

                        @can('view_produksi')
                        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Produksi</p>
                            <a href="{{ route('produksi.index') }}" class="{{ $linkBase }} {{ request()->routeIs('produksi.index') ? $linkActive : $linkInactive }}">Antrean Produksi</a>
                            <a href="{{ route('produksi.selesai') }}" class="{{ $linkBase }} {{ request()->routeIs('produksi.selesai') ? $linkActive : $linkInactive }}">Riwayat Selesai</a>
                        </div>
                        @endcan

                        @role('owner')
                        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('user.index') }}" class="{{ $linkBase }} {{ request()->routeIs('user.*') ? $linkActive : $linkInactive }}">User</a>
                        </div>
                        @endrole

                        @can('view_laporan_omzet')
                        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('laporan.omzet') }}" class="{{ $linkBase }} {{ request()->routeIs('laporan.*') ? $linkActive : $linkInactive }}">Laporan</a>
                        </div>
                        @endcan
                    </nav>

                    <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                        <div class="mb-3">
                            <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="space-y-1">
                            <a href="{{ route('profile.edit') }}" class="{{ $linkBase }} {{ request()->routeIs('profile.*') ? $linkActive : $linkInactive }}">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left {{ $linkBase }} {{ $linkInactive }}">Log Out</button>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- Main content -->
            <div class="md:pl-64 flex flex-col min-h-screen">
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
