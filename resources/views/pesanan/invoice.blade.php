<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Invoice Pesanan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded mb-4">Cetak</button>
                    
                    <div class="border p-6">
                        <x-company-header class="mb-4 pb-4 border-b" />

                        <h1 class="text-3xl font-bold text-center mb-2">INVOICE</h1>
                        <p class="text-center mb-6">{{ $pesanan->nomor_nota }}</p>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="font-bold">Pelanggan:</p>
                                <p>{{ $pesanan->pelanggan->nama_pelanggan ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="font-bold">Tanggal:</p>
                                <p>{{ $pesanan->tanggal_pesanan->format('d-m-Y') }}</p>
                            </div>
                        </div>

                        <table class="w-full border-collapse border mb-6">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border p-2 text-left">Item</th>
                                    <th class="border p-2 text-right">Qty</th>
                                    <th class="border p-2 text-right">Harga</th>
                                    <th class="border p-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detailPesanans as $detail)
                                <tr>
                                    <td class="border p-2">
                                        {{ $detail->nama_item }}
                                        @if($detail->is_jasa_design)
                                            <span class="text-xs text-yellow-700">({{ $detail->nama_item }})</span>
                                        @endif
                                    </td>
                                    <td class="border p-2 text-right">{{ $detail->qty }}</td>
                                    <td class="border p-2 text-right">Rp {{ number_format($detail->harga_bahan) }}</td>
                                    <td class="border p-2 text-right">Rp {{ number_format($detail->subtotal) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mb-6">
                            <h2 class="font-bold mb-2">Rincian Pembayaran</h2>
                            @if($pesanan->pembayarans->isNotEmpty())
                                <table class="w-full border-collapse border">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="border p-2 text-left">Tanggal</th>
                                            <th class="border p-2 text-left">Tipe</th>
                                            <th class="border p-2 text-left">Metode</th>
                                            <th class="border p-2 text-right">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pesanan->pembayarans as $pembayaran)
                                        <tr>
                                            <td class="border p-2">{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="border p-2 uppercase">{{ $pembayaran->tipe === 'dp' ? 'Uang Muka (DP)' : 'Pelunasan' }}</td>
                                            <td class="border p-2 uppercase">{{ $pembayaran->metode_pembayaran }}</td>
                                            <td class="border p-2 text-right">Rp {{ number_format($pembayaran->jumlah) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-sm text-gray-500">Belum ada pembayaran.</p>
                            @endif
                        </div>

                        <div class="flex justify-end mb-6">
                            <div class="w-64">
                                <div class="flex justify-between border-t pt-2">
                                    <span class="font-bold">Total Tagihan:</span>
                                    <span class="font-bold">Rp {{ number_format($pesanan->total_harga) }}</span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span>DP / Uang Muka:</span>
                                    <span>Rp {{ number_format($pesanan->total_dp) }}</span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="font-bold">Sisa Bayar:</span>
                                    <span class="font-bold">Rp {{ number_format($pesanan->sisa_bayar) }}</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-center text-sm italic mt-6 border-t pt-4">
                            * Invoice ini harap dibawa ketika pengambilan hasil cetak
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>