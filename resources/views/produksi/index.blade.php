<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Antrean Produksi & Workshop</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Pantau alur pengerjaan cetak, SPK teknis, dan kesiapan berkas operator</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('produksi.selesai') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-750 shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Selesai Cetak
                </a>
            </div>
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

        {{-- Kanban Board Columns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Kolom 1: Siap Cetak --}}
            <div class="bg-gray-50/80 dark:bg-gray-800/60 p-5 rounded-2xl border border-gray-200/80 dark:border-gray-700/80 flex flex-col">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-blue-500 ring-4 ring-blue-500/20"></span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Siap Cetak</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                            {{ $pesananSiapCetak->count() }} Order
                        </span>
                    </div>
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">Menunggu Mesin</span>
                </div>

                <div class="space-y-4 flex-1">
                    @forelse($pesananSiapCetak as $pesanan)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/70 hover:shadow-md transition">
                            {{-- Header Card --}}
                            <div class="flex justify-between items-start gap-2 mb-2.5">
                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                                        {{ $pesanan->nomor_nota }}
                                    </span>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">
                                        {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}
                                    </h4>
                                </div>
                                <span class="text-[11px] px-2 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-medium">
                                    {{ $pesanan->tanggal_pesanan->format('d M Y') }}
                                </span>
                            </div>

                            {{-- Item Breakdown --}}
                            <div class="space-y-2 mb-4 p-3 rounded-xl bg-gray-50/80 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700/50 text-xs">
                                @foreach($pesanan->detailPesanans as $item)
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-800 dark:text-gray-200 truncate">
                                                {{ $item->nama_item }}
                                            </p>
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                                {{ $item->bahan->nama_bahan ?? 'Jasa' }}
                                                @if($item->panjang && $item->lebar)
                                                    &bull; <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $item->panjang }}m × {{ $item->lebar }}m</span>
                                                @endif
                                            </p>
                                        </div>
                                        <span class="font-bold px-2 py-0.5 rounded bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 shrink-0">
                                            {{ $item->qty }} pcs
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Action Footer --}}
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700/60">
                                <a href="{{ route('produksi.show', $pesanan) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    Buka SPK & Desain
                                </a>

                                <form action="{{ route('pesanan.update-status', $pesanan) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="proses_cetak">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-sm shadow-amber-500/25 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                        </svg>
                                        Mulai Cetak
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 px-4 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 mx-auto flex items-center justify-center mb-2.5">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.25 7.034V3.375" />
                                </svg>
                            </div>
                            <h5 class="text-sm font-bold text-gray-800 dark:text-gray-200">Tidak ada antrean cetak</h5>
                            <p class="text-xs text-gray-400 mt-1">Semua pesanan baru berstatus siap cetak akan tampil di kolom ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Kolom 2: Sedang Proses Cetak --}}
            <div class="bg-gray-50/80 dark:bg-gray-800/60 p-5 rounded-2xl border border-gray-200/80 dark:border-gray-700/80 flex flex-col">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-full bg-amber-500 ring-4 ring-amber-500/20 animate-pulse"></span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Sedang Proses Cetak</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            {{ $pesananProsesCetak->count() }} Order
                        </span>
                    </div>
                    <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold flex items-center gap-1">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                        Di Mesin / Meja Kerja
                    </span>
                </div>

                <div class="space-y-4 flex-1">
                    @forelse($pesananProsesCetak as $pesanan)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-amber-200/80 dark:border-amber-900/50 hover:shadow-md transition">
                            {{-- Header Card --}}
                            <div class="flex justify-between items-start gap-2 mb-2.5">
                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                                        {{ $pesanan->nomor_nota }}
                                    </span>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">
                                        {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}
                                    </h4>
                                </div>
                                <span class="text-[11px] px-2 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 font-medium">
                                    {{ $pesanan->tanggal_pesanan->format('d M Y') }}
                                </span>
                            </div>

                            {{-- Item Breakdown --}}
                            <div class="space-y-2 mb-4 p-3 rounded-xl bg-gray-50/80 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700/50 text-xs">
                                @foreach($pesanan->detailPesanans as $item)
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-800 dark:text-gray-200 truncate">
                                                {{ $item->nama_item }}
                                            </p>
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                                {{ $item->bahan->nama_bahan ?? 'Jasa' }}
                                                @if($item->panjang && $item->lebar)
                                                    &bull; <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $item->panjang }}m × {{ $item->lebar }}m</span>
                                                @endif
                                            </p>
                                        </div>
                                        <span class="font-bold px-2 py-0.5 rounded bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 shrink-0">
                                            {{ $item->qty }} pcs
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Action Footer --}}
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700/60">
                                <a href="{{ route('produksi.show', $pesanan) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    Buka SPK & Desain
                                </a>

                                <form action="{{ route('pesanan.update-status', $pesanan) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm shadow-emerald-500/25 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        Tandai Selesai
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 px-4 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-500 mx-auto flex items-center justify-center mb-2.5">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h5 class="text-sm font-bold text-gray-800 dark:text-gray-200">Tidak ada mesin sedang berjalan</h5>
                            <p class="text-xs text-gray-400 mt-1">Mulai cetak pesanan dari kolom Siap Cetak untuk memproses.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Panel Riwayat Selesai Cetak Terkini --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 mb-5 border-b border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Riwayat Selesai Dicetak (Terbaru)</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                        {{ $pesananSelesai->count() }} Order Terakhir
                    </span>
                </div>
                <a href="{{ route('produksi.selesai') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Lihat Semua Riwayat Selesai
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($pesananSelesai as $pesanan)
                    <div class="p-4 rounded-xl bg-gray-50/70 dark:bg-gray-750 border border-gray-100 dark:border-gray-700/60 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start gap-2 mb-2">
                                <span class="font-bold text-xs text-emerald-600 dark:text-emerald-400">
                                    {{ $pesanan->nomor_nota }}
                                </span>
                                <span class="text-[11px] text-gray-400">
                                    {{ $pesanan->updated_at->format('d M H:i') }}
                                </span>
                            </div>
                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200 mb-2 truncate">
                                {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}
                            </p>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400 space-y-1 mb-3">
                                @foreach($pesanan->detailPesanans as $item)
                                    <div class="truncate">
                                        • {{ $item->nama_item }} ({{ $item->qty }} pcs)
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-200/80 dark:border-gray-700/60 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Siap Diambil
                            </span>
                            <a href="{{ route('produksi.show', $pesanan) }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                Detail SPK &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-xs text-gray-400">
                        Belum ada pesanan yang selesai dicetak.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
