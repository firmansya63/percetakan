<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Pesanan: {{ $pesanan->nomor_nota }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            <!-- Top Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <span class="text-xs text-gray-500 uppercase">Nomor Nota</span>
                    <h3 class="text-2xl font-mono font-bold text-gray-900 dark:text-gray-100">{{ $pesanan->nomor_nota }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <span class="text-xs text-gray-500 uppercase">Status Produksi</span>
                    <h3 class="text-xl font-bold text-yellow-600">{{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <span class="text-xs text-gray-500 uppercase">Total Tagihan</span>
                    <h3 class="text-2xl font-bold text-purple-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm border-l-4 {{ $pesanan->status_pembayaran === 'lunas' ? 'border-green-500' : 'border-red-500' }}">
                    <span class="text-xs text-gray-500 uppercase">Status Pembayaran</span>
                    <h3 class="text-xl font-bold {{ $pesanan->status_pembayaran === 'lunas' ? 'text-green-600' : 'text-red-600' }}">
                        {{ strtoupper(str_replace('_', ' ', $pesanan->status_pembayaran)) }}
                    </h3>
                </div>
            </div>

            <!-- Customer & Order Information -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex flex-col md:flex-row justify-between gap-6">
                <div class="space-y-1 text-sm">
                    <p><strong>Pelanggan:</strong> {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}</p>
                    <p><strong>No. Telepon:</strong> {{ $pesanan->pelanggan->telepon ?? '-' }}</p>
                    <p><strong>Tanggal Pesan:</strong> {{ $pesanan->tanggal_pesanan->format('d/m/Y') }}</p>
                    <p><strong>Estimasi Selesai:</strong> {{ $pesanan->tanggal_ambil ? $pesanan->tanggal_ambil->format('d/m/Y') : '-' }}</p>
                    <p><strong>Kasir Pembuat:</strong> {{ $pesanan->user->name ?? 'Kasir' }}</p>
                </div>
                <div class="space-y-1 text-sm md:text-right">
                    <p><strong>Total Tagihan:</strong> Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                    <p><strong>Total Terbayar:</strong> <span class="text-green-600 font-bold">Rp {{ number_format($pesanan->total_dp, 0, ',', '.') }}</span></p>
                    <p><strong>Sisa Pembayaran:</strong> <span class="text-red-600 font-extrabold text-base">Rp {{ number_format($pesanan->sisa_bayar, 0, ',', '.') }}</span></p>
                    @if($pesanan->catatan)
                        <p class="text-xs text-gray-500 mt-2"><strong>Catatan:</strong> {{ $pesanan->catatan }}</p>
                    @endif
                </div>
            </div>

            <!-- Items & Upload Desain Section -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Item Pekerjaan & Berkas Desain</h3>

                <div class="space-y-4">
                    @foreach($pesanan->detailPesanans as $idx => $item)
                        <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-750">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 mb-2">
                                <div>
                                    <span class="font-bold text-sm text-gray-800">#{{ $idx + 1 }} {{ $item->nama_item }}</span>
                                    @if($item->is_jasa_design)
                                        <span class="text-xs text-yellow-700 ml-2">(Jasa Design)</span>
                                    @else
                                        <span class="text-xs text-gray-500 ml-2">({{ $item->bahan->nama_bahan ?? '-' }})</span>
                                    @endif
                                </div>
                                <div class="text-sm font-bold text-green-600">
                                    Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-xs text-gray-600 mb-3">
                                <div>
                                    @if($item->is_jasa_design)
                                        Jenis: <strong>Jasa Design (harga manual)</strong>
                                    @elseif($item->panjang && $item->lebar)
                                        Ukuran: <strong>{{ $item->panjang }}m x {{ $item->lebar }}m</strong> (Luas: {{ $item->panjang * $item->lebar }} m²)
                                    @else
                                        Ukuran: <strong>Standar ({{ $item->bahan->satuan ?? '-' }})</strong>
                                    @endif
                                    &bull; Qty: <strong>{{ $item->qty }}</strong>
                                </div>
                                <div>
                                    Harga: Rp {{ number_format($item->harga_bahan, 0, ',', '.') }}
                                    (Subtotal: Rp {{ number_format($item->subtotal_bahan, 0, ',', '.') }})
                                </div>
                                <div>
                                    @if($item->detailFinishings->count() > 0)
                                        Finishing:
                                        @foreach($item->detailFinishings as $f)
                                            <span class="inline-block bg-gray-200 px-1.5 py-0.5 rounded text-gray-700 mr-1">{{ $f->finishing->nama_finishing }} (+Rp {{ number_format($f->subtotal, 0, ',', '.') }})</span>
                                        @endforeach
                                    @else
                                        Finishing: <span class="italic text-gray-400">Tidak ada</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Upload & Status File Desain -->
                            @if(! $item->is_jasa_design)
                            <div class="pt-3 border-t flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                                <div class="text-xs">
                                    @if($item->file_desain)
                                        <div class="flex items-center gap-2">
                                            <span class="text-green-600 font-bold flex items-center gap-1">
                                                &#10003; Desain Siap:
                                            </span>
                                            <a href="{{ route('produksi.download', $item) }}" class="text-blue-600 hover:underline font-mono">
                                                Download Berkas Desain
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-red-500 font-semibold italic">Belum ada file desain diupload.</span>
                                    @endif
                                </div>

                                <form action="{{ route('detail-pesanan.upload', $item) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                                    @csrf
                                    <input type="file" name="file_desain" required class="text-xs border rounded p-1">
                                    <button type="submit" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded">
                                        Upload Desain
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Riwayat Pembayaran & Actions -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Riwayat Pembayaran</h3>
                    @if($pesanan->sisa_bayar > 0)
                        <a href="{{ route('pembayaran.create', $pesanan) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded shadow">
                            + Proses Pembayaran / Pelunasan
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-2">Waktu</th>
                                <th class="p-2">Tipe</th>
                                <th class="p-2">Metode</th>
                                <th class="p-2">Kasir</th>
                                <th class="p-2 text-right">Jumlah</th>
                                <th class="p-2 text-right">Kembalian</th>
                                <th class="p-2 text-right">Kwitansi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($pesanan->pembayarans as $pembayaran)
                                <tr>
                                    <td class="p-2 text-xs">{{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-2">
                                        <span class="px-2 py-0.5 text-xs rounded font-bold {{ $pembayaran->tipe === 'dp' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ strtoupper($pembayaran->tipe) }}
                                        </span>
                                    </td>
                                    <td class="p-2 uppercase text-xs">{{ $pembayaran->metode_pembayaran }}</td>
                                    <td class="p-2 text-xs">{{ $pembayaran->user->name ?? 'Kasir' }}</td>
                                    <td class="p-2 text-right font-bold text-green-600">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                    <td class="p-2 text-right font-semibold {{ (float) $pembayaran->kembalian > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                                        {{ (float) $pembayaran->kembalian > 0 ? 'Rp ' . number_format($pembayaran->kembalian, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="p-2 text-right">
                                        <a href="{{ route('pembayaran.receipt', $pembayaran) }}" class="text-xs text-blue-600 hover:underline">Lihat Kwitansi</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="p-3 text-center text-gray-500 text-xs">Belum ada catatan pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap justify-between items-center gap-3 pt-4 border-t">
                    <div class="flex gap-2">
                        <a href="{{ route('pesanan.invoice', $pesanan) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded shadow">
                            Cetak Nota / Invoice
                        </a>
                    </div>
                    <a href="{{ route('pesanan.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Kembali ke Daftar Pesanan</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
