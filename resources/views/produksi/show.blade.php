<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Surat Perintah Kerja (SPK) / Spesifikasi Produksi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Info Order & Status Update Form (NO PRICE/MONEY DATA) -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b pb-4 mb-4 dark:border-gray-700">
                    <div>
                        <span class="text-xs uppercase tracking-wide text-gray-500">Nomor Nota / SPK</span>
                        <h1 class="text-2xl font-mono font-bold text-gray-900 dark:text-gray-100">{{ $pesanan->nomor_nota }}</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Pelanggan: <strong>{{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}</strong> &bull;
                            Tgl Pesan: {{ $pesanan->tanggal_pesanan->format('d/m/Y') }}
                        </p>
                    </div>

                    <!-- Update Status Form -->
                    <form action="{{ route('pesanan.update-status', $pesanan) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Status:</label>
                        <select name="status" class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm">
                            <option value="siap_cetak" {{ $pesanan->status === 'siap_cetak' ? 'selected' : '' }}>Siap Cetak</option>
                            <option value="proses_cetak" {{ $pesanan->status === 'proses_cetak' ? 'selected' : '' }}>Proses Cetak</option>
                            <option value="selesai" {{ $pesanan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <button type="submit" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-md">
                            Update
                        </button>
                    </form>
                </div>

                @if($pesanan->catatan)
                    <div class="p-3 bg-amber-50 dark:bg-gray-700 rounded border border-amber-200 dark:border-gray-600 text-sm">
                        <strong class="text-amber-800 dark:text-amber-200">Catatan Khusus Pesanan:</strong>
                        <p class="text-gray-700 dark:text-gray-300">{{ $pesanan->catatan }}</p>
                    </div>
                @endif
            </div>

            <!-- Technical Specifications Table (NO PRICE/MONEY COLUMNS) -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Rincian Spesifikasi Teknis Cetak</h3>

                <div class="space-y-6">
                    @foreach($pesanan->detailPesanans as $index => $item)
                        <div class="border rounded-lg p-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-750">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 mb-3">
                                <div>
                                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                        Item #{{ $index + 1 }}
                                    </span>
                                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 inline ml-2">{{ $item->nama_item }}</h4>
                                </div>
                                <div>
                                    @if($item->file_desain)
                                        <a href="{{ route('produksi.download', $item) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow">
                                            Download File Desain
                                        </a>
                                    @else
                                        <span class="text-xs font-medium text-red-500 italic">File desain belum diupload</span>
                                    @endif
                                </div>
                            </div>

                            @if($item->is_jasa_design)
                                <div class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                    <span class="font-semibold">{{ $item->nama_item }}</span> (tidak memerlukan produksi cetak)
                                </div>
                            @else
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-3">
                                <div>
                                    <span class="text-xs text-gray-500 block">Bahan</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $item->bahan->nama_bahan }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Jenis Cetakan</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ ucfirst($item->bahan->jenis_cetakan) }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Ukuran (P x L)</span>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">
                                        @if($item->panjang && $item->lebar)
                                            {{ $item->panjang }}m x {{ $item->lebar }}m
                                        @else
                                            Standar ({{ $item->bahan->satuan }})
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Jumlah Cetak (Qty)</span>
                                    <span class="text-base font-bold text-blue-600">{{ $item->qty }} {{ $item->bahan->satuan }}</span>
                                </div>
                            </div>
                            @endif

                            <!-- Finishing list -->
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400 block mb-1">Opsi Finishing:</span>
                                @if($item->detailFinishings->count() > 0)
                                    <ul class="list-disc list-inside text-xs text-gray-700 dark:text-gray-300 space-y-0.5">
                                        @foreach($item->detailFinishings as $fin)
                                            <li>{{ $fin->finishing->nama_finishing }} (Qty: {{ $fin->qty }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-xs text-gray-500 italic">Tanpa finishing khusus.</span>
                                @endif
                            </div>

                            @if($item->catatan)
                                <div class="mt-2 text-xs text-amber-700 dark:text-amber-300">
                                    <strong>Instruksi Khusus:</strong> {{ $item->catatan }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('produksi.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded">
                        &larr; Kembali ke Antrean
                    </a>
                    <button onclick="window.print()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded">
                        Cetak Lembar Kerja (SPK)
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
