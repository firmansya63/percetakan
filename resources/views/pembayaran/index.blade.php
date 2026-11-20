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
                                <th class="p-3">Metode</th>
                                <th class="p-3">Kasir</th>
                                <th class="p-3 text-right">Jumlah</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-center">Aksi</th>
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
                                    <td class="p-3 uppercase text-xs font-semibold">{{ $bayar->metode_pembayaran }}</td>
                                    <td class="p-3 text-xs">{{ $bayar->user->name ?? 'Kasir' }}</td>
                                    <td class="p-3 text-right font-bold text-green-600">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">
                                        <span class="px-2 py-0.5 text-xs rounded font-semibold {{ optional($bayar->pesanan)->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ optional($bayar->pesanan)->status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pembayaran.receipt', $bayar) }}" target="_blank" rel="noopener"
                                               title="Cetak Kwitansi"
                                               class="inline-flex items-center justify-center p-1.5 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                                </svg>
                                            </a>
                                            @can('create_pembayaran')
                                                @if(optional($bayar->pesanan)->status_pembayaran !== 'lunas')
                                                    <a href="{{ route('pembayaran.create', $bayar->pesanan) }}"
                                                       title="Pembayaran / Pelunasan"
                                                       class="inline-flex items-center justify-center p-1.5 rounded-md bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 dark:hover:bg-green-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 4.5 6h16.5a.75.75 0 0 1 .75.75v9a.75.75 0 0 1-.75.75H4.5a.75.75 0 0 1-.75-.75v-9a.75.75 0 0 1 .75-.75Zm3 6.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm9.75-3h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endcan
                                        </div>
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