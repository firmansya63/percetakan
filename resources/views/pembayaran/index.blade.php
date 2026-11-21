<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Riwayat Pembayaran</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Daftar rekapan transaksi kasir, uang muka (DP), dan pelunasan</p>
            </div>
            @can('view_pesanan')
            <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700/50 shadow-sm transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                </svg>
                Kelola Pesanan
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Filter Box --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <form method="GET" action="{{ route('pembayaran.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
                
                {{-- Date Range --}}
                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Dari Tanggal</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        value="{{ request('start_date') }}" 
                        class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-xs sm:text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>
                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Sampai Tanggal</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        value="{{ request('end_date') }}" 
                        class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-xs sm:text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                </div>

                {{-- Tipe --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Tipe Bayar</label>
                    <select 
                        name="tipe" 
                        class="w-full px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-xs sm:text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                    >
                        <option value="">Semua Tipe</option>
                        <option value="dp" {{ request('tipe') === 'dp' ? 'selected' : '' }}>DP (Uang Muka)</option>
                        <option value="pelunasan" {{ request('tipe') === 'pelunasan' ? 'selected' : '' }}>Pelunasan</option>
                    </select>
                </div>

                {{-- Filter Action --}}
                <div class="lg:col-span-2 flex items-end gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 py-2 px-4 rounded-xl bg-gray-900 hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 text-white text-xs sm:text-sm font-semibold transition"
                    >
                        Filter
                    </button>
                    @if(request()->hasAny(['start_date', 'end_date', 'tipe']))
                        <a 
                            href="{{ route('pembayaran.index') }}" 
                            class="p-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition" 
                            title="Reset Filter"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-100 dark:border-gray-700/60 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="py-4 px-5">Waktu & Transaksi</th>
                            <th class="py-4 px-5">Pelanggan</th>
                            <th class="py-4 px-5">Tipe & Metode</th>
                            <th class="py-4 px-5">Kasir</th>
                            <th class="py-4 px-5 text-right">Nominal Bayar</th>
                            <th class="py-4 px-5 text-center">Status Nota</th>
                            <th class="py-4 px-5 text-center">Kwitansi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                        @forelse($pembayarans as $bayar)
                            @php
                                $isLunas = optional($bayar->pesanan)->status_pembayaran === 'lunas';
                                $tipeKey = strtolower($bayar->tipe ?? '');
                            @endphp
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors">
                                
                                {{-- Waktu & Nota --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            @if($bayar->pesanan)
                                                <a href="{{ route('pesanan.show', $bayar->pesanan) }}" class="font-bold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                                    {{ $bayar->pesanan->nomor_nota }}
                                                </a>
                                            @else
                                                <span class="font-bold text-gray-400">-</span>
                                            @endif
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                                {{ $bayar->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Pelanggan --}}
                                <td class="py-4 px-5">
                                    <div class="font-medium text-gray-800 dark:text-gray-200">
                                        {{ $bayar->pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}
                                    </div>
                                    @if(optional(optional($bayar->pesanan)->pelanggan)->telepon)
                                        <div class="text-[11px] text-gray-400">
                                            {{ $bayar->pesanan->pelanggan->telepon }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Tipe & Metode --}}
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $tipeKey === 'dp' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' }}">
                                            {{ strtoupper($bayar->tipe) }}
                                        </span>
                                        <span class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400">
                                            {{ $bayar->metode_pembayaran }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Kasir --}}
                                <td class="py-4 px-5 whitespace-nowrap text-xs text-gray-600 dark:text-gray-300">
                                    {{ $bayar->user->name ?? 'Kasir' }}
                                </td>

                                {{-- Nominal --}}
                                <td class="py-4 px-5 whitespace-nowrap text-right">
                                    <span class="font-black text-sm text-emerald-600 dark:text-emerald-400">
                                        +Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- Status Nota --}}
                                <td class="py-4 px-5 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $isLunas ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 border border-rose-200 dark:border-rose-800' }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ $isLunas ? 'LUNAS' : 'BELUM LUNAS' }}
                                    </span>
                                </td>

                                {{-- Kwitansi & Pelunasan --}}
                                <td class="py-4 px-5 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-2">
                                        {{-- Cetak Kwitansi --}}
                                        <a href="{{ route('pembayaran.receipt', $bayar) }}" target="_blank" rel="noopener"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/60 text-xs font-semibold text-gray-700 dark:text-gray-200 shadow-sm transition"
                                           title="Cetak Kwitansi Pembayaran">
                                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.25 7.034V3.375" />
                                            </svg>
                                            Kwitansi
                                        </a>

                                        {{-- Bayar Sisa jika belum lunas --}}
                                        @can('create_pembayaran')
                                            @if(!$isLunas && $bayar->pesanan)
                                                <a href="{{ route('pembayaran.create', $bayar->pesanan) }}"
                                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition"
                                                   title="Bayar / Pelunasan Sisa Nota">
                                                    Lunasi
                                                </a>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-5 text-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-700/50 text-gray-400 mx-auto flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200">Belum ada transaksi pembayaran</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                                        Semua riwayat pembayaran uang muka (DP) dan pelunasan akan tercatat otomatis di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pembayarans->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-800">
                    {{ $pembayarans->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
