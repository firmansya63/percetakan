@php
    $linkBase = 'flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors';
    $linkActive = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300';
    $linkInactive = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100';
@endphp

<!-- Sidebar drawer (auto-hide, toggle via top bar) -->
<div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40">
    <div @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-gray-900/50"></div>
    <aside
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        @keydown.escape.window="sidebarOpen = false"
        class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto transform"
    >
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0">
                <x-application-logo class="block h-8 w-auto shrink-0 fill-current text-gray-800 dark:text-gray-200" />
                <span class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $companyProfile->name ?? config('app.name', 'Percetakan') }}</span>
            </a>
            <button @click="sidebarOpen = false" class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700" aria-label="Tutup menu">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $linkActive : $linkInactive }}">
                <span>Dashboard</span>
            </a>

            @can('view_pesanan')
            <a href="{{ route('pesanan.index') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('pesanan.*') ? $linkActive : $linkInactive }}">
                <span>Pesanan</span>
            </a>
            @endcan

            @can('view_pelanggan')
            <a href="{{ route('pelanggan.index') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('pelanggan.*') ? $linkActive : $linkInactive }}">
                <span>Pelanggan</span>
            </a>
            @endcan

            @can('view_pembayaran')
            <a href="{{ route('pembayaran.index') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('pembayaran.*') ? $linkActive : $linkInactive }}">
                <span>Pembayaran</span>
            </a>
            @endcan

            @if(auth()->check() && (auth()->user()->can('view_bahan') || auth()->user()->can('view_finishing')))
            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Bahan & Finishing</p>
                @can('view_bahan')
                <a href="{{ route('bahan.index') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('bahan.*') ? $linkActive : $linkInactive }}">
                    <span>Bahan</span>
                </a>
                @endcan
                @can('view_finishing')
                <a href="{{ route('finishing.index') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('finishing.*') ? $linkActive : $linkInactive }}">
                    <span>Finishing</span>
                </a>
                @endcan
            </div>
            @endif

            @can('view_produksi')
            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Produksi</p>
                <a href="{{ route('produksi.index') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('produksi.index') ? $linkActive : $linkInactive }}">
                    <span>Antrean Produksi</span>
                </a>
                <a href="{{ route('produksi.selesai') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('produksi.selesai') ? $linkActive : $linkInactive }}">
                    <span>Riwayat Selesai</span>
                </a>
            </div>
            @endcan

            @role('owner')
            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</p>
                <a href="{{ route('company.edit') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('company.*') ? $linkActive : $linkInactive }}">
                    <span>Profil Perusahaan</span>
                </a>
                <a href="{{ route('user.index') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('user.*') ? $linkActive : $linkInactive }}">
                    <span>User</span>
                </a>
            </div>
            @endrole

            @can('view_laporan_omzet')
            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('laporan.omzet') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('laporan.*') ? $linkActive : $linkInactive }}">
                    <span>Laporan</span>
                </a>
                @role('owner')
                <a href="{{ route('laporan.shift') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('laporan.shift') ? $linkActive : $linkInactive }}">
                    <span>Laporan Shift</span>
                </a>
                @endrole
            </div>
            @endcan

            @can('view_cashflow')
            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Keuangan</p>
                <a href="{{ route('cashflow.index') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('cashflow.*') ? $linkActive : $linkInactive }}">
                    <span>Cashflow</span>
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
                <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false" class="{{ $linkBase }} {{ request()->routeIs('profile.*') ? $linkActive : $linkInactive }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left {{ $linkBase }} {{ $linkInactive }}">Log Out</button>
                </form>
            </div>
        </div>
    </aside>
</div>
