<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Bahan Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('bahan.store') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="nama_bahan">Nama Bahan</label>
                            <input id="nama_bahan" type="text" name="nama_bahan" value="{{ old('nama_bahan') }}" required class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="jenis_cetakan">Jenis Cetakan</label>
                            <select id="jenis_cetakan" name="jenis_cetakan" required class="w-full border rounded px-3 py-2">
                                <option value="banner" {{ old('jenis_cetakan') === 'banner' ? 'selected' : '' }}>Banner</option>
                                <option value="kertas" {{ old('jenis_cetakan') === 'kertas' ? 'selected' : '' }}>Kertas</option>
                                <option value="lainnya" {{ old('jenis_cetakan', 'lainnya') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="satuan">Satuan</label>
                            <select id="satuan" name="satuan" required class="w-full border rounded px-3 py-2">
                                <option value="m2" {{ old('satuan') === 'm2' ? 'selected' : '' }}>m2</option>
                                <option value="rim" {{ old('satuan') === 'rim' ? 'selected' : '' }}>Rim</option>
                                <option value="lembar" {{ old('satuan') === 'lembar' ? 'selected' : '' }}>Lembar</option>
                                <option value="meter" {{ old('satuan') === 'meter' ? 'selected' : '' }}>Meter</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="harga_dasar">Harga Dasar (Modal)</label>
                            <input id="harga_dasar" type="number" name="harga_dasar" value="{{ old('harga_dasar', 0) }}" required min="0" step="0.01" class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="harga_jual">Harga Jual</label>
                            <input id="harga_jual" type="number" name="harga_jual" value="{{ old('harga_jual') }}" required min="0" step="0.01" class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="stok">Stok</label>
                            <input id="stok" type="number" name="stok" value="{{ old('stok', 0) }}" required min="0" step="0.01" class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="keterangan">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="3" class="w-full border rounded px-3 py-2">{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="mb-4 flex items-center gap-2">
                            <input id="is_active" type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-green-600">
                            <label for="is_active" class="text-gray-700">Status Aktif</label>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
                        <a href="{{ route('bahan.index') }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>