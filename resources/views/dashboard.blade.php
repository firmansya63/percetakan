<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Ringkasan aktivitas dan operasional percetakan hari ini</p>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ ($canViewOmzet ?? false) ? 'xl:grid-cols-4' : 'xl:grid-cols-3' }} gap-5">

            {{-- Total Pesanan --}}
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pesanan</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ number_format($totalPesanan ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium mt-1">Keseluruhan order masuk</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/25 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pesanan Hari Ini --}}
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pesanan Hari Ini</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ number_format($pesananHariIni ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1">Dibuat pada hari ini</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/25 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Sedang Proses Cetak --}}
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Sedang Proses</p>
                        <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ number_format($pesananProses ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mt-1">Dalam antrean mesin cetak</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/25 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.25 7.034V3.375" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Omzet (Lunas) --}}
            @if($canViewOmzet ?? false)
            <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 pr-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Omzet</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mt-2 truncate">Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-xs text-purple-600 dark:text-purple-400 font-medium mt-1">Status pembayaran lunas</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/25 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Quick Actions & Shortcuts --}}
        <div class="rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-6 text-white shadow-lg shadow-blue-500/15">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold">Akses Cepat Operasional</h2>
                    <p class="text-blue-100 text-sm mt-1">Mulai transaksi baru atau kelola alur kerja cetak dengan sekali klik.</p>
                </div>
                <div class="flex flex-wrap gap-2.5">
                    @can('view_pesanan')
                    <a href="{{ route('pesanan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 hover:bg-blue-50 font-semibold text-sm rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Buat Pesanan
                    </a>
                    @endcan

                    @can('view_pelanggan')
                    <a href="{{ route('pelanggan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white font-semibold text-sm rounded-xl backdrop-blur-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Tambah Pelanggan
                    </a>
                    @endcan

                    @can('view_produksi')
                    <a href="{{ route('produksi.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white font-semibold text-sm rounded-xl backdrop-blur-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                        </svg>
                        Antrean Cetak
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Main 2-column Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Pesanan Terbaru (2 cols on lg) --}}
            <div class="lg:col-span-2 rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar transaksi cetak paling baru masuk</p>
                    </div>
                    @can('view_pesanan')
                    <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 hover:underline">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                    @endcan
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($pesananTerbaru as $pesanan)
                        @php
                            $status = strtolower($pesanan->status ?? '');
                            $statusClasses = [
                                'menunggu_desain' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                'siap_cetak'      => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                'proses_cetak'    => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                'finishing'       => 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                'selesai'         => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                'diambil'         => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600',
                                'batal'           => 'bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                            ];
                            $badgeClass = $statusClasses[$status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600';
                        @endphp
                        <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('pesanan.show', $pesanan) }}" class="font-bold text-sm text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 truncate">
                                            {{ $pesanan->nomor_nota }}
                                        </a>
                                        @if($pesanan->status_pembayaran)
                                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $pesanan->status_pembayaran === 'lunas' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                                {{ ucfirst($pesanan->status_pembayaran) }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                        {{ $pesanan->pelanggan?->nama_pelanggan ?? 'Umum / Walk-in' }} &bull; {{ $pesanan->tanggal_pesanan?->format('d M Y') ?? 'Tanggal tidak diketahui' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-3 sm:shrink-0">
                                @if(isset($pesanan->total_harga))
                                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </span>
                                @endif
                                <span class="text-xs font-semibold px-3 py-1 rounded-full border {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-400 mx-auto flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Belum ada pesanan</p>
                            <p class="text-xs text-gray-400 mt-1">Transaksi pesanan yang baru dibuat akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Ringkasan Status Pesanan (1 col on lg) --}}
            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700/60 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Status Pesanan</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sebaran status seluruh order</p>
                        </div>
                        <span class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                            </svg>
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse($pesananStatus as $status)
                            @php
                                $statusName = strtolower($status->status ?? '');
                                $palette = match($statusName) {
                                    'menunggu_desain' => ['dot' => 'bg-amber-500', 'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200'],
                                    'siap_cetak'      => ['dot' => 'bg-blue-500', 'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200'],
                                    'proses_cetak'    => ['dot' => 'bg-indigo-500', 'badge' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200'],
                                    'finishing'       => ['dot' => 'bg-purple-500', 'badge' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-200'],
                                    'selesai'         => ['dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200'],
                                    'diambil'         => ['dot' => 'bg-gray-500', 'badge' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'],
                                    'batal'           => ['dot' => 'bg-rose-500', 'badge' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200'],
                                    default           => ['dot' => 'bg-blue-500', 'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200'],
                                };
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/80 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $palette['dot'] }} shrink-0"></span>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 truncate">
                                        {{ ucfirst(str_replace('_', ' ', $status->status)) }}
                                    </span>
                                </div>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $palette['badge'] }}">
                                    {{ $status->total }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-6">Tidak ada data status</p>
                        @endforelse
                    </div>
                </div>

                {{-- Status Info / Help Note --}}
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60">
                    <div class="flex items-start gap-2.5 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>Status pesanan akan terbarui otomatis saat operator memproses tahapan produksi.</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
