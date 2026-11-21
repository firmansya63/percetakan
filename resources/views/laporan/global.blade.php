<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Laporan Keuangan Global
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Konsolidasi pemasukan, HPP, pengeluaran operasional, dan piutang</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('laporan.global.export', request()->query()) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Excel (.csv)
                </a>
                <button onclick="window.print()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium print:hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.25 7.034V3.375" />
                    </svg>
                    Export PDF (Cetak)
                </button>
            </div>
        </div>
    </x-slot>

    <div class="pt-2 pb-6 sm:pt-4 sm:pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @include('cashflow._tabs')

            {{-- Filter --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm print:hidden">
                <form method="GET" action="{{ route('laporan.global') }}" class="space-y-4">
                    <div class="flex flex-wrap gap-2">
                        @php
                            $presets = [
                                'hari_ini' => 'Hari Ini',
                                'bulan_ini' => 'Bulan Ini',
                                'tahun_ini' => 'Tahun Ini',
                            ];
                            $currentPreset = request('preset');
                        @endphp
                        @foreach($presets as $key => $label)
                            <button type="submit" name="preset" value="{{ $key }}"
                                class="px-4 py-2 rounded-lg text-sm font-semibold transition
                                       {{ ($currentPreset === $key) || (! $currentPreset && $key === 'bulan_ini') ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori Produk</label>
                            <select name="kategori_produk" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriProduk as $key => $label)
                                    <option value="{{ $key }}" @selected(request('kategori_produk') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-md text-sm">Filter</button>
                            <a href="{{ route('laporan.global') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Kop Perusahaan (hanya saat print) --}}
            <div class="hidden print:block bg-white p-6 rounded-lg shadow-sm border">
                <div class="text-center border-b-2 border-dashed pb-4 mb-4">
                    <x-company-header class="mb-3 justify-center text-center" />
                    <h1 class="text-2xl font-bold uppercase tracking-wider">Laporan Keuangan Global</h1>
                    <p class="text-xs text-gray-500">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Total Omzet Kotor</span>
                    <h4 class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($summary['total_omzet_kotor'], 0, ',', '.') }}</h4>
                    <p class="text-[11px] text-gray-400 mt-1">Transaksi + piutang</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Kas Masuk Riil</span>
                    <h4 class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($summary['kas_masuk_riil'], 0, ',', '.') }}</h4>
                    <p class="text-[11px] text-gray-400 mt-1">Cash + Transfer + QRIS</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Pengeluaran & HPP</span>
                    <h4 class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($summary['total_beban'], 0, ',', '.') }}</h4>
                    <p class="text-[11px] text-gray-400 mt-1">HPP + Operasional</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 {{ $summary['laba_bersih'] >= 0 ? 'border-emerald-500' : 'border-orange-500' }}">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Laba Bersih</span>
                    <h4 class="text-xl font-bold {{ $summary['laba_bersih'] >= 0 ? 'text-emerald-600' : 'text-orange-600' }} mt-1">Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}</h4>
                    <p class="text-[11px] text-gray-400 mt-1">Kas masuk - beban</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border-l-4 border-purple-500">
                    <span class="text-xs text-gray-500 uppercase font-semibold">Piutang Pelanggan</span>
                    <h4 class="text-xl font-bold text-purple-600 mt-1">Rp {{ number_format($summary['total_piutang'], 0, ',', '.') }}</h4>
                    <p class="text-[11px] text-gray-400 mt-1">Sisa tagihan belum lunas</p>
                </div>
            </div>

            {{-- Grafik Tren --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Tren Pemasukan vs Pengeluaran</h3>
                @php
                    $maxVal = max(1, $tren->max('masuk'), $tren->max('keluar'));
                @endphp
                <div class="space-y-2">
                    @foreach($tren as $item)
                        <div class="flex items-center gap-3">
                            <span class="w-14 text-xs text-gray-500 shrink-0">{{ $item['label'] }}</span>
                            <div class="flex-1 space-y-1">
                                <div class="flex items-center gap-2">
                                    <div class="h-3 rounded bg-green-500" style="width: {{ max(2, ($item['masuk'] / $maxVal) * 100) }}%"></div>
                                    <span class="text-[10px] text-green-600 whitespace-nowrap">{{ number_format($item['masuk'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-3 rounded bg-red-500" style="width: {{ max(2, ($item['keluar'] / $maxVal) * 100) }}%"></div>
                                    <span class="text-[10px] text-red-600 whitespace-nowrap">{{ number_format($item['keluar'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex items-center gap-4 mt-4 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-green-500 inline-block"></span> Pemasukan</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-500 inline-block"></span> Pengeluaran</span>
                </div>
            </div>

            {{-- Laporan Laba Rugi --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Laporan Laba Rugi Global</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y dark:divide-gray-700">
                            {{-- Pendapatan --}}
                            <tr class="bg-gray-50 dark:bg-gray-700/40">
                                <td class="p-3 font-bold text-gray-900 dark:text-gray-100" colspan="2">PENDAPATAN</td>
                            </tr>
                            <tr>
                                <td class="p-3 pl-6">Penjualan / Omzet Kotor</td>
                                <td class="p-3 text-right font-semibold">Rp {{ number_format($summary['total_omzet_kotor'], 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-700/40">
                                <td class="p-3 font-bold text-gray-900 dark:text-gray-100">Total Pendapatan</td>
                                <td class="p-3 text-right font-bold text-green-600">Rp {{ number_format($summary['total_omzet_kotor'], 0, ',', '.') }}</td>
                            </tr>

                            {{-- HPP --}}
                            <tr class="bg-gray-50 dark:bg-gray-700/40">
                                <td class="p-3 font-bold text-gray-900 dark:text-gray-100" colspan="2">HARGA POKOK PRODUKSI (HPP)</td>
                            </tr>
                            @forelse($hpp['per_kategori'] as $kategori => $total)
                                <tr>
                                    <td class="p-3 pl-6">Bahan — {{ ucfirst($kategori) }}</td>
                                    <td class="p-3 text-right text-red-600">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td class="p-3 pl-6 text-gray-400 italic" colspan="2">Tidak ada HPP tercatat</td></tr>
                            @endforelse
                            <tr class="bg-gray-50 dark:bg-gray-700/40">
                                <td class="p-3 font-bold">Total HPP</td>
                                <td class="p-3 text-right font-bold text-red-600">Rp {{ number_format($hpp['total'], 0, ',', '.') }}</td>
                            </tr>

                            {{-- Laba Kotor --}}
                            @php $labaKotor = $summary['total_omzet_kotor'] - $hpp['total']; @endphp
                            <tr class="bg-blue-50 dark:bg-blue-900/20">
                                <td class="p-3 font-bold text-blue-800 dark:text-blue-200">LABA KOTOR</td>
                                <td class="p-3 text-right font-bold text-blue-700 dark:text-blue-300">Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
                            </tr>

                            {{-- Beban Operasional --}}
                            <tr class="bg-gray-50 dark:bg-gray-700/40">
                                <td class="p-3 font-bold text-gray-900 dark:text-gray-100" colspan="2">BEBAN OPERASIONAL</td>
                            </tr>
                            @forelse($opex['per_kategori'] as $kategori => $total)
                                <tr>
                                    <td class="p-3 pl-6">{{ $kategori }}</td>
                                    <td class="p-3 text-right text-red-600">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td class="p-3 pl-6 text-gray-400 italic" colspan="2">Tidak ada beban operasional tercatat</td></tr>
                            @endforelse
                            <tr class="bg-gray-50 dark:bg-gray-700/40">
                                <td class="p-3 font-bold">Total Beban Operasional</td>
                                <td class="p-3 text-right font-bold text-red-600">Rp {{ number_format($opex['total'], 0, ',', '.') }}</td>
                            </tr>

                            {{-- Laba Bersih --}}
                            <tr class="bg-emerald-50 dark:bg-emerald-900/20">
                                <td class="p-3 font-bold text-emerald-800 dark:text-emerald-200 text-base">LABA BERSIH</td>
                                <td class="p-3 text-right font-bold text-emerald-700 dark:text-emerald-300 text-base">Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rekonsiliasi Kas & Bank --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Rekonsiliasi Kas & Bank</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">Saldo</th>
                                <th class="p-3 text-right">Masuk</th>
                                <th class="p-3 text-right">Keluar</th>
                                <th class="p-3 text-right">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @foreach($rekonsiliasi as $metode => $data)
                                <tr>
                                    <td class="p-3 font-semibold">
                                        @if($metode === 'tunai') 💵 Laci Kasir (Tunai)
                                        @elseif($metode === 'transfer') 🏦 Rekening (Transfer)
                                        @else 📱 E-Wallet (QRIS)
                                        @endif
                                    </td>
                                    <td class="p-3 text-right text-green-600">Rp {{ number_format($data['masuk'], 0, ',', '.') }}</td>
                                    <td class="p-3 text-right text-red-600">Rp {{ number_format($data['keluar'], 0, ',', '.') }}</td>
                                    <td class="p-3 text-right font-bold {{ $data['saldo'] >= 0 ? 'text-gray-900 dark:text-gray-100' : 'text-red-600' }}">
                                        Rp {{ number_format($data['saldo'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Piutang --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Piutang Usaha (Belum Lunas)</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">No. Nota</th>
                                <th class="p-3">Pelanggan</th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3 text-right">Total</th>
                                <th class="p-3 text-right">Terbayar</th>
                                <th class="p-3 text-right">Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($piutang['rows'] as $row)
                                <tr>
                                    <td class="p-3 font-mono font-bold">{{ $row->nomor_nota }}</td>
                                    <td class="p-3">{{ $row->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                                    <td class="p-3 text-xs">{{ $row->tanggal_pesanan?->format('d/m/Y') }}</td>
                                    <td class="p-3 text-right">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right text-green-600">Rp {{ number_format($row->total_dp, 0, ',', '.') }}</td>
                                    <td class="p-3 text-right font-bold text-red-600">Rp {{ number_format($row->sisa_bayar, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-4 text-center text-gray-500">Tidak ada piutang pada periode ini.</td></tr>
                            @endforelse
                            @if($piutang['rows']->isNotEmpty())
                                <tr class="bg-gray-50 dark:bg-gray-700/40 font-bold">
                                    <td class="p-3" colspan="5">Total Piutang</td>
                                    <td class="p-3 text-right text-red-600">Rp {{ number_format($piutang['total'], 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
