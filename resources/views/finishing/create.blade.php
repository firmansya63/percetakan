<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Opsi Finishing</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Daftarkan opsi finishing cetak (laminasi, mata ayam, lem, dll)</p>
            </div>
            <a href="{{ route('finishing.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar Finishing
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
            <form action="{{ route('finishing.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Header Card Info --}}
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-700/60">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Detail Opsi Finishing</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tentukan harga dan metode satuan perhitungan biaya finishing</p>
                    </div>
                </div>

                {{-- Grid Input 1: Nama Finishing --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="nama_finishing">
                        Nama Finishing <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        id="nama_finishing" 
                        type="text" 
                        name="nama_finishing" 
                        value="{{ old('nama_finishing') }}" 
                        placeholder="Contoh: Mata Ayam / Kolong Atas Bawah / Laminasi Glossy / Lem Hot Melt" 
                        required 
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                    >
                </div>

                {{-- Grid Input 2: Harga & Satuan Hitung --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 p-5 rounded-2xl bg-gray-50/70 dark:bg-gray-750 border border-gray-100 dark:border-gray-700">
                    {{-- Harga --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="harga">
                            Tarif Biaya Finishing (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-bold text-emerald-600 dark:text-emerald-400 pointer-events-none">Rp</span>
                            <input 
                                id="harga" 
                                type="number" 
                                name="harga" 
                                value="{{ old('harga') }}" 
                                required 
                                min="0" 
                                step="any" 
                                placeholder="0" 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-black text-emerald-600 dark:text-emerald-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>
                        <span class="text-[11px] text-gray-400 mt-1 block">Biaya tambahan yang dikenakan per satuan.</span>
                    </div>

                    {{-- Satuan Hitung --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="satuan_hitung">
                            Metode Satuan Hitung <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="satuan_hitung" 
                            name="satuan_hitung" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                            <option value="per_item" {{ old('satuan_hitung') === 'per_item' ? 'selected' : '' }}>🏷️ Per Item (Dihitung per pcs cetakan)</option>
                            <option value="per_meter" {{ old('satuan_hitung') === 'per_meter' ? 'selected' : '' }}>📏 Per Meter (Dihitung panjang/keliling)</option>
                            <option value="per_lembar" {{ old('satuan_hitung') === 'per_lembar' ? 'selected' : '' }}>📄 Per Lembar (Dihitung per sheet)</option>
                        </select>
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5" for="keterangan">
                        Keterangan Tambahan
                    </label>
                    <textarea 
                        id="keterangan" 
                        name="keterangan" 
                        rows="3" 
                        placeholder="Contoh: Mata ayam standar 4 sudut, atau lipat keliling dengan lem banner..." 
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                    >{{ old('keterangan') }}</textarea>
                </div>

                {{-- Status Aktif --}}
                <div class="p-4 rounded-xl bg-gray-50/50 dark:bg-gray-750 border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Status Aktif Finishing</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jika aktif, opsi finishing ini akan muncul sebagai checkbox pada form input pesanan.</p>
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
                        Simpan Opsi Finishing
                    </button>
                    <a 
                        href="{{ route('finishing.index') }}" 
                        class="py-3 px-6 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold text-sm transition"
                    >
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>
