<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Data Pelanggan
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm flex flex-col md:flex-row justify-between gap-4 items-center">
                <a href="{{ route('pelanggan.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded text-sm">
                    + Tambah Pelanggan
                </a>

                <form method="GET" action="{{ route('pelanggan.index') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. telp..." class="text-sm border-gray-300 rounded-md">
                    <button type="submit" class="px-3 py-2 bg-gray-700 text-white text-xs font-semibold rounded">Cari</button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="p-3">Nama Pelanggan</th>
                                <th class="p-3">Telepon / WA</th>
                                <th class="p-3">Alamat</th>
                                <th class="p-3">Catatan</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($pelanggans as $cust)
                                <tr>
                                    <td class="p-3 font-semibold">{{ $cust->nama_pelanggan }}</td>
                                    <td class="p-3">{{ $cust->telepon ?? '-' }}</td>
                                    <td class="p-3">{{ $cust->alamat ?? '-' }}</td>
                                    <td class="p-3 text-xs text-gray-500">{{ $cust->catatan ?? '-' }}</td>
                                    <td class="p-3 text-right space-x-2">
                                        <a href="{{ route('pelanggan.edit', $cust) }}" class="text-blue-600 hover:underline">Edit</a>
                                        <form action="{{ route('pelanggan.destroy', $cust) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pelanggan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-4 text-center text-gray-500">Belum ada data pelanggan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $pelanggans->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
