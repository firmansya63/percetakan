<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Keuangan & Arus Kas (Owner Only)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Filter Tanggal -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('laporan.keuangan') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
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
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Uang Masuk</span>
                    <h4 class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Uang Muka (DP)</span>
                    <h4 class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($totalDP, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Pelunasan</span>
                    <h4 class="text-2xl font-bold text-indigo-600 mt-1">Rp {{ number_format($totalPelunasan, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Piutang Belum Lunas</span>
                    <h4 class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- Rincian Pembayaran Masuk -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Riwayat Pembayaran Masuk</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">Waktu</th>
                                <th class="p-3">No. Nota</th>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Tipe</th>
                                <th class="p-3">Metode</th>
                                <th class="p-3">Kasir</th>
                                <th class="p-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($pembayarans as $bayar)
                                <tr>
                                    <td class="p-3 text-xs">{{ $bayar->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-3 font-mono font-bold">{{ $bayar->pesanan->nomor_nota }}</td>
                                    <td class="p-3">{{ $bayar->pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded font-bold {{ $bayar->tipe === 'dp' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ strtoupper($bayar->tipe) }}
                                        </span>
                                    </td>
                                    <td class="p-3 uppercase text-xs font-semibold">{{ $bayar->metode_pembayaran }}</td>
                                    <td class="p-3 text-xs">{{ $bayar->user->name ?? 'System' }}</td>
                                    <td class="p-3 text-right font-bold text-green-600">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="p-4 text-center text-gray-500">Tidak ada riwayat pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
