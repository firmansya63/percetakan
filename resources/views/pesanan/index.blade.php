<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Daftar Pesanan</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola dan pantau seluruh transaksi percetakan</p>
            </div>
            @can('create_pesanan')
            <a href="{{ route('pesanan.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-200 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Pesanan Baru
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Flash Notification --}}
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Filter & Search Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <form method="GET" action="{{ route('pesanan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
                
                {{-- Search Box --}}
                <div class="lg:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nomor nota atau nama pelanggan..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                    >
                </div>

                {{-- Status Filter --}}
                <div class="lg:col-span-4">
                    <select 
                        name="status" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                    >
                        <option value="">Semua Status Pesanan</option>
                        @foreach($statusList as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 text-white text-sm font-semibold transition"
                    >
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a 
                            href="{{ route('pesanan.index') }}" 
                            class="p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition" 
                            title="Reset Filter"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-100 dark:border-gray-700/60 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="py-4 px-5">Nota & Pelanggan</th>
                            <th class="py-4 px-5">Tanggal</th>
                            <th class="py-4 px-5">Status Produksi</th>
                            <th class="py-4 px-5">Pembayaran</th>
                            <th class="py-4 px-5 text-right">Total Biaya</th>
                            <th class="py-4 px-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                        @forelse($pesanans as $pesanan)
                            @php
                                $statusKey = strtolower($pesanan->status ?? '');
                                $statusStyles = [
                                    'menunggu_desain' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                    'siap_cetak'      => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                    'proses_cetak'    => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                    'finishing'       => 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                    'selesai'         => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                    'diambil'         => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600',
                                    'batal'           => 'bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                ];
                                $badgeStyle = $statusStyles[$statusKey] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600';

                                $payKey = strtolower($pesanan->status_pembayaran ?? '');
                                $payStyles = [
                                    'lunas'       => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                    'belum_lunas' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                    'dp'          => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                ];
                                $payStyle = $payStyles[$payKey] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600';
                            @endphp
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-colors">
                                
                                {{-- Nota & Pelanggan --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('pesanan.show', $pesanan) }}" class="font-bold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                {{ $pesanan->nomor_nota }}
                                            </a>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                                {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum / Walk-in' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                                        {{ $pesanan->tanggal_pesanan ? $pesanan->tanggal_pesanan->format('d M Y') : '-' }}
                                    </div>
                                    @if($pesanan->tanggal_ambil)
                                        <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                            Ambil: {{ $pesanan->tanggal_ambil->format('d M Y') }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Status Produksi --}}
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeStyle }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                                    </span>
                                </td>

                                {{-- Status Pembayaran --}}
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $payStyle }}">
                                        {{ ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran ?? 'belum')) }}
                                    </span>
                                </td>

                                {{-- Total Biaya --}}
                                <td class="py-4 px-5 whitespace-nowrap text-right">
                                    <div class="font-bold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </div>
                                    @if($pesanan->sisa_bayar > 0)
                                        <div class="text-[11px] text-amber-600 dark:text-amber-400 font-medium">
                                            Sisa: Rp {{ number_format($pesanan->sisa_bayar, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Action Buttons --}}
                                <td class="py-4 px-5 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1">
                                        {{-- View --}}
                                        <a 
                                            href="{{ route('pesanan.show', $pesanan) }}" 
                                            class="p-2 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors"
                                            title="Lihat Detail"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>

                                        {{-- Edit --}}
                                        @can('edit_pesanan')
                                        <a 
                                            href="{{ route('pesanan.edit', $pesanan) }}" 
                                            class="p-2 rounded-lg text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors"
                                            title="Edit Pesanan"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                                        @endcan

                                        {{-- Delete --}}
                                        @can('delete_pesanan')
                                        <form action="{{ route('pesanan.destroy', $pesanan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan {{ $pesanan->nomor_nota }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="p-2 rounded-lg text-rose-600 hover:text-rose-800 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors"
                                                title="Hapus Pesanan"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-5 text-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-700/50 text-gray-400 mx-auto flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200">Tidak ada pesanan ditemukan</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                                        @if(request()->hasAny(['search', 'status']))
                                            Coba sesuaikan kata kunci pencarian atau ubah filter status yang dipilih.
                                        @else
                                            Belum ada data pesanan yang tercatat. Buat pesanan baru untuk mulai transaksi.
                                        @endif
                                    </p>
                                    @if(request()->hasAny(['search', 'status']))
                                        <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:underline mt-3">
                                            Reset Filter
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pesanans->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-800">
                    {{ $pesanans->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
