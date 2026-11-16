<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Opsi Finishing
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form action="{{ route('finishing.update', $finishing) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nama Finishing *</label>
                        <input type="text" name="nama_finishing" value="{{ old('nama_finishing', $finishing->nama_finishing) }}" required class="w-full text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Harga (Rp) *</label>
                        <input type="number" name="harga" value="{{ old('harga', $finishing->harga) }}" required min="0" class="w-full text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Satuan Hitung *</label>
                        <select name="satuan_hitung" required class="w-full text-sm border-gray-300 rounded-md">
                            <option value="per_item" {{ $finishing->satuan_hitung === 'per_item' ? 'selected' : '' }}>Per Item</option>
                            <option value="per_meter" {{ $finishing->satuan_hitung === 'per_meter' ? 'selected' : '' }}>Per Meter</option>
                            <option value="per_lembar" {{ $finishing->satuan_hitung === 'per_lembar' ? 'selected' : '' }}>Per Lembar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="w-full text-sm border-gray-300 rounded-md">{{ old('keterangan', $finishing->keterangan) }}</textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $finishing->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                        <label for="is_active" class="text-sm">Status Aktif</label>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('finishing.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded text-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
