<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Proses Pembayaran</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Input transaksi pembayaran atau pelunasan nota pesanan</p>
            </div>
            <a href="{{ route('pesanan.show', $pesanan) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Detail Pesanan
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl mx-auto">

        {{-- Info Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- No Nota --}}
            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Nomor Nota</p>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mt-1">{{ $pesanan->nomor_nota }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
            </div>

            {{-- Total Tagihan --}}
            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Tagihan</p>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mt-1">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h3>
                    <p class="text-xs text-emerald-600 font-semibold mt-0.5">Sudah Masuk: Rp {{ number_format($pesanan->total_dp, 0, ',', '.') }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Sisa Tagihan --}}
            <div class="p-5 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Sisa Tagihan</p>
                    <h3 class="text-xl font-black text-rose-600 dark:text-rose-400 mt-1">Rp {{ number_format($pesanan->sisa_bayar, 0, ',', '.') }}</h3>
                    <p class="text-xs text-rose-500 font-medium mt-0.5">Harus dilunasi</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Form Box --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <form action="{{ route('pembayaran.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Tipe Pembayaran --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                            Tipe Pembayaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="tipe" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            <option value="pelunasan" selected>Pelunasan (Sisa Tagihan)</option>
                            <option value="dp">DP Tambahan / Cicilan</option>
                        </select>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                            Metode Pembayaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="metode_pembayaran" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            <option value="tunai">💵 Tunai / Cash</option>
                            <option value="transfer">🏦 Transfer Bank</option>
                            <option value="qris">📱 QRIS</option>
                        </select>
                    </div>
                </div>

                {{-- Jumlah Pembayaran --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                            Jumlah Nominal Pembayaran (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <button type="button" onclick="document.getElementById('inputJumlah').value = {{ $pesanan->sisa_bayar }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Bayar Pas Sesuai Sisa (Rp {{ number_format($pesanan->sisa_bayar, 0, ',', '.') }})
                        </button>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center font-bold text-gray-400 pointer-events-none">Rp</span>
                        <input 
                            type="number" 
                            name="jumlah" 
                            id="inputJumlah" 
                            value="{{ $pesanan->sisa_bayar }}" 
                            placeholder="0" 
                            required 
                            step="any" 
                            min="1" 
                            class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-lg font-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">
                        Jika dibayar lebih dari sisa tagihan, selisih nominal otomatis dihitung sebagai uang kembalian pada nota/kwitansi.
                    </p>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                        Catatan Transaksi (Opsional)
                    </label>
                    <textarea name="catatan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition" rows="3" placeholder="Misal: Diterima oleh kasir malam, no ref transfer: ..."></textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <button type="submit" class="flex-1 py-3 px-6 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-sm shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 transition-all">
                        Simpan Transaksi & Cetak Kwitansi
                    </button>
                    <a href="{{ route('pesanan.show', $pesanan) }}" class="py-3 px-6 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold text-sm transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
