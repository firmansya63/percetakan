@php
    $linkBase = 'flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors';
    $linkActive = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300';
    $linkInactive = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100';
@endphp

<aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
    <!-- Logo -->
    <div class="flex items-center h-16 px-4 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <x-application-logo class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-200" />
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ config('app.name', 'Percetakan') }}</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        <a href="{{ route('dashboard') }}" class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $linkActive : $linkInactive }}">
            <span>Dashboard</span>
        </a>

        @can('view_pesanan')
        <a href="{{ route('pesanan.index') }}" class="{{ $linkBase }} {{ request()->routeIs('pesanan.*') ? $linkActive : $linkInactive }}">
            <span>Pesanan</span>
        </a>
        @endcan

        @can('view_pelanggan')
        <a href="{{ route('pelanggan.index') }}" class="{{ $linkBase }} {{ request()->routeIs('pelanggan.*') ? $linkActive : $linkInactive }}">
            <span>Pelanggan</span>
        </a>
        @endcan

        @can('view_pembayaran')
        <a href="{{ route('pembayaran.index') }}" class="{{ $linkBase }} {{ request()->routeIs('pembayaran.*') ? $linkActive : $linkInactive }}">
            <span>Pembayaran</span>
        </a>
        @endcan

        @if(auth()->check() && (auth()->user()->can('view_bahan') || auth()->user()->can('view_finishing')))
        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Bahan & Finishing</p>
            @can('view_bahan')
            <a href="{{ route('bahan.index') }}" class="{{ $linkBase }} {{ request()->routeIs('bahan.*') ? $linkActive : $linkInactive }}">
                <span>Bahan</span>
            </a>
            @endcan
            @can('view_finishing')
            <a href="{{ route('finishing.index') }}" class="{{ $linkBase }} {{ request()->routeIs('finishing.*') ? $linkActive : $linkInactive }}">
                <span>Finishing</span>
            </a>
            @endcan
        </div>
        @endif

        @can('view_produksi')
        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Produksi</p>
            <a href="{{ route('produksi.index') }}" class="{{ $linkBase }} {{ request()->routeIs('produksi.index') ? $linkActive : $linkInactive }}">
                <span>Antrean Produksi</span>
            </a>
            <a href="{{ route('produksi.selesai') }}" class="{{ $linkBase }} {{ request()->routeIs('produksi.selesai') ? $linkActive : $linkInactive }}">
                <span>Riwayat Selesai</span>
            </a>
        </div>
        @endcan

        @role('owner')
        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('user.index') }}" class="{{ $linkBase }} {{ request()->routeIs('user.*') ? $linkActive : $linkInactive }}">
                <span>User</span>
            </a>
        </div>
        @endrole

        @can('view_laporan_omzet')
        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('laporan.omzet') }}" class="{{ $linkBase }} {{ request()->routeIs('laporan.*') ? $linkActive : $linkInactive }}">
                <span>Laporan</span>
            </a>
        </div>
        @endcan
    </nav>

    <!-- User -->
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
