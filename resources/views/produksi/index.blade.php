<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Antrean Produksi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Siap Cetak -->
                <div class="bg-blue-50 dark:bg-gray-800 p-5 rounded-xl border-t-4 border-blue-500 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span> Siap Cetak
                        </h3>
                        <span class="bg-blue-200 text-blue-900 text-xs px-2.5 py-1 rounded-full font-bold">
                            {{ $pesananSiapCetak->count() }} Order
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse($pesananSiapCetak as $pesanan)
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-mono font-bold text-blue-600 dark:text-blue-400">{{ $pesanan->nomor_nota }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-300">{{ $pesanan->tanggal_pesanan->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Pelanggan: {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}</p>

                                <div class="text-xs text-gray-600 dark:text-gray-300 space-y-1 mb-3 bg-gray-50 dark:bg-gray-800 p-2 rounded">
                                    @foreach($pesanan->detailPesanans as $item)
                                        <div>
                                            • <strong>{{ $item->nama_item }}</strong> ({{ $item->bahan->nama_bahan ?? 'Jasa Design' }})
                                            @if($item->panjang && $item->lebar)
                                                - Ukuran: {{ $item->panjang }}m x {{ $item->lebar }}m
                                            @endif
                                            - Qty: {{ $item->qty }}
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-600">
                                    <a href="{{ route('produksi.show', $pesanan) }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                        Lihat Spek & Desain &rarr;
                                    </a>
                                    <form action="{{ route('pesanan.update-status', $pesanan) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="proses_cetak">
                                        <button type="submit" class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold rounded">
                                            Mulai Cetak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-6 text-gray-500 dark:text-gray-400">Tidak ada pesanan siap cetak.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Kolom Proses Cetak -->
                <div class="bg-yellow-50 dark:bg-gray-800 p-5 rounded-xl border-t-4 border-yellow-500 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span> Sedang Proses Cetak
                        </h3>
                        <span class="bg-yellow-200 text-yellow-900 text-xs px-2.5 py-1 rounded-full font-bold">
                            {{ $pesananProsesCetak->count() }} Order
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse($pesananProsesCetak as $pesanan)
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-mono font-bold text-yellow-600 dark:text-yellow-400">{{ $pesanan->nomor_nota }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-300">{{ $pesanan->tanggal_pesanan->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Pelanggan: {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}</p>

                                <div class="text-xs text-gray-600 dark:text-gray-300 space-y-1 mb-3 bg-gray-50 dark:bg-gray-800 p-2 rounded">
                                    @foreach($pesanan->detailPesanans as $item)
                                        <div>
                                            • <strong>{{ $item->nama_item }}</strong> ({{ $item->bahan->nama_bahan ?? 'Jasa Design' }})
                                            @if($item->panjang && $item->lebar)
                                                - Ukuran: {{ $item->panjang }}m x {{ $item->lebar }}m
                                            @endif
                                            - Qty: {{ $item->qty }}
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-600">
                                    <a href="{{ route('produksi.show', $pesanan) }}" class="text-xs font-semibold text-yellow-600 dark:text-yellow-400 hover:underline">
                                        Lihat Spek & Desain &rarr;
                                    </a>
                                    <form action="{{ route('pesanan.update-status', $pesanan) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-6 text-gray-500 dark:text-gray-400">Tidak ada pesanan sedang dicetak.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Riwayat Pesanan Sudah Dicetak -->
                <div class="md:col-span-2 bg-green-50 dark:bg-gray-800 p-5 rounded-xl border-t-4 border-green-500 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span> Riwayat Sudah Dicetak
                        </h3>
                        <div class="flex items-center gap-3">
                            <span class="bg-green-200 text-green-900 text-xs px-2.5 py-1 rounded-full font-bold">
                                {{ $pesananSelesai->count() }} Order Terbaru
                            </span>
                            <a href="{{ route('produksi.selesai') }}" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded shadow">
                                Lihat Semua Riwayat &rarr;
                            </a>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @forelse($pesananSelesai as $pesanan)
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                                <div class="flex flex-wrap justify-between items-start gap-2 mb-2">
                                    <span class="font-mono font-bold text-green-700 dark:text-green-400">{{ $pesanan->nomor_nota }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-300">Dicatat selesai: {{ $pesanan->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Pelanggan: {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}</p>
                                <div class="text-xs text-gray-600 dark:text-gray-300 space-y-1 bg-gray-50 dark:bg-gray-800 p-2 rounded">
                                    @foreach($pesanan->detailPesanans as $item)
                                        <div>• <strong>{{ $item->nama_item }}</strong> ({{ $item->bahan->nama_bahan ?? 'Jasa Design' }}) - Qty: {{ $item->qty }}</div>
                                    @endforeach
                                </div>
                                <a href="{{ route('produksi.show', $pesanan) }}" class="inline-block mt-3 text-xs font-semibold text-green-700 dark:text-green-400 hover:underline">
                                    Lihat Spek & Desain &rarr;
                                </a>
                            </div>
                        @empty
                            <p class="text-center py-6 text-gray-500 dark:text-gray-400">Belum ada pesanan yang selesai dicetak.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
