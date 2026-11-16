<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Bahan: {{ $bahan->nama_bahan }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form action="{{ route('bahan.update', $bahan) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nama Bahan *</label>
                        <input type="text" name="nama_bahan" value="{{ old('nama_bahan', $bahan->nama_bahan) }}" required class="w-full text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Jenis Cetakan *</label>
                        <select name="jenis_cetakan" required class="w-full text-sm border-gray-300 rounded-md">
                            <option value="banner" {{ $bahan->jenis_cetakan === 'banner' ? 'selected' : '' }}>Banner (Spanduk/Baliho - hitung m2)</option>
                            <option value="kertas" {{ $bahan->jenis_cetakan === 'kertas' ? 'selected' : '' }}>Kertas (Brosur/Kartu Nama - hitung qty)</option>
                            <option value="lainnya" {{ $bahan->jenis_cetakan === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Satuan *</label>
                        <select name="satuan" required class="w-full text-sm border-gray-300 rounded-md">
                            <option value="m2" {{ $bahan->satuan === 'm2' ? 'selected' : '' }}>m2 (meter persegi)</option>
                            <option value="rim" {{ $bahan->satuan === 'rim' ? 'selected' : '' }}>Rim</option>
                            <option value="lembar" {{ $bahan->satuan === 'lembar' ? 'selected' : '' }}>Lembar</option>
                            <option value="meter" {{ $bahan->satuan === 'meter' ? 'selected' : '' }}>Meter</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Harga Dasar (Modal) *</label>
                            <input type="number" name="harga_dasar" value="{{ old('harga_dasar', $bahan->harga_dasar) }}" required min="0" class="w-full text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Harga Jual *</label>
                            <input type="number" name="harga_jual" value="{{ old('harga_jual', $bahan->harga_jual) }}" required min="0" class="w-full text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Stok Tersedia</label>
                        <input type="number" name="stok" value="{{ old('stok', $bahan->stok) }}" step="0.01" required min="0" class="w-full text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="w-full text-sm border-gray-300 rounded-md">{{ old('keterangan', $bahan->keterangan) }}</textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $bahan->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                        <label for="is_active" class="text-sm">Status Aktif</label>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('bahan.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded text-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
