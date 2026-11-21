<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Cashflow / Arus Kas
            </h2>
            @can('create_cashflow')
                <div class="flex items-center gap-2">
                    <button type="button" x-data x-on:click="$dispatch('open-modal', 'input-pengeluaran')"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Input Pengeluaran
                    </button>
                    <a href="{{ route('cashflow.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                        Tambah Transaksi
                    </a>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="pt-2 pb-6 sm:pt-4 sm:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @include('cashflow._tabs')

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
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Pemasukan per Metode Pembayaran</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-lg border-l-4 border-violet-500 bg-gray-50 dark:bg-gray-700/40">
                        <span class="text-xs text-gray-500 uppercase font-semibold">Transfer</span>
                        <h4 class="text-2xl font-bold text-violet-600 mt-1">Rp {{ number_format($masukTransfer, 0, ',', '.') }}</h4>
                    </div>
                    <div class="p-4 rounded-lg border-l-4 border-cyan-500 bg-gray-50 dark:bg-gray-700/40">
                        <span class="text-xs text-gray-500 uppercase font-semibold">QRIS</span>
                        <h4 class="text-2xl font-bold text-cyan-600 mt-1">Rp {{ number_format($masukQris, 0, ',', '.') }}</h4>
                    </div>
                    <div class="p-4 rounded-lg border-l-4 border-emerald-500 bg-gray-50 dark:bg-gray-700/40">
                        <span class="text-xs text-gray-500 uppercase font-semibold">Tunai</span>
                        <h4 class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($masukTunai, 0, ',', '.') }}</h4>
                    </div>
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

    {{-- Modal Input Pengeluaran --}}
    @can('create_cashflow')
    <x-modal name="input-pengeluaran" :show="$errors->any()" maxWidth="lg">
        <form method="POST" action="{{ route('cashflow.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="tipe" value="keluar">

            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Input Pengeluaran</h3>
                <button type="button" x-on:click="$dispatch('close-modal', 'input-pengeluaran')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if($errors->any())
                <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="modal-tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                    <input id="modal-tanggal" name="tanggal" type="date" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required
                        class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                </div>
                <div>
                    <label for="modal-kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <input id="modal-kategori" name="kategori" type="text" list="modal-kategori-options" value="{{ old('kategori') }}" required maxlength="100"
                        placeholder="Contoh: Pembelian Bahan, Gaji, Operasional"
                        class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                    <datalist id="modal-kategori-options">
                        @foreach($kategoriList as $opt)
                            <option value="{{ $opt }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label for="modal-jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah (Rp)</label>
                    <input id="modal-jumlah" name="jumlah" type="number" step="0.01" min="0.01" value="{{ old('jumlah') }}" required placeholder="0"
                        class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                </div>
                <div>
                    <label for="modal-metode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran (opsional)</label>
                    <select id="modal-metode" name="metode_pembayaran" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                        <option value="">-</option>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="modal-keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                <textarea id="modal-keterangan" name="keterangan" rows="3" maxlength="1000" placeholder="Contoh: Beli tinta printer, bayar listrik..."
                    class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close-modal', 'input-pengeluaran')"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-semibold">
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </x-modal>
    @endcan
</x-app-layout>
