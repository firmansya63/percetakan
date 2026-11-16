<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Riwayat Pesanan Selesai Dicetak
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                            Daftar Riwayat Produksi
                        </h3>
                        <a href="{{ route('produksi.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-bold rounded shadow">
                            &larr; Kembali ke Antrean
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                                <tr>
                                    <th class="p-3">Waktu Selesai</th>
                                    <th class="p-3">No. Nota</th>
                                    <th class="p-3">Pelanggan</th>
                                    <th class="p-3">Item Pesanan</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-700">
                                @forelse($pesananSelesai as $pesanan)
                                    <tr>
                                        <td class="p-3 text-xs">{{ $pesanan->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="p-3 font-mono font-bold text-green-600 dark:text-green-400">
                                            {{ $pesanan->nomor_nota }}
                                        </td>
                                        <td class="p-3 font-semibold">{{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                                        <td class="p-3">
                                            <div class="text-xs space-y-1">
                                                @foreach($pesanan->detailPesanans as $item)
                                                    <div>• {{ $item->nama_item }} ({{ $item->qty }})</div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="p-3 text-center">
                                            <a href="{{ route('produksi.show', $pesanan) }}" class="inline-block px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-bold rounded">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada riwayat pesanan yang selesai dicetak.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $pesananSelesai->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>