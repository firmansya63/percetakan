<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Transaksi Cashflow
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('cashflow.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                            <input id="tanggal" name="tanggal" type="date" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required
                                class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                            @error('tanggal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="tipe" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe</label>
                            <select id="tipe" name="tipe" required class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                                <option value="masuk" @selected(old('tipe') === 'masuk')>Masuk</option>
                                <option value="keluar" @selected(old('tipe', 'keluar') === 'keluar')>Keluar</option>
                            </select>
                            @error('tipe') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                            <input id="kategori" name="kategori" type="text" list="kategori-options" value="{{ old('kategori') }}" required maxlength="100"
                                placeholder="Contoh: Pembelian Bahan, Gaji, Operasional"
                                class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                            <datalist id="kategori-options">
                                @foreach($kategoriOptions as $opt)
                                    <option value="{{ $opt }}"></option>
                                @endforeach
                            </datalist>
                            @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah (Rp)</label>
                            <input id="jumlah" name="jumlah" type="number" step="0.01" min="0.01" value="{{ old('jumlah') }}" required
                                class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                            @error('jumlah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran (opsional)</label>
                        <select id="metode_pembayaran" name="metode_pembayaran" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                            <option value="">-</option>
                            <option value="tunai" @selected(old('metode_pembayaran') === 'tunai')>Tunai</option>
                            <option value="transfer" @selected(old('metode_pembayaran') === 'transfer')>Transfer</option>
                            <option value="qris" @selected(old('metode_pembayaran') === 'qris')>QRIS</option>
                        </select>
                        @error('metode_pembayaran') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('cashflow.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
