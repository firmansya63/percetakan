<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Input Pengeluaran
        </h2>
    </x-slot>

    <div class="pt-2 pb-6 sm:pt-4 sm:pb-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-9 h-9 rounded-xl bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Input Pengeluaran</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Catat transaksi pengeluaran / kas kecil</p>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('cashflow.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="tipe" value="keluar">

                    @if($errors->any())
                        <div class="p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Tanggal <span class="text-rose-500">*</span>
                            </label>
                            <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                        </div>
                        <div>
                            <label for="jumlah" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Jumlah (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-semibold text-gray-400 pointer-events-none">Rp</span>
                                <input id="jumlah" name="jumlah" type="number" step="0.01" min="0.01" value="{{ old('jumlah') }}" required placeholder="0"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm font-bold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="kategori" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <input id="kategori" name="kategori" type="text" list="kategori-options" value="{{ old('kategori') }}" required maxlength="100"
                                placeholder="Contoh: Kas Kecil, Operasional"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                            <datalist id="kategori-options">
                                @foreach($kategoriList as $opt)
                                    <option value="{{ $opt }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label for="metode_pembayaran" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Metode Pembayaran
                            </label>
                            <select id="metode_pembayaran" name="metode_pembayaran"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">
                                <option value="">-</option>
                                <option value="tunai">💵 Tunai</option>
                                <option value="transfer">🏦 Transfer</option>
                                <option value="qris">📱 QRIS</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                            Keterangan
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="3" maxlength="1000" placeholder="Contoh: Beli tinta printer, bayar listrik..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-sm shadow-lg shadow-red-500/25 hover:shadow-red-500/35 transition-all">
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
