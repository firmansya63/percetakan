<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Bahan Baru</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Daftarkan bahan baku atau material cetak ke dalam sistem</p>
            </div>
            <a href="{{ route('bahan.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Bahan
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl mx-auto">

        {{-- Error Alerts --}}
        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 shadow-sm">
                <div class="flex items-center gap-2 mb-2 font-bold text-sm">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    Mohon periksa kesalahan input berikut:
                </div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Box --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <form action="{{ route('bahan.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Header Card Info --}}
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-700/60">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Spesifikasi & Harga Bahan</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Atur harga modal, harga jual untuk kalkulasi otomatis pesanan, dan kontrol stok</p>
                    </div>
                </div>

                {{-- Grid Input 1: Nama, Jenis & Satuan --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    {{-- Nama Bahan --}}
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="nama_bahan">
                            Nama Bahan Cetak <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            id="nama_bahan" 
                            type="text" 
                            name="nama_bahan" 
                            value="{{ old('nama_bahan') }}" 
                            placeholder="Contoh: Flexi Standard 280gr / Art Paper 150gr" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                    </div>

                    {{-- Jenis Cetakan --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="jenis_cetakan">
                            Jenis Cetakan <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="jenis_cetakan" 
                            name="jenis_cetakan" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm font-medium text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                            <option value="banner" {{ old('jenis_cetakan') === 'banner' ? 'selected' : '' }}>🚩 Banner (Spanduk / Baliho - hitung m²)</option>
                            <option value="kertas" {{ old('jenis_cetakan') === 'kertas' ? 'selected' : '' }}>📄 Kertas (Brosur / Undangan - hitung qty)</option>
                            <option value="lainnya" {{ old('jenis_cetakan', 'lainnya') === 'lainnya' ? 'selected' : '' }}>📦 Lainnya / Custom</option>
                        </select>
                    </div>

                    {{-- Satuan --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="satuan">
                            Satuan Ukuran <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="satuan" 
                            name="satuan" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm font-medium text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                            <option value="m2" {{ old('satuan') === 'm2' ? 'selected' : '' }}>m² (Meter Persegi)</option>
                            <option value="meter" {{ old('satuan') === 'meter' ? 'selected' : '' }}>Meter</option>
                            <option value="lembar" {{ old('satuan') === 'lembar' ? 'selected' : '' }}>Lembar</option>
                            <option value="rim" {{ old('satuan') === 'rim' ? 'selected' : '' }}>Rim</option>
                        </select>
                    </div>

                    {{-- Stok Awal --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="stok">
                            Stok Awal <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            id="stok" 
                            type="number" 
                            name="stok" 
                            value="{{ old('stok', 0) }}" 
                            required 
                            min="0" 
                            step="0.01" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                    </div>
                </div>

                {{-- Grid Input 2: Harga Dasar & Harga Jual --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 p-5 rounded-2xl bg-gray-50/70 dark:bg-gray-750 border border-gray-100 dark:border-gray-700">
                    {{-- Harga Dasar --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="harga_dasar">
                            Harga Dasar / Modal (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-semibold text-gray-400 pointer-events-none">Rp</span>
                            <input 
                                id="harga_dasar" 
                                type="number" 
                                name="harga_dasar" 
                                value="{{ old('harga_dasar', 0) }}" 
                                required 
                                min="0" 
                                step="0.01" 
                                placeholder="0" 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>
                        <span class="text-[11px] text-gray-400 mt-1 block">Biaya modal pokok bahan per satuan.</span>
                    </div>

                    {{-- Harga Jual --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="harga_jual">
                            Harga Jual Konsumen (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-bold text-emerald-600 dark:text-emerald-400 pointer-events-none">Rp</span>
                            <input 
                                id="harga_jual" 
                                type="number" 
                                name="harga_jual" 
                                value="{{ old('harga_jual') }}" 
                                required 
                                min="0" 
                                step="0.01" 
                                placeholder="0" 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-black text-emerald-600 dark:text-emerald-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>
                        <span class="text-[11px] text-gray-400 mt-1 block">Harga dasar yang dipakai saat kalkulasi input order.</span>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="keterangan">
                        Keterangan / Spesifikasi Tambahan
                    </label>
                    <textarea 
                        id="keterangan" 
                        name="keterangan" 
                        rows="3" 
                        placeholder="Contoh: Cocok untuk outdoor tahan air, ketebalan 0.28mm, lebar roll 3.2m..." 
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                    >{{ old('keterangan') }}</textarea>
                </div>

                {{-- Status Aktif --}}
                <div class="p-4 rounded-xl bg-gray-50/50 dark:bg-gray-750 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Status Aktif Bahan</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jika aktif, bahan akan langsung muncul pada pilihan menu input order.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input id="is_active" type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 py-3 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 transition-all"
                    >
                        Simpan Bahan
                    </button>
                    <a 
                        href="{{ route('bahan.index') }}" 
                        class="py-3 px-6 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold text-sm transition"
                    >
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
