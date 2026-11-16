<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Pelanggan: {{ $pelanggan->nama_pelanggan }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-lg mb-2">Informasi Kontak</h3>
                <p><strong>Telepon:</strong> {{ $pelanggan->telepon ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $pelanggan->email ?? '-' }}</p>
                <p><strong>Alamat:</strong> {{ $pelanggan->alamat ?? '-' }}</p>
                <div class="mt-4">
                    <a href="{{ route('pelanggan.index') }}" class="text-blue-600 text-sm hover:underline">&larr; Kembali ke Daftar Pelanggan</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
