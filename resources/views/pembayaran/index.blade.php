<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Data Transaksi Pembayaran
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">Waktu</th>
                                <th class="p-3">No. Nota</th>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Tipe</th>
                                <th class="p-3">Status Pesanan</th>
                                <th class="p-3">Metode</th>
                                <th class="p-3">Kasir</th>
                                <th class="p-3 text-right">Jumlah</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($pembayarans as $bayar)
                                <tr>
                                    <td class="p-3 text-xs">{{ $bayar->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-3 font-mono font-bold text-blue-600">
                                        <a href="{{ route('pesanan.show', $bayar->pesanan) }}">{{ $bayar->pesanan->nomor_nota }}</a>
                                    </td>
                                    <td class="p-3">{{ $bayar->pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded font-bold {{ $bayar->tipe === 'dp' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ strtoupper($bayar->tipe) }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded font-semibold {{ optional($bayar->pesanan)->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ optional($bayar->pesanan)->status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                                        </span>
                                    </td>
                                    <td class="p-3 uppercase text-xs font-semibold">{{ $bayar->metode_pembayaran }}</td>
                                    <td class="p-3 text-xs">{{ $bayar->user->name ?? 'Kasir' }}</td>
                                    <td class="p-3 text-right font-bold text-green-600">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right">
                                        <a href="{{ route('pembayaran.receipt', $bayar) }}" class="text-xs bg-gray-100 hover:bg-gray-200 px-2.5 py-1 rounded border font-semibold">Kwitansi</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="p-4 text-center text-gray-500">Belum ada transaksi pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $pembayarans->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>