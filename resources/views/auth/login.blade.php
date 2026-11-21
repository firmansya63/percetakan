<x-guest-layout>
    <div class="min-h-screen flex" x-data="{ showPassword: false }">

        {{-- Left Panel — Branding --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800">
            {{-- Decorative circles --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full animate-float"></div>
            <div class="absolute top-1/3 -right-16 w-56 h-56 bg-white/5 rounded-full animate-float animation-delay-200"></div>
            <div class="absolute -bottom-10 left-1/4 w-40 h-40 bg-white/10 rounded-full animate-float animation-delay-400"></div>

            {{-- Pattern overlay --}}
            <div class="absolute inset-0 opacity-[0.03]"
                 style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 text-white">
                {{-- Company Logo / Fallback Icon --}}
                <div class="mb-8 animate-slide-in-left">
                    @if(!empty($companyProfile?->logo))
                        <div class="w-24 h-24 bg-white rounded-2xl p-2.5 flex items-center justify-center shadow-2xl overflow-hidden">
                            <img src="{{ asset('storage/' . $companyProfile->logo) }}" alt="{{ $companyProfile->name ?? config('app.name') }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-24 h-24 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-2xl">
                            <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.25 7.034V3.375" />
                            </svg>
                        </div>
                    @endif
                </div>

                <h1 class="text-4xl font-bold mb-4 text-center animate-slide-in-left animation-delay-100">
                    {{ $companyProfile->name ?? config('app.name', 'Percetakan') }}
                </h1>
                <p class="text-lg text-blue-100 text-center max-w-sm leading-relaxed animate-slide-in-left animation-delay-200">
                    Solusi cetak terpercaya untuk kebutuhan bisnis dan personal Anda
                </p>

                {{-- Feature list --}}
                <div class="mt-12 space-y-4 animate-slide-in-left animation-delay-300">
                    <div class="flex items-center gap-3 text-blue-100">
                        <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span>Kelola pesanan dengan mudah</span>
                    </div>
                    <div class="flex items-center gap-3 text-blue-100">
                        <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span>Pantau progres secara real-time</span>
                    </div>
                    <div class="flex items-center gap-3 text-blue-100">
                        <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span>Laporan & analisis lengkap</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel — Login Form --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-6 py-12">
            <div class="w-full max-w-md">

                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8 animate-fade-in-up">
                    @if(!empty($companyProfile?->logo))
                        <div class="inline-flex w-20 h-20 bg-white dark:bg-gray-800 rounded-2xl items-center justify-center p-2.5 shadow-lg border border-gray-100 dark:border-gray-700 mb-4 overflow-hidden">
                            <img src="{{ asset('storage/' . $companyProfile->logo) }}" alt="{{ $companyProfile->name ?? config('app.name') }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="inline-flex w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl items-center justify-center shadow-lg mb-4">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.25 7.034V3.375" />
                            </svg>
                        </div>
                    @endif
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $companyProfile->name ?? config('app.name', 'Percetakan') }}</h2>
                </div>

                {{-- Welcome text --}}
                <div class="mb-8 animate-fade-in-up">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Selamat Datang! 👋</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Masukkan akun Anda untuk melanjutkan</p>
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email / Username --}}
                    <div class="animate-fade-in-up animation-delay-100">
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Email atau Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <input
                                id="email"
                                type="text"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="owner@percetakan.com atau owner"
                                class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div class="animate-fade-in-up animation-delay-200">
                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full pl-12 pr-12 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            >
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between animate-fade-in-up animation-delay-300">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-800 transition"
                            >
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <div class="animate-fade-in-up animation-delay-400">
                        <button
                            type="submit"
                            class="w-full py-3 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-semibold text-sm tracking-wide shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                        >
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                Masuk
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Footer --}}
                <p class="mt-8 text-center text-sm text-gray-400 dark:text-gray-500 animate-fade-in-up animation-delay-400">
                    &copy; {{ date('Y') }} {{ $companyProfile->name ?? config('app.name', 'Percetakan') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
