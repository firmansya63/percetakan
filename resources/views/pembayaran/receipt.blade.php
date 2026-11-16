<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kwitansi Pembayaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-sm border">
                <div class="flex justify-between items-center mb-6">
                    <button onclick="window.print()" class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded">Cetak Kwitansi</button>
                    <a href="{{ route('pesanan.show', $pembayaran->pesanan) }}" class="text-xs text-gray-500 hover:underline">&larr; Kembali ke Pesanan</a>
                </div>

                <div class="border-b-2 border-dashed pb-4 mb-4 text-center">
                    <h2 class="text-xl font-bold uppercase tracking-wider">Kwitansi Pembayaran Percetakan</h2>
                    <p class="text-xs text-gray-500">Tanda Bukti Pembayaran Sah</p>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nomor Nota:</span>
                        <span class="font-mono font-bold">{{ $pembayaran->pesanan->nomor_nota }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal & Waktu:</span>
                        <span>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telah Terima Dari:</span>
                        <span class="font-bold">{{ $pembayaran->pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jenis Pembayaran:</span>
                        <span class="font-bold uppercase">{{ $pembayaran->tipe === 'dp' ? 'Uang Muka (DP)' : 'Pelunasan Tagihan' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode Bayar:</span>
                        <span class="font-semibold uppercase">{{ $pembayaran->metode_pembayaran }}</span>
                    </div>
                    <div class="border-t border-b py-3 flex justify-between items-center bg-gray-50 p-2 rounded">
                        <span class="font-bold">Jumlah Dibayar:</span>
                        <span class="text-xl font-extrabold text-green-600">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600 pt-1">
                        <span>Total Tagihan Pesanan:</span>
                        <span>Rp {{ number_format($pembayaran->pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>Total Telah Dibayar:</span>
                        <span>Rp {{ number_format($pembayaran->pesanan->total_dp, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-red-600">
                        <span>Sisa Tagihan:</span>
                        <span>Rp {{ number_format($pembayaran->pesanan->sisa_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-8 pt-4 border-t border-dashed flex justify-between text-xs text-center text-gray-500">
                    <div>
                        <p>Kasir Penerima,</p>
                        <br><br>
                        <p class="font-semibold text-gray-700">({{ $pembayaran->user->name ?? 'Kasir' }})</p>
                    </div>
                    <div>
                        <p>Pelanggan,</p>
                        <br><br>
                        <p class="font-semibold text-gray-700">({{ $pembayaran->pesanan->pelanggan->nama_pelanggan ?? '..................' }})</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
