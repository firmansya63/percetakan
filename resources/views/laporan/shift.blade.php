<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Keuangan per Shift (Owner Only)
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Filter Shift -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="GET" action="{{ route('laporan.shift') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal:</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Shift:</label>
                        <select name="shift" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm">
                            @foreach(\App\Http\Controllers\ShiftReportController::SHIFTS as $key => $s)
                                <option value="{{ $key }}" {{ $shift == $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md text-sm">
                            Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Uang Masuk</span>
                    <h4 class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Uang Muka (DP)</span>
                    <h4 class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($totalDP, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-teal-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Pelunasan</span>
                    <h4 class="text-2xl font-bold text-teal-600 mt-1">Rp {{ number_format($totalPelunasan, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Uang Keluar</span>
                    <h4 class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Saldo Shift</span>
                    <h4 class="text-2xl font-bold {{ $saldo >= 0 ? 'text-indigo-600' : 'text-red-600' }} mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h4>
                </div>
            </div>

            <div class="text-sm text-gray-500 dark:text-gray-400">
                Periode: <strong>{{ $start->format('d/m/Y H:i') }}</strong> s.d. <strong>{{ $end->format('d/m/Y H:i') }}</strong>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Ringkasan per Metode Pembayaran -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Uang Masuk per Metode</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="p-3">Metode</th>
                                    <th class="p-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-700">
                                @forelse($perMetode as $m)
                                    <tr>
                                        <td class="p-3 uppercase font-semibold">{{ $m->metode }}</td>
                                        <td class="p-3 text-right font-bold text-green-600">Rp {{ number_format($m->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="p-4 text-center text-gray-500">Tidak ada transaksi masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ringkasan per Kategori -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Rincian per Kategori</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="p-3">Tipe</th>
                                    <th class="p-3">Kategori</th>
                                    <th class="p-3 text-right">Jumlah Transaksi</th>
                                    <th class="p-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-700">
                                @forelse($perKategori as $k)
                                    <tr>
                                        <td class="p-3">
                                            <span class="px-2 py-0.5 text-xs rounded font-bold {{ $k->tipe === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ strtoupper($k->tipe) }}
                                            </span>
                                        </td>
                                        <td class="p-3">{{ $k->kategori }}</td>
                                        <td class="p-3 text-right">{{ $k->jumlah_transaksi }}</td>
                                        <td class="p-3 text-right font-bold {{ $k->tipe === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                            Rp {{ number_format($k->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="p-4 text-center text-gray-500">Tidak ada transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ringkasan per Kasir -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Rekap per Kasir</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">Kasir</th>
                                <th class="p-3 text-right">Jumlah Transaksi</th>
                                <th class="p-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($perKasir as $k)
                                <tr>
                                    <td class="p-3 font-semibold">{{ $k->user->name ?? 'System' }}</td>
                                    <td class="p-3 text-right">{{ $k->jumlah_transaksi }}</td>
                                    <td class="p-3 text-right font-bold text-gray-800 dark:text-gray-200">Rp {{ number_format($k->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-4 text-center text-gray-500">Tidak ada transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rincian Transaksi -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Rincian Transaksi Shift</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">Waktu</th>
                                <th class="p-3">Tipe</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Metode</th>
                                <th class="p-3">Kasir</th>
                                <th class="p-3">Keterangan</th>
                                <th class="p-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($cashFlows as $cf)
                                <tr>
                                    <td class="p-3 text-xs">{{ $cf->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 text-xs rounded font-bold {{ $cf->tipe === 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ strtoupper($cf->tipe) }}
                                        </span>
                                    </td>
                                    <td class="p-3">{{ $cf->kategori }}</td>
                                    <td class="p-3 uppercase text-xs font-semibold">{{ $cf->metode_pembayaran ?? '-' }}</td>
                                    <td class="p-3 text-xs">{{ $cf->user->name ?? 'System' }}</td>
                                    <td class="p-3 text-xs text-gray-500">{{ $cf->keterangan }}</td>
                                    <td class="p-3 text-right font-bold {{ $cf->tipe === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($cf->jumlah, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="p-4 text-center text-gray-500">Tidak ada transaksi pada shift ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
