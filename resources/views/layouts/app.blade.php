<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $companyProfile->name ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        },
                    },
                },
            };
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>[x-cloak]{display:none !important;}</style>
    </head>
    <body class="font-sans antialiased">
        @php
            $linkBase = 'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors';
            $linkActive = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300';
            $linkInactive = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100';
        @endphp

        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Auto-hide Sidebar -->
            @include('layouts.navigation')

            <!-- Top bar -->
            <div class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center h-16 px-4 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 focus:outline-none" aria-label="Buka menu">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="ml-3 flex items-center gap-2 min-w-0">
                        <x-application-logo class="block h-8 w-auto shrink-0 fill-current text-gray-800 dark:text-gray-200" />
                        <span class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $companyProfile->name ?? config('app.name', 'Percetakan') }}</span>
                    </a>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex flex-col min-h-screen">
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
