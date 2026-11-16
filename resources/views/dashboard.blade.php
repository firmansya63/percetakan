<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 {{ $canViewOmzet ?? false ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-4 sm:gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Total Pesanan</h3>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $totalPesanan }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Pesanan Hari Ini</h3>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $pesananHariIni }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Sedang Proses Cetak</h3>
                    <p class="text-2xl sm:text-3xl font-bold text-orange-600">{{ $pesananProses }}</p>
                </div>
                @if($canViewOmzet)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400">Total Omzet (Lunas)</h3>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-purple-600 break-words">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Pesanan Terbaru</h3>
                            <a href="{{ route('pesanan.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($pesananTerbaru as $pesanan)
                                <div class="py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ route('pesanan.show', $pesanan) }}" class="font-semibold text-blue-600 hover:underline break-words">
                                            {{ $pesanan->nomor_nota }}
                                        </a>
                                        <p class="text-xs text-gray-500 break-words">{{ $pesanan->pelanggan?->nama_pelanggan ?? 'Umum / Walk-in' }} &bull; {{ $pesanan->tanggal_pesanan?->format('d/m/Y') ?? 'Tanggal tidak diketahui' }}</p>
                                    </div>
                                    <div class="sm:text-right shrink-0">
                                        <span class="inline-block px-2 py-1 text-xs rounded font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 py-4">Belum ada pesanan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Ringkasan Status Pesanan</h3>
                        <div class="space-y-3">
                            @forelse($pesananStatus as $status)
                                <div class="flex justify-between items-center p-3 rounded bg-gray-50 dark:bg-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $status->status)) }}</span>
                                    <span class="text-sm font-bold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-0.5 rounded">{{ $status->total }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500">Tidak ada data</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>