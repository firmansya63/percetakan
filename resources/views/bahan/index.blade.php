<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Bahan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @can('create_bahan')
                    <a href="{{ route('bahan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 mb-4">
                        Tambah Bahan
                    </a>
                    @endcan
                    
                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bahans as $bahan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $bahan->nama_bahan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($bahan->jenis_cetakan) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $bahan->satuan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($bahan->harga_jual, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $bahan->stok }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @can('edit_bahan')
                                    <a href="{{ route('bahan.edit', $bahan) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                    @endcan
                                    @can('delete_bahan')
                                    <form action="{{ route('bahan.destroy', $bahan) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{ $bahans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>