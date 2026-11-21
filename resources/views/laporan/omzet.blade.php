<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Cashflow — Laporan Omzet
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @include('cashflow._tabs')

            <!-- Filter Tanggal -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('laporan.omzet') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal:</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal:</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm">
                    </div>
                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md text-sm">
                            Filter Laporan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ringkasan Omzet Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-purple-50 dark:bg-gray-800 p-6 rounded-lg border-l-4 border-purple-500 shadow-sm">
                    <span class="text-xs uppercase font-bold text-purple-700 dark:text-purple-300">Total Omzet (Pesanan Lunas)</span>
                    <h3 class="text-3xl font-extrabold text-purple-900 dark:text-purple-100 mt-2">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-gray-800 p-6 rounded-lg border-l-4 border-blue-500 shadow-sm">
                    <span class="text-xs uppercase font-bold text-blue-700 dark:text-blue-300">Total Transaksi Lunas</span>
                    <h3 class="text-3xl font-extrabold text-blue-900 dark:text-blue-100 mt-2">{{ $totalPesanan }} Transaksi</h3>
                </div>
            </div>

            <!-- Tabel Data Pesanan Lunas -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Daftar Transaksi Lunas</h3>
                    <button onclick="window.print()" class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-xs font-semibold rounded">Cetak Laporan</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">No. Nota</th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Status Cetak</th>
                                <th class="p-3 text-right">Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($pesananLunas as $order)
                                <tr>
                                    <td class="p-3 font-mono font-bold text-blue-600">{{ $order->nomor_nota }}</td>
                                    <td class="p-3">{{ $order->tanggal_pesanan->format('d/m/Y') }}</td>
                                    <td class="p-3">{{ $order->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-800 font-semibold">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                    </td>
                                    <td class="p-3 text-right font-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-gray-500">Tidak ada transaksi lunas pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
