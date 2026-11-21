@php
    $tabs = [
        'transaksi' => [
            'label' => 'Transaksi',
            'route' => 'cashflow.index',
            'active' => request()->routeIs('cashflow.index', 'cashflow.create', 'cashflow.edit'),
        ],
        'global' => [
            'label' => 'Laporan Global',
            'route' => 'laporan.keuangan',
            'active' => request()->routeIs('laporan.keuangan'),
        ],
        'shift' => [
            'label' => 'Laporan Shift',
            'route' => 'laporan.shift',
            'active' => request()->routeIs('laporan.shift'),
        ],
    ];
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-1.5 flex flex-wrap gap-1.5">
    @foreach($tabs as $tab)
        <a href="{{ route($tab['route']) }}"
           class="flex-1 min-w-[120px] inline-flex items-center justify-center px-4 py-2.5 rounded-md text-sm font-semibold transition-colors
                  {{ $tab['active'] ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
