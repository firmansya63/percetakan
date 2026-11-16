<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Cashflow / Arus Kas
            </h2>
            @can('create_cashflow')
                <a href="{{ route('cashflow.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                    Tambah Transaksi
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('cashflow.index') }}" class="flex flex-wrap items-end gap-4">
                    <div class="min-w-[160px] flex-1">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm">
                    </div>
                    <div class="min-w-[160px] flex-1">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm">
                    </div>
                    <div class="min-w-[150px] flex-1">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tipe</label>
                        <select name="tipe" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            <option value="masuk" @selected($tipe === 'masuk')>Masuk</option>
                            <option value="keluar" @selected($tipe === 'keluar')>Keluar</option>
                        </select>
                    </div>
                    <div class="min-w-[180px] flex-1">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                        <select name="kategori" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm">
                            <option value="">Semua</option>
                            @foreach($kategoriOptions as $opt)
                                <option value="{{ $opt }}" @selected($kategori === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-md text-sm">Filter</button>
                        <a href="{{ route('cashflow.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium">Reset</a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Masuk</span>
                    <h4 class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Keluar</span>
                    <h4 class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 {{ $saldo >= 0 ? 'border-blue-500' : 'border-orange-500' }}">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Saldo (Masuk - Keluar)</span>
                    <h4 class="text-2xl font-bold {{ $saldo >= 0 ? 'text-blue-600' : 'text-orange-600' }} mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h4>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Riwayat Cashflow</h3>
                    <p class="text-xs text-gray-500">Transaksi dari pembayaran disinkronkan otomatis dan tidak dapat diedit/dihapus dari sini.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">Tipe</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Keterangan</th>
                                <th class="p-3">Metode</th>
                                <th class="p-3">Sumber</th>
                                <th class="p-3">User</th>
                                <th class="p-3 text-right">Jumlah</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($cashFlows as $flow)
                                <tr>
                                    <td class="p-3 whitespace-nowrap">{{ $flow->tanggal?->format('d/m/Y') }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded font-bold {{ $flow->tipe === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ strtoupper($flow->tipe) }}
                                        </span>
                                    </td>
                                    <td class="p-3">{{ $flow->kategori }}</td>
                                    <td class="p-3 text-xs text-gray-600 dark:text-gray-300 max-w-xs">{{ $flow->keterangan ?? '-' }}</td>
                                    <td class="p-3 uppercase text-xs">{{ $flow->metode_pembayaran ?? '-' }}</td>
                                    <td class="p-3 text-xs">
                                        @if($flow->pembayaran)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200">Pembayaran #{{ $flow->pembayaran_id }}</span>
                                        @else
                                            <span class="text-gray-500">Manual</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-xs">{{ $flow->user->name ?? '-' }}</td>
                                    <td class="p-3 text-right font-bold {{ $flow->tipe === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $flow->tipe === 'masuk' ? '+' : '-' }} Rp {{ number_format($flow->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-center">
                                        @if($flow->isSynced())
                                            <span class="text-xs text-gray-400">Terkunci</span>
                                        @else
                                            <div class="flex items-center justify-center gap-2">
                                                @can('edit_cashflow')
                                                    <a href="{{ route('cashflow.edit', $flow) }}" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Edit</a>
                                                @endcan
                                                @can('delete_cashflow')
                                                    <form action="{{ route('cashflow.destroy', $flow) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi cashflow ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                                                    </form>
                                                @endcan
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-6 text-center text-gray-500">Belum ada data cashflow pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $cashFlows->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
