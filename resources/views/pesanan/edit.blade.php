<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Pesanan: {{ $pesanan->nomor_nota }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form action="{{ route('pesanan.update', $pesanan) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-semibold mb-1">Status Pesanan</label>
                        <select name="status" class="w-full text-sm border-gray-300 rounded-md">
                            <option value="menunggu_desain" {{ $pesanan->status === 'menunggu_desain' ? 'selected' : '' }}>Menunggu Desain</option>
                            <option value="siap_cetak" {{ $pesanan->status === 'siap_cetak' ? 'selected' : '' }}>Siap Cetak</option>
                            <option value="proses_cetak" {{ $pesanan->status === 'proses_cetak' ? 'selected' : '' }}>Proses Cetak</option>
                            <option value="selesai" {{ $pesanan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diambil" {{ $pesanan->status === 'diambil' ? 'selected' : '' }}>Diambil</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Catatan Tambahan</label>
                        <textarea name="catatan" rows="3" class="w-full text-sm border-gray-300 rounded-md">{{ old('catatan', $pesanan->catatan) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('pesanan.show', $pesanan) }}" class="px-4 py-2 bg-gray-500 text-white rounded text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded text-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
