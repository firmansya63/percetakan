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
                                    <td class="border p-2">{{ $detail->nama_item }}</td>
                                    <td class="border p-2 text-right">{{ $detail->qty }}</td>
                                    <td class="border p-2 text-right">Rp {{ number_format($detail->harga_bahan) }}</td>
                                    <td class="border p-2 text-right">Rp {{ number_format($detail->subtotal) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="flex justify-end mb-6">
                            <div class="w-64">
                                <div class="flex justify-between border-t pt-2">
                                    <span class="font-bold">Total:</span>
                                    <span class="font-bold">Rp {{ number_format($pesanan->total_harga) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>