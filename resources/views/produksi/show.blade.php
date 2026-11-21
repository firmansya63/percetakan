<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Surat Perintah Kerja (SPK)</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Lembar instruksi teknis produksi workshop dan spesifikasi cetak</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('produksi.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-750 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Antrean Produksi
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 text-white text-sm font-bold shadow-md shadow-gray-900/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.25 7.034V3.375" />
                    </svg>
                    Cetak Lembar SPK
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-5xl mx-auto">

        {{-- Flash Notification --}}
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 shadow-sm print:hidden">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- SPK Header & Status Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-5 border-b border-gray-100 dark:border-gray-700/60">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                        Nomor SPK / Nota
                    </span>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">
                        {{ $pesanan->nomor_nota }}
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex flex-wrap items-center gap-2">
                        <span>Pelanggan: <strong class="text-gray-800 dark:text-gray-200">{{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}</strong></span>
                        &bull;
                        <span>Tgl Pesan: <strong>{{ $pesanan->tanggal_pesanan ? $pesanan->tanggal_pesanan->format('d M Y') : '-' }}</strong></span>
                        @if($pesanan->tanggal_ambil)
                            &bull;
                            <span class="text-amber-600 dark:text-amber-400 font-semibold">Estimasi Ambil: {{ $pesanan->tanggal_ambil->format('d M Y') }}</span>
                        @endif
                    </p>
                </div>

                {{-- Status Update Form (Hidden on Print) --}}
                <div class="print:hidden w-full md:w-auto">
                    <form action="{{ route('pesanan.update-status', $pesanan) }}" method="POST" class="flex items-center gap-2 bg-gray-50 dark:bg-gray-750 p-2 rounded-2xl border border-gray-200 dark:border-gray-700">
                        @csrf
                        @method('PUT')
                        <label class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 pl-2">Status:</label>
                        <select name="status" class="px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-xs font-bold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="siap_cetak" {{ $pesanan->status === 'siap_cetak' ? 'selected' : '' }}>Siap Cetak</option>
                            <option value="proses_cetak" {{ $pesanan->status === 'proses_cetak' ? 'selected' : '' }}>Proses Cetak</option>
                            <option value="selesai" {{ $pesanan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <button type="submit" class="px-4 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                            Update
                        </button>
                    </form>
                </div>
            </div>

            {{-- Catatan Pesanan --}}
            @if($pesanan->catatan)
                <div class="mt-4 p-4 rounded-xl bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/60 text-xs">
                    <span class="font-bold text-amber-800 dark:text-amber-300 block mb-1">
                        ⚠️ Catatan Khusus untuk Operator:
                    </span>
                    <p class="text-amber-900 dark:text-amber-200 font-medium whitespace-pre-line">{{ $pesanan->catatan }}</p>
                </div>
            @endif
        </div>

        {{-- Technical Specifications Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-100 dark:border-gray-700/60 space-y-5">
            <div class="flex items-center gap-2 pb-3 border-b border-gray-100 dark:border-gray-700/60">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Rincian Spesifikasi Item Cetak</h3>
            </div>

            <div class="space-y-5">
                @foreach($pesanan->detailPesanans as $index => $item)
                    <div class="p-5 rounded-2xl bg-gray-50/70 dark:bg-gray-750 border border-gray-200 dark:border-gray-700/70 space-y-4">
                        {{-- Item Top Bar --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                    Item #{{ $index + 1 }}
                                </span>
                                <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ $item->nama_item }}
                                </h4>
                            </div>

                            {{-- File Download Button --}}
                            <div class="print:hidden">
                                @if($item->file_desain)
                                    <a href="{{ route('produksi.download', $item) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm shadow-indigo-500/25 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Unduh File Desain
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-400 text-xs font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        Tanpa File Desain
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Technical Specs Grid --}}
                        @if($item->is_jasa_design)
                            <div class="p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                                Item ini berupa pekerjaan jasa desain dan tidak memerlukan proses cetak mesin.
                            </div>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                                <div class="p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Bahan Cetak</span>
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $item->bahan->nama_bahan ?? '-' }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Jenis Cetakan</span>
                                    <span class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ ucfirst($item->bahan->jenis_cetakan ?? '-') }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Ukuran (P × L)</span>
                                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                        @if($item->panjang && $item->lebar)
                                            {{ $item->panjang }}m × {{ $item->lebar }}m
                                        @else
                                            Standar ({{ $item->bahan->satuan ?? 'pcs' }})
                                        @endif
                                    </span>
                                </div>
                                <div class="p-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Jumlah Cetak</span>
                                    <span class="text-base font-black text-emerald-600 dark:text-emerald-400">
                                        {{ $item->qty }} {{ $item->bahan->satuan ?? 'pcs' }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        {{-- Finishing Details --}}
                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700/60">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1.5">
                                Instruksi Finishing:
                            </span>
                            @if($item->detailFinishings && $item->detailFinishings->count() > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($item->detailFinishings as $fin)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 text-purple-700 dark:text-purple-300 text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            {{ $fin->finishing->nama_finishing }} (Qty: {{ $fin->qty }})
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Tanpa finishing khusus (potong standar).</span>
                            @endif
                        </div>

                        {{-- Catatan Item --}}
                        @if($item->catatan)
                            <div class="text-xs p-3 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200">
                                <strong>Catatan Khusus Item:</strong> {{ $item->catatan }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Bottom Action (Hidden on Print) --}}
            <div class="pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between print:hidden">
                <a href="{{ route('produksi.index') }}" class="py-2.5 px-5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold text-xs transition">
                    &larr; Kembali ke Antrean
                </a>
                <button onclick="window.print()" class="py-2.5 px-5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white font-bold text-xs shadow-md transition">
                    Cetak Lembar Kerja (SPK)
                </button>
            </div>
        </div>

    </div>
</x-app-layout>
