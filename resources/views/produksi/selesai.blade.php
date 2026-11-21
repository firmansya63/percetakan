<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Riwayat Pesanan Selesai Dicetak</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Daftar arsip produksi cetak yang telah rampung dikerjakan</p>
            </div>
            <a href="{{ route('produksi.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Antrean Produksi
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Table Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-100 dark:border-gray-700/60 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="py-4 px-5">Waktu Selesai</th>
                            <th class="py-4 px-5">Nota & Pelanggan</th>
                            <th class="py-4 px-5">Rincian Item Cetakan</th>
                            <th class="py-4 px-5 text-center">Status</th>
                            <th class="py-4 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                        @forelse($pesananSelesai as $pesanan)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors">
                                
                                {{-- Waktu Selesai --}}
                                <td class="py-4 px-5 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    <div class="font-bold text-gray-800 dark:text-gray-200">
                                        {{ $pesanan->updated_at->format('d M Y') }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">
                                        Pukul {{ $pesanan->updated_at->format('H:i') }} WIB
                                    </div>
                                </td>

                                {{-- Nota & Pelanggan --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <a href="{{ route('produksi.show', $pesanan) }}" class="font-bold text-gray-900 dark:text-gray-100 hover:text-emerald-600 dark:hover:text-emerald-400 transition">
                                                {{ $pesanan->nomor_nota }}
                                            </a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Item Cetakan --}}
                                <td class="py-4 px-5">
                                    <div class="space-y-1 text-xs">
                                        @foreach($pesanan->detailPesanans as $item)
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $item->nama_item }}</span>
                                                <span class="text-gray-400">({{ $item->qty }} pcs)</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="py-4 px-5 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai Cetak
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="py-4 px-5 whitespace-nowrap text-center">
                                    <a href="{{ route('produksi.show', $pesanan) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-200 shadow-sm transition">
                                        Lihat SPK
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 px-5 text-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-700/50 text-gray-400 mx-auto flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200">Belum ada riwayat pesanan selesai</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                                        Pesanan yang telah selesai dikerjakan operator akan diarsipkan secara rapi di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pesananSelesai->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-800">
                    {{ $pesananSelesai->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
