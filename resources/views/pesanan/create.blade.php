<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Buat Pesanan & Kalkulasi</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Input detail pesanan cetak dan hitung rincian biaya otomatis</p>
            </div>
            <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-6xl mx-auto">

        {{-- Alerts --}}
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 shadow-sm">
                <div class="flex items-center gap-2 mb-2 font-bold text-sm">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    Mohon periksa kesalahan input berikut:
                </div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('pesanan.store') }}" method="POST" id="orderForm" class="space-y-6">
            @csrf

            {{-- Card 1: Data Pelanggan & Jadwal --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700/60">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm shrink-0">
                        1
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Informasi Pelanggan & Jadwal</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pilih pelanggan lama atau input walk-in dan tentukan estimasi selesai</p>
                    </div>
                </div>

                {{-- Sumber Transaksi --}}
                <div class="mb-5 p-4 rounded-xl bg-gray-50/70 dark:bg-gray-750 border border-gray-100 dark:border-gray-700/60">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">
                        Sumber Transaksi <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="flex-1 inline-flex items-start gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 transition">
                            <input type="radio" name="sumber_transaksi" value="on_the_spot" {{ old('sumber_transaksi', 'on_the_spot') === 'on_the_spot' ? 'checked' : '' }} class="mt-0.5 rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm">
                                <span class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                                    🏪 On The Spot
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5">Customer datang langsung ke tempat percetakan</span>
                            </span>
                        </label>
                        <label class="flex-1 inline-flex items-start gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 transition">
                            <input type="radio" name="sumber_transaksi" value="online" {{ old('sumber_transaksi') === 'online' ? 'checked' : '' }} class="mt-0.5 rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm">
                                <span class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                                    🌐 Online
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5">Customer kirim file / pesan via email</span>
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Detail Transaksi Online --}}
                <div id="onlineDetailBlock" class="mb-5 p-4 rounded-xl bg-sky-50/60 dark:bg-sky-950/30 border border-sky-200 dark:border-sky-800/60 hidden">
                    <label class="block text-xs font-bold uppercase tracking-wider text-sky-700 dark:text-sky-300 mb-2">
                        📧 Detail Pengirim Email
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Email Pengirim
                            </label>
                            <input type="email" name="email_pengirim" id="inputEmailPengirim" value="{{ old('email_pengirim') }}" placeholder="contoh@email.com" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Subjek / No. Referensi Email
                            </label>
                            <input type="text" name="referensi_email" value="{{ old('referensi_email') }}" placeholder="Contoh: Re: Pesanan Banner - INV202611170001" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    {{-- Pilih Pelanggan --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                            Pilih Pelanggan Terdaftar
                        </label>
                        <select name="pelanggan_id" id="pelangganSelect" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            <option value="">-- Pelanggan Baru / Walk-in --</option>
                            @foreach($pelanggans as $p)
                                <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pelanggan }} ({{ $p->telepon ?? 'Tanpa Telp' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nama Baru --}}
                    <div id="newCustomerBlock">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                            Nama Pelanggan Baru
                        </label>
                        <input type="text" name="nama_pelanggan_baru" value="{{ old('nama_pelanggan_baru') }}" placeholder="Contoh: Budi Santoso / PT Maju" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                            No. WhatsApp / Telepon
                        </label>
                        <input type="text" name="telepon_pelanggan_baru" value="{{ old('telepon_pelanggan_baru') }}" placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>

                    {{-- Tanggal Pesan & Ambil --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Tanggal Pesanan <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="tanggal_pesanan" value="{{ old('tanggal_pesanan', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Estimasi Ambil
                            </label>
                            <input type="date" name="tanggal_ambil" value="{{ old('tanggal_ambil', date('Y-m-d', strtotime('+1 day'))) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Items & Dynamic Calculation --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center justify-between gap-4 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700/60">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shrink-0">
                            2
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Item Cetakan & Finishing</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Rincian produk cetak, spesifikasi ukuran, dan opsi finishing</p>
                        </div>
                    </div>
                    <button type="button" id="addItemBtn" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/20 hover:shadow transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Item
                    </button>
                </div>

                {{-- Items Container --}}
                <div id="itemsContainer" class="space-y-4">
                    {{-- Dynamically injected via JS --}}
                </div>

                {{-- Jasa Tambahan (Optional) --}}
                <div class="mt-6 p-5 bg-gradient-to-br from-amber-50/70 to-orange-50/50 dark:from-gray-750 dark:to-gray-700 rounded-2xl border border-amber-200/80 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xs font-bold">
                            +
                        </span>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Jasa Tambahan (Opsional)</h4>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Jasa Design --}}
                        <div class="p-3.5 rounded-xl bg-white dark:bg-gray-800 border border-amber-100 dark:border-gray-700 shadow-sm space-y-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="jasaDesignCheck" name="jasa_design_enabled" class="jasa-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" {{ old('jasa_design_enabled') ? 'checked' : '' }}>
                                <span class="ml-2 text-xs font-bold text-gray-800 dark:text-gray-200">Jasa Desain</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-gray-400 font-semibold pointer-events-none">Rp</span>
                                <input type="number" name="jasa_design" id="inputJasaDesign" value="{{ old('jasa_design', 0) }}" min="0" step="any" placeholder="0" class="jasa-price w-full pl-9 pr-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-xs font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-amber-500">
                            </div>
                        </div>

                        {{-- Jasa Finishing --}}
                        <div class="p-3.5 rounded-xl bg-white dark:bg-gray-800 border border-amber-100 dark:border-gray-700 shadow-sm space-y-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="jasaFinishingCheck" name="jasa_finishing_enabled" class="jasa-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" {{ old('jasa_finishing_enabled') ? 'checked' : '' }}>
                                <span class="ml-2 text-xs font-bold text-gray-800 dark:text-gray-200">Jasa Pasang / Finishing</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-gray-400 font-semibold pointer-events-none">Rp</span>
                                <input type="number" name="jasa_finishing" id="inputJasaFinishing" value="{{ old('jasa_finishing', 0) }}" min="0" step="any" placeholder="0" class="jasa-price w-full pl-9 pr-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-xs font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-amber-500">
                            </div>
                        </div>

                        {{-- Jasa Potong --}}
                        <div class="p-3.5 rounded-xl bg-white dark:bg-gray-800 border border-amber-100 dark:border-gray-700 shadow-sm space-y-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="jasaPotongCheck" name="jasa_potong_enabled" class="jasa-check rounded border-gray-300 text-amber-600 focus:ring-amber-500" {{ old('jasa_potong_enabled') ? 'checked' : '' }}>
                                <span class="ml-2 text-xs font-bold text-gray-800 dark:text-gray-200">Jasa Potong Custom</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-gray-400 font-semibold pointer-events-none">Rp</span>
                                <input type="number" name="jasa_potong" id="inputJasaPotong" value="{{ old('jasa_potong', 0) }}" min="0" step="any" placeholder="0" class="jasa-price w-full pl-9 pr-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-xs font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-amber-500">
                            </div>
                        </div>
                    </div>

                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-3">Centang dan tentukan biaya untuk jasa tambahan, otomatis masuk ke kalkulasi nota.</p>
                </div>
            </div>

            {{-- Card 3: Pembayaran & Rangkuman Tagihan --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700/60">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-sm shrink-0">
                        3
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Pembayaran & Penerbitan Nota</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Atur uang muka, metode pembayaran, serta catatan instruksi kerja</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    {{-- Form Pembayaran --}}
                    <div class="lg:col-span-7 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Uang Muka / DP (Rp)
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-semibold text-gray-400 pointer-events-none">Rp</span>
                                <input type="number" name="dp" id="inputDP" value="{{ old('dp', 0) }}" min="0" step="any" placeholder="0" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm font-semibold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                            </div>
                            <span class="text-[11px] text-gray-400 mt-1 block">Kosongkan/isi 0 jika belum bayar, isi penuh untuk bayar lunas langsung.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Metode Pembayaran
                            </label>
                            <select name="metode_pembayaran" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                                <option value="tunai" {{ old('metode_pembayaran', 'tunai') == 'tunai' ? 'selected' : '' }}>💵 Tunai / Cash</option>
                                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
                                <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>📱 QRIS</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1.5">
                                Catatan Khusus untuk Operator Produksi
                            </label>
                            <textarea name="catatan" rows="3" placeholder="Contoh: Mata ayam tiap 1 meter, warna cerah, potong pas garis..." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 text-sm text-gray-800 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    {{-- Summary Calculation Box --}}
                    <div class="lg:col-span-5 bg-gradient-to-br from-gray-50 to-blue-50/30 dark:from-gray-750 dark:to-gray-800 p-6 rounded-2xl border border-gray-200/80 dark:border-gray-700 flex flex-col justify-between">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Ringkasan Tagihan</h4>
                            
                            <div class="space-y-3.5">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Total Tagihan:</span>
                                    <span class="text-2xl font-black text-gray-900 dark:text-white" id="totalTagihanDisplay">Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Uang Muka (DP):</span>
                                    <span class="text-base font-bold text-blue-600 dark:text-blue-400" id="dpDisplay">Rp 0</span>
                                </div>
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center text-sm">
                                    <span class="font-bold text-gray-800 dark:text-gray-200">Sisa Bayar:</span>
                                    <span class="text-2xl font-black text-rose-600 dark:text-rose-400" id="sisaBayarDisplay">Rp 0</span>
                                </div>
                                <div class="text-right pt-1" id="statusBayarBadge">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                                        BELUM LUNAS
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-700 flex items-center gap-3">
                            <a href="{{ route('pesanan.index') }}" class="w-1/3 py-3 text-center rounded-xl border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold text-sm transition">
                                Batal
                            </a>
                            <button type="submit" class="w-2/3 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-sm shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 transition-all duration-200">
                                Simpan & Cetak Nota
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    {{-- Client-side calculation script --}}
    <script>
        const materials = @json($bahans);
        const finishings = @json($finishings);
        let itemCount = 0;

        function formatRupiah(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        function createItemHtml(idx) {
            let materialOptions = `<option value="">-- Pilih Bahan Cetak --</option>`;
            materials.forEach(m => {
                materialOptions += `<option value="${m.id}" data-type="${m.jenis_cetakan}" data-price="${m.harga_jual}" data-unit="${m.satuan}">
                    ${m.nama_bahan} - Rp ${Number(m.harga_jual).toLocaleString('id-ID')} / ${m.satuan} (${m.jenis_cetakan})
                </option>`;
            });

            let finishingCheckboxes = '';
            finishings.forEach(f => {
                finishingCheckboxes += `
                    <label class="inline-flex items-center text-xs mr-3 mb-1.5 p-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 cursor-pointer transition">
                        <input type="checkbox" name="items[${idx}][finishings][]" value="${f.id}" data-price="${f.harga}" class="item-finishing-check rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 font-medium text-gray-700 dark:text-gray-300">${f.nama_finishing} <span class="text-blue-600 dark:text-blue-400 font-bold">(+Rp ${Number(f.harga).toLocaleString('id-ID')})</span></span>
                    </label>
                `;
            });

            return `
                <div class="item-card rounded-2xl p-5 bg-gray-50/70 dark:bg-gray-750 border border-gray-200 dark:border-gray-700 space-y-4" data-index="${idx}">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                Item #${idx + 1}
                            </span>
                        </div>
                        ${idx > 0 ? `<button type="button" class="text-xs font-semibold text-rose-600 hover:text-rose-800 hover:underline remove-item-btn flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Hapus Item
                        </button>` : ''}
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1">Pilih Bahan <span class="text-rose-500">*</span></label>
                            <select name="items[${idx}][bahan_id]" class="item-bahan w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-medium text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" required>
                                ${materialOptions}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1">Nama Item / Pekerjaan <span class="text-rose-500">*</span></label>
                            <input type="text" name="items[${idx}][nama_item]" placeholder="Contoh: Banner Warung 3x1" class="item-name w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-1">Jumlah (Qty) <span class="text-rose-500">*</span></label>
                            <input type="number" name="items[${idx}][qty]" value="1" min="1" class="item-qty w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs font-bold text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" required>
                        </div>
                    </div>

                    {{-- Banner Dimension Block --}}
                    <div class="banner-dimension-block hidden grid-cols-2 gap-3 p-4 bg-blue-50/70 dark:bg-blue-950/30 rounded-xl border border-blue-200 dark:border-blue-800/60">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-200 mb-1">Panjang (Meter) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0.1" name="items[${idx}][panjang]" class="item-panjang w-full px-3 py-2 rounded-xl border border-blue-200 dark:border-blue-800 bg-white dark:bg-gray-800 text-xs font-bold text-gray-800 dark:text-gray-100" placeholder="Contoh: 3">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-200 mb-1">Lebar (Meter) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0.1" name="items[${idx}][lebar]" class="item-lebar w-full px-3 py-2 rounded-xl border border-blue-200 dark:border-blue-800 bg-white dark:bg-gray-800 text-xs font-bold text-gray-800 dark:text-gray-100" placeholder="Contoh: 1">
                        </div>
                    </div>

                    {{-- Finishing Options --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">Finishing Tambahan</label>
                        <div class="flex flex-wrap">
                            ${finishingCheckboxes}
                        </div>
                    </div>

                    {{-- Subtotal & Formula --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Kalkulasi: <span class="formula-info font-medium text-gray-700 dark:text-gray-300">Pilih bahan...</span>
                        </span>
                        <div class="flex items-center gap-2 sm:justify-end">
                            <span class="text-xs text-gray-500">Subtotal Item:</span>
                            <span class="font-extrabold text-base text-emerald-600 dark:text-emerald-400 item-subtotal-display">Rp 0</span>
                        </div>
                    </div>
                </div>
            `;
        }

        function calculateItem(card) {
            const bahanSelect = card.querySelector('.item-bahan');
            const selectedOpt = bahanSelect.options[bahanSelect.selectedIndex];
            const qtyInput = card.querySelector('.item-qty');
            const panjangInput = card.querySelector('.item-panjang');
            const lebarInput = card.querySelector('.item-lebar');
            const formulaInfo = card.querySelector('.formula-info');
            const subtotalDisplay = card.querySelector('.item-subtotal-display');

            if (!selectedOpt || !selectedOpt.value) {
                formulaInfo.innerText = 'Pilih bahan terlebih dahulu';
                subtotalDisplay.innerText = 'Rp 0';
                return 0;
            }

            const type = selectedOpt.getAttribute('data-type');
            const price = parseFloat(selectedOpt.getAttribute('data-price')) || 0;
            const qty = parseInt(qtyInput.value) || 1;

            let subtotalBahan = 0;
            if (type === 'banner') {
                const panjang = parseFloat(panjangInput.value) || 0;
                const lebar = parseFloat(lebarInput.value) || 0;
                const luas = panjang * lebar;
                subtotalBahan = luas * qty * price;
                formulaInfo.innerText = `(${panjang}m x ${lebar}m) x ${qty} pcs x Rp ${price.toLocaleString('id-ID')}`;
            } else {
                subtotalBahan = qty * price;
                formulaInfo.innerText = `${qty} pcs x Rp ${price.toLocaleString('id-ID')}`;
            }

            // Total finishings
            let totalFinishing = 0;
            const checkedFinishings = card.querySelectorAll('.item-finishing-check:checked');
            checkedFinishings.forEach(chk => {
                const finPrice = parseFloat(chk.getAttribute('data-price')) || 0;
                totalFinishing += (finPrice * qty);
            });

            if (totalFinishing > 0) {
                formulaInfo.innerText += ` + Finishing Rp ${totalFinishing.toLocaleString('id-ID')}`;
            }

            const subtotalItem = subtotalBahan + totalFinishing;
            subtotalDisplay.innerText = formatRupiah(subtotalItem);
            return subtotalItem;
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.item-card').forEach(card => {
                grandTotal += calculateItem(card);
            });

            // Tambahkan jasa tambahan jika dicentang
            document.querySelectorAll('.jasa-check').forEach(chk => {
                if (chk.checked) {
                    const priceInput = chk.closest('div').querySelector('.jasa-price');
                    if (priceInput) {
                        grandTotal += parseFloat(priceInput.value) || 0;
                    }
                }
            });

            document.getElementById('totalTagihanDisplay').innerText = formatRupiah(grandTotal);

            const dpInput = document.getElementById('inputDP');
            const dp = parseFloat(dpInput.value) || 0;
            document.getElementById('dpDisplay').innerText = formatRupiah(dp);

            const sisa = Math.max(0, grandTotal - dp);
            document.getElementById('sisaBayarDisplay').innerText = formatRupiah(sisa);

            const statusBadge = document.getElementById('statusBayarBadge');
            if (grandTotal > 0 && sisa <= 0) {
                statusBadge.innerHTML = '<span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">LUNAS</span>';
            } else {
                statusBadge.innerHTML = '<span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">BELUM LUNAS</span>';
            }
        }

        function attachEvents(card) {
            const bahanSelect = card.querySelector('.item-bahan');
            const bannerBlock = card.querySelector('.banner-dimension-block');

            bahanSelect.addEventListener('change', () => {
                const opt = bahanSelect.options[bahanSelect.selectedIndex];
                const type = opt ? opt.getAttribute('data-type') : '';
                if (type === 'banner') {
                    bannerBlock.classList.remove('hidden');
                    bannerBlock.classList.add('grid');
                } else {
                    bannerBlock.classList.add('hidden');
                    bannerBlock.classList.remove('grid');
                }
                calculateGrandTotal();
            });

            card.querySelectorAll('input').forEach(inp => {
                inp.addEventListener('input', calculateGrandTotal);
                inp.addEventListener('change', calculateGrandTotal);
            });

            const removeBtn = card.querySelector('.remove-item-btn');
            if (removeBtn) {
                removeBtn.addEventListener('click', () => {
                    card.remove();
                    calculateGrandTotal();
                });
            }
        }

        function addNewItem() {
            const container = document.getElementById('itemsContainer');
            const wrapper = document.createElement('div');
            wrapper.innerHTML = createItemHtml(itemCount);
            const card = wrapper.firstElementChild;
            container.appendChild(card);
            attachEvents(card);
            itemCount++;
            calculateGrandTotal();
        }

        document.getElementById('addItemBtn').addEventListener('click', addNewItem);
        document.getElementById('inputDP').addEventListener('input', calculateGrandTotal);

        // Jasa tambahan: hitung ulang saat checkbox/harga berubah
        document.querySelectorAll('.jasa-check').forEach(chk => {
            chk.addEventListener('change', calculateGrandTotal);
        });
        document.querySelectorAll('.jasa-price').forEach(inp => {
            inp.addEventListener('input', calculateGrandTotal);
        });

        // Add first item on load
        addNewItem();

        // Toggle detail transaksi online berdasarkan pilihan sumber transaksi
        const sumberRadios = document.querySelectorAll('input[name="sumber_transaksi"]');
        const onlineDetailBlock = document.getElementById('onlineDetailBlock');

        function toggleOnlineDetail() {
            const selected = document.querySelector('input[name="sumber_transaksi"]:checked');
            if (selected && selected.value === 'online') {
                onlineDetailBlock.classList.remove('hidden');
            } else {
                onlineDetailBlock.classList.add('hidden');
            }
        }

        sumberRadios.forEach(radio => {
            radio.addEventListener('change', toggleOnlineDetail);
        });

        toggleOnlineDetail();
    </script>
</x-app-layout>
