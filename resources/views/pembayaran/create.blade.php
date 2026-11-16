<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Proses Pembayaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                    <p class="text-gray-600">Nomor Nota</p>
                    <p class="text-2xl font-bold">{{ $pesanan->nomor_nota }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                    <p class="text-gray-600">Sisa Bayar</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($pesanan->sisa_bayar) }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('pembayaran.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Tipe Pembayaran</label>
                            <select name="tipe" required class="w-full border rounded px-3 py-2">
                                <option value="dp">DP (Down Payment)</option>
                                <option value="pelunasan">Pelunasan</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Jumlah Pembayaran</label>
                            <input type="number" name="jumlah" placeholder="0" required step="any" min="1" class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Metode Pembayaran</label>
                            <select name="metode_pembayaran" required class="w-full border rounded px-3 py-2">
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Catatan</label>
                            <textarea name="catatan" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan Pembayaran</button>
                        <a href="{{ route('pesanan.show', $pesanan) }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>