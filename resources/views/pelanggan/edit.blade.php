<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Data Pelanggan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form action="{{ route('pelanggan.update', $pelanggan) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nama Pelanggan *</label>
                        <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required class="w-full text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">No. Telepon / WhatsApp</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $pelanggan->telepon) }}" class="w-full text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" class="w-full text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full text-sm border-gray-300 rounded-md">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Catatan</label>
                        <textarea name="catatan" rows="2" class="w-full text-sm border-gray-300 rounded-md">{{ old('catatan', $pelanggan->catatan) }}</textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('pelanggan.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded text-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
