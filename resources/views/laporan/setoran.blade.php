<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Setoran Harian Kasir
        </h2>
    </x-slot>

    <div class="pt-2 pb-6 sm:pt-4 sm:pb-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('laporan.setoran') }}" class="flex flex-wrap items-end gap-4">
                    <div class="min-w-[180px] flex-1">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-md text-sm">Tampilkan</button>
                </form>
            </div>

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Setoran</span>
                    <h4 class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($pembayarans->sum('jumlah'), 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-emerald-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Tunai</span>
                    <h4 class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($perMetode['tunai'] ?? 0, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-violet-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Transfer</span>
                    <h4 class="text-2xl font-bold text-violet-600 mt-1">Rp {{ number_format($perMetode['transfer'] ?? 0, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-cyan-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">QRIS</span>
                    <h4 class="text-2xl font-bold text-cyan-600 mt-1">Rp {{ number_format($perMetode['qris'] ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>

            {{-- Riwayat --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Riwayat Penerimaan ({{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }})</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">Waktu</th>
                                <th class="p-3">No. Nota</th>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Tipe</th>
                                <th class="p-3">Metode</th>
                                <th class="p-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($pembayarans as $bayar)
                                <tr>
                                    <td class="p-3 text-xs">{{ $bayar->created_at->format('H:i') }}</td>
                                    <td class="p-3 font-mono font-bold">{{ $bayar->pesanan->nomor_nota ?? '-' }}</td>
                                    <td class="p-3">{{ $bayar->pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded font-bold {{ $bayar->tipe === 'dp' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ strtoupper($bayar->tipe) }}
                                        </span>
                                    </td>
                                    <td class="p-3 uppercase text-xs font-semibold">{{ $bayar->metode_pembayaran }}</td>
                                    <td class="p-3 text-right font-bold text-green-600">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-4 text-center text-gray-500">Belum ada penerimaan pada tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
