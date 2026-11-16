<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Rekapitulasi Pesanan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('laporan.pesanan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal:</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal:</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status:</label>
                        <select name="status" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                            <option value="">Semua Status</option>
                            @foreach($statusList as $key => $label)
                                <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md text-sm">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">No. Nota</th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Pembayaran</th>
                                <th class="p-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($pesanans as $pesanan)
                                <tr>
                                    <td class="p-3 font-mono font-bold text-blue-600">{{ $pesanan->nomor_nota }}</td>
                                    <td class="p-3">{{ $pesanan->tanggal_pesanan->format('d/m/Y') }}</td>
                                    <td class="p-3">{{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded bg-blue-100 text-blue-800 font-semibold">{{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded font-semibold {{ $pesanan->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran)) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right font-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-4 text-center text-gray-500">Tidak ada data pesanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $pesanans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
