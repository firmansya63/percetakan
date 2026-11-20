<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Pesanan & Kalkulasi Harga
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('pesanan.store') }}" method="POST" id="orderForm" class="space-y-6">
                @csrf

                <!-- Card 1: Data Pelanggan & Tanggal -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">1. Informasi Pemesanan & Pelanggan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">Pilih Pelanggan Terdaftar:</label>
                            <select name="pelanggan_id" id="pelangganSelect" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">
                                <option value="">-- Pelanggan Baru / Walk-in --</option>
                                @foreach($pelanggans as $p)
                                    <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_pelanggan }} ({{ $p->telepon ?? 'No Telp' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="newCustomerBlock">
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">Nama Pelanggan Baru:</label>
                            <input type="text" name="nama_pelanggan_baru" value="{{ old('nama_pelanggan_baru') }}" placeholder="Nama Pelanggan / Perusahaan" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">No. WhatsApp / Telepon:</label>
                            <input type="text" name="telepon_pelanggan_baru" value="{{ old('telepon_pelanggan_baru') }}" placeholder="08xxxxxxxxxx" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">Tanggal Pesanan *:</label>
                                <input type="date" name="tanggal_pesanan" value="{{ old('tanggal_pesanan', date('Y-m-d')) }}" required class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">Estimasi Ambil:</label>
                                <input type="date" name="tanggal_ambil" value="{{ old('tanggal_ambil', date('Y-m-d', strtotime('+1 day'))) }}" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Items & Dynamic Calculation -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">2. Item Cetakan & Kalkulasi Otomatis</h3>
                        <button type="button" id="addItemBtn" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded shadow">
                            + Tambah Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-6">
                        <!-- Item row template injected here by JS -->
                    </div>

                    <!-- Jasa Design (opsional) -->
                    <div class="mt-6 p-4 bg-yellow-50 dark:bg-gray-700 rounded-lg border border-yellow-200 dark:border-gray-600">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="jasaDesignCheck" name="jasa_design_enabled" class="rounded border-gray-300 text-yellow-600" {{ old('jasa_design_enabled') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-semibold text-gray-800 dark:text-gray-200">Termasuk Jasa Design</span>
                        </label>
                        <div id="jasaDesignPriceBlock" class="mt-3 {{ old('jasa_design_enabled') ? '' : 'hidden' }}">
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">Harga Jasa Design (Rp):</label>
                            <input type="number" name="jasa_design" id="inputJasaDesign" value="{{ old('jasa_design', 0) }}" min="0" step="any" placeholder="0" class="w-full sm:w-64 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md">
                            <p class="text-xs text-gray-500 mt-1">Harga diinput manual, akan muncul sebagai item terpisah di invoice & nota.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Pembayaran & Rangkuman Tagihan -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">3. Pembayaran & Catatan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1">Uang Muka / DP (Rp):</label>
                                <input type="number" name="dp" id="inputDP" value="{{ old('dp', 0) }}" min="0" step="any" class="w-full text-sm border-gray-300 rounded-md">
                                <span class="text-xs text-gray-500">Kosongkan jika belum bayar, isi penuh untuk bayar lunas langsung.</span>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1">Metode Pembayaran:</label>
                                <select name="metode_pembayaran" class="w-full text-sm border-gray-300 rounded-md">
                                    <option value="tunai" {{ old('metode_pembayaran', 'tunai') == 'tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                                    <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="qris" {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1">Catatan Tambahan untuk Produksi:</label>
                                <textarea name="catatan" rows="2" placeholder="Contoh: Warna cerah, potong pas garis..." class="w-full text-sm border-gray-300 rounded-md">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        <!-- Summary calculation box -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">Total Tagihan:</span>
                                    <span class="text-xl font-bold text-gray-900 dark:text-gray-100" id="totalTagihanDisplay">Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">Uang Muka (DP):</span>
                                    <span class="text-base font-semibold text-blue-600" id="dpDisplay">Rp 0</span>
                                </div>
                                <div class="flex justify-between items-center text-sm border-t pt-2">
                                    <span class="font-bold text-gray-800 dark:text-gray-200">Sisa Bayar:</span>
                                    <span class="text-xl font-extrabold text-red-600" id="sisaBayarDisplay">Rp 0</span>
                                </div>
                                <div class="text-xs text-right font-semibold" id="statusBayarBadge">
                                    Status: <span class="text-red-500">Belum Lunas</span>
                                </div>
                            </div>

                            <div class="pt-4 flex gap-2">
                                <a href="{{ route('pesanan.index') }}" class="w-1/3 py-2 text-center bg-gray-500 hover:bg-gray-600 text-white font-bold rounded text-sm">
                                    Batal
                                </a>
                                <button type="submit" class="w-2/3 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded text-sm shadow">
                                    Simpan & Terbitkan Nota
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Client-side calculation script -->
    <script>
        const materials = @json($bahans);
        const finishings = @json($finishings);
        let itemCount = 0;

        function formatRupiah(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        function createItemHtml(idx) {
            let materialOptions = `<option value="">-- Pilih Bahan --</option>`;
            materials.forEach(m => {
                materialOptions += `<option value="${m.id}" data-type="${m.jenis_cetakan}" data-price="${m.harga_jual}" data-unit="${m.satuan}">
                    ${m.nama_bahan} - Rp ${Number(m.harga_jual).toLocaleString('id-ID')} / ${m.satuan} (${m.jenis_cetakan})
                </option>`;
            });

            let finishingCheckboxes = '';
            finishings.forEach(f => {
                finishingCheckboxes += `
                    <label class="inline-flex items-center text-xs mr-3 mb-1">
                        <input type="checkbox" name="items[${idx}][finishings][]" value="${f.id}" data-price="${f.harga}" class="item-finishing-check rounded border-gray-300 text-blue-600">
                        <span class="ml-1 text-gray-700 dark:text-gray-300">${f.nama_finishing} (+Rp ${Number(f.harga).toLocaleString('id-ID')})</span>
                    </label>
                `;
            });

            return `
                <div class="item-card border rounded-lg p-4 bg-gray-50 dark:bg-gray-750 dark:border-gray-700" data-index="${idx}">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800">Item #${idx + 1}</span>
                        ${idx > 0 ? `<button type="button" class="text-xs text-red-600 hover:underline remove-item-btn">Hapus Item</button>` : ''}
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-semibold mb-1">Pilih Bahan *:</label>
                            <select name="items[${idx}][bahan_id]" class="item-bahan w-full text-xs border-gray-300 rounded-md" required>
                                ${materialOptions}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Nama Item / Pekerjaan *:</label>
                            <input type="text" name="items[${idx}][nama_item]" placeholder="Misal: Spanduk Pecel Lele" class="item-name w-full text-xs border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Jumlah (Qty) *:</label>
                            <input type="number" name="items[${idx}][qty]" value="1" min="1" class="item-qty w-full text-xs border-gray-300 rounded-md" required>
                        </div>
                    </div>

                    <!-- Banner Dimension Inputs (Hidden by default, shown if banner) -->
                    <div class="banner-dimension-block hidden grid-cols-2 gap-3 mb-3 p-3 bg-blue-50 dark:bg-gray-700 rounded border border-blue-200">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-blue-900 dark:text-blue-200">Panjang (Meter) *:</label>
                            <input type="number" step="0.01" min="0.1" name="items[${idx}][panjang]" class="item-panjang w-full text-xs border-gray-300 rounded-md" placeholder="Contoh: 3">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-blue-900 dark:text-blue-200">Lebar (Meter) *:</label>
                            <input type="number" step="0.01" min="0.1" name="items[${idx}][lebar]" class="item-lebar w-full text-xs border-gray-300 rounded-md" placeholder="Contoh: 1">
                        </div>
                    </div>

                    <!-- Finishings options -->
                    <div class="mb-3">
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">Pilihan Finishing Tambahan:</label>
                        <div class="flex flex-wrap">
                            ${finishingCheckboxes}
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-600">
                        <span class="text-xs text-gray-500">Rumus: <span class="formula-info">Pilih bahan...</span></span>
                        <div class="text-right">
                            <span class="text-xs text-gray-500 mr-2">Subtotal Item:</span>
                            <span class="font-bold text-sm text-green-600 item-subtotal-display">Rp 0</span>
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

            // Tambahkan jasa design jika dicentang
            const jasaCheck = document.getElementById('jasaDesignCheck');
            const jasaPrice = document.getElementById('inputJasaDesign');
            if (jasaCheck && jasaCheck.checked && jasaPrice) {
                grandTotal += parseFloat(jasaPrice.value) || 0;
            }

            document.getElementById('totalTagihanDisplay').innerText = formatRupiah(grandTotal);

            const dpInput = document.getElementById('inputDP');
            const dp = parseFloat(dpInput.value) || 0;
            document.getElementById('dpDisplay').innerText = formatRupiah(dp);

            const sisa = Math.max(0, grandTotal - dp);
            document.getElementById('sisaBayarDisplay').innerText = formatRupiah(sisa);

            const statusBadge = document.getElementById('statusBayarBadge');
            if (grandTotal > 0 && sisa <= 0) {
                statusBadge.innerHTML = 'Status: <span class="text-green-600 font-bold">LUNAS</span>';
            } else {
                statusBadge.innerHTML = 'Status: <span class="text-red-500 font-bold">BELUM LUNAS</span>';
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

        // Toggle jasa design price block
        const jasaCheck = document.getElementById('jasaDesignCheck');
        const jasaPriceBlock = document.getElementById('jasaDesignPriceBlock');
        const jasaPrice = document.getElementById('inputJasaDesign');
        if (jasaCheck) {
            jasaCheck.addEventListener('change', () => {
                jasaPriceBlock.classList.toggle('hidden', !jasaCheck.checked);
                calculateGrandTotal();
            });
        }
        if (jasaPrice) {
            jasaPrice.addEventListener('input', calculateGrandTotal);
        }

        // Add first item on load
        addNewItem();
    </script>
</x-app-layout>
