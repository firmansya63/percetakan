<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah User
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <form method="POST" action="{{ route('user.store') }}" class="space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                            <ul class="list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="name" class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Nama *</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                               class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Email *</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Role *</label>
                        <select id="role" name="role" required
                                class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('role') === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Role menentukan menu dan fitur yang dapat diakses user.</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Password *</label>
                        <input id="password" type="password" name="password" required
                               class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Minimal 8 karakter.</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold mb-1 text-gray-700 dark:text-gray-300">Konfirmasi Password *</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="w-full text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('user.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded text-sm">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
