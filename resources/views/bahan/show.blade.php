<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Bahan: {{ $bahan->nama_bahan }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm space-y-3">
                <p><strong>Nama Bahan:</strong> {{ $bahan->nama_bahan }}</p>
                <p><strong>Jenis Cetakan:</strong> {{ ucfirst($bahan->jenis_cetakan) }}</p>
                <p><strong>Satuan:</strong> {{ $bahan->satuan }}</p>
                <p><strong>Harga Dasar (Modal):</strong> Rp {{ number_format($bahan->harga_dasar, 0, ',', '.') }}</p>
                <p><strong>Harga Jual:</strong> Rp {{ number_format($bahan->harga_jual, 0, ',', '.') }}</p>
                <p><strong>Stok:</strong> {{ $bahan->stok }} {{ $bahan->satuan }}</p>
                <p><strong>Keterangan:</strong> {{ $bahan->keterangan ?? '-' }}</p>
                <div class="pt-4 flex gap-2">
                    <a href="{{ route('bahan.edit', $bahan) }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded">Edit</a>
                    <a href="{{ route('bahan.index') }}" class="px-4 py-2 bg-gray-500 text-white text-sm rounded">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
