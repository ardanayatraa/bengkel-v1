{{-- resources/views/transaksi/create.blade.php --}}
<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <h2 class="text-lg font-semibold border-b pb-2 mb-6">Tambah Transaksi</h2>

        {{-- Display Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Konsumen --}}
                <div>
                    <label class="block mb-1 font-medium">Konsumen <span class="text-red-500">*</span></label>
                    <div class="flex space-x-2">
                        <select id="id_konsumen" name="id_konsumen" required class="flex-1 p-2 border rounded"
                            data-points-json='@json($konsumens->pluck('jumlah_point', 'id_konsumen'))'>
                            <option value="">-- Pilih Konsumen --</option>
                            @foreach ($konsumens as $k)
                                <option value="{{ $k->id_konsumen }}" data-point="{{ $k->jumlah_point }}"
                                    {{ old('id_konsumen') == $k->id_konsumen ? 'selected' : '' }}>
                                    {{ $k->nama_konsumen }} — {{ $k->jumlah_point }} pt
                                    @if ($k->kode_referral)
                                        — Kode: {{ $k->kode_referral }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('konsumen.create', ['return_to' => url()->current()]) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-center">
                            + Konsumen
                        </a>
                    </div>
                    @error('id_konsumen')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kode Referral --}}
                <div>
                    <label class="block mb-1 font-medium">Kode Referral (Opsional)</label>
                    <div class="flex space-x-2">
                        <input type="text" name="kode_referral" id="kode_referral"
                            value="{{ old('kode_referral') }}" class="flex-1 p-2 border rounded uppercase"
                            placeholder="Masukkan kode referral" maxlength="10" />
                        <button type="button" id="check_referral"
                            class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Cek
                        </button>
                    </div>
                    <div id="referral_message" class="mt-1 text-sm"></div>
                    @error('kode_referral')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tukar Poin --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 font-medium">Tukar Poin (kelipatan 10 pt → Rp 10.000)</label>
                    <div class="flex items-center space-x-2">
                        <button type="button" id="decrement_redeem" class="px-3 py-1 bg-gray-200 rounded">−</button>
                        <input type="number" name="redeem_points" id="redeem_points"
                            value="{{ old('redeem_points', 0) }}" min="0" step="10"
                            class="w-24 text-center p-2 border rounded" />
                        <button type="button" id="increment_redeem" class="px-3 py-1 bg-gray-200 rounded">+</button>
                        <span class="text-sm ml-4">Maks: <strong id="max_points_display">0</strong> pt</span>
                    </div>
                    @error('redeem_points')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm">Sisa Poin: <strong id="sisa_points_display">0</strong> pt</p>
                </div>

                {{-- Teknisi --}}
                <div>
                    <label class="block mb-1 font-medium">Teknisi</label>
                    <select name="id_teknisi" class="w-full p-2 border rounded">
                        <option value="">— Pilih Teknisi —</option>
                        @foreach ($teknisis as $t)
                            <option value="{{ $t->id_teknisi }}"
                                {{ old('id_teknisi') == $t->id_teknisi ? 'selected' : '' }}>
                                {{ $t->nama_teknisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_teknisi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Service --}}
                <div>
                    <label class="block mb-1 font-medium">Status Service <span class="text-red-500">*</span></label>
                    <select name="status_service" required class="w-full p-2 border rounded">
                        <option value="">-- Pilih Status --</option>
                        @foreach (['proses', 'selesai', 'diambil'] as $s)
                            <option value="{{ $s }}" {{ old('status_service') == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_service')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Transaksi --}}
                <div>
                    <label class="block mb-1 font-medium">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_transaksi"
                        value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required
                        class="w-full p-2 border rounded" />
                    @error('tanggal_transaksi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block mb-1 font-medium">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select name="metode_pembayaran" required class="w-full p-2 border rounded">
                        <option value="">-- Pilih Metode --</option>
                        @foreach (['Cash', 'QRIS'] as $m)
                            <option value="{{ $m }}"
                                {{ old('metode_pembayaran') == $m ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endforeach
                    </select>
                    @error('metode_pembayaran')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jasa --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 font-medium">Jasa (bisa pilih banyak)</label>
                    <input type="text" id="search_jasa" placeholder="Cari jasa..."
                        class="mb-2 w-full p-2 border rounded" />
                    <div class="grid grid-cols-2 gap-2 p-2 border rounded max-h-40 overflow-auto" id="jasa_list">
                        @foreach ($jasas as $j)
                            <label class="flex items-center space-x-2 jasa-item">
                                <input type="checkbox" name="id_jasa[]" value="{{ $j->id_jasa }}"
                                    data-harga="{{ $j->harga_jasa }}" class="jasa-cb"
                                    {{ is_array(old('id_jasa')) && in_array($j->id_jasa, old('id_jasa')) ? 'checked' : '' }} />
                                <span>{{ $j->nama_jasa }} — Rp {{ number_format($j->harga_jasa, 0, ',', '.') }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('id_jasa')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estimasi Pengerjaan (tampil saat ada jasa tercentang) --}}
                <div id="estimasi_wrapper" class="hidden md:col-span-2">
                    <label class="block mb-1 font-medium">Estimasi Pengerjaan</label>
                    <input type="text" name="estimasi_pengerjaan" value="{{ old('estimasi_pengerjaan') }}"
                        class="w-full p-2 border rounded" placeholder="Misal: 2 hari kerja" />
                    @error('estimasi_pengerjaan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Barang --}}
                <div class="md:col-span-2">
                    <label class="block mb-1 font-medium">Barang (qty per item)</label>
                    <input type="text" id="search_barang" placeholder="Cari barang..."
                        class="mb-2 w-full p-2 border rounded" />
                    <div class="grid grid-cols-2 gap-2 p-2 border rounded max-h-40 overflow-auto" id="barang_list">
                        @foreach ($barangs as $b)
                            <div class="flex items-center space-x-2 barang-item">
                                <input type="checkbox" name="id_barang[]" value="{{ $b->id_barang }}"
                                    data-harga="{{ $b->harga_jual }}" data-stok="{{ $b->stok }}"
                                    class="barang-cb"
                                    {{ is_array(old('id_barang')) && in_array($b->id_barang, old('id_barang')) ? 'checked' : '' }} />
                                <span class="flex-1">{{ $b->nama_barang }} — Rp
                                    {{ number_format($b->harga_jual, 0, ',', '.') }}
                                    <br><small class="text-gray-500">Stok: {{ $b->stok }}</small>
                                </span>
                                <input type="number" name="qty_barang[{{ $b->id_barang }}]"
                                    value="{{ old('qty_barang.' . $b->id_barang, 1) }}" min="1"
                                    max="{{ $b->stok }}" class="qty-input w-16 p-1 border rounded text-sm"
                                    {{ is_array(old('id_barang')) && in_array($b->id_barang, old('id_barang')) ? '' : 'disabled' }} />
                            </div>
                        @endforeach
                    </div>
                    @error('id_barang')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('qty_barang.*')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Diskon & Total --}}
                <div class="md:col-span-2 bg-gray-50 p-4 rounded">
                    <h3 class="font-medium mb-3">Ringkasan Pembayaran</h3>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span>Subtotal:</span>
                            <span id="subtotal_display" class="float-right font-medium">Rp 0</span>
                        </div>
                        <div>
                            <span>Diskon Poin:</span>
                            <span id="diskon_poin_display" class="float-right font-medium text-green-600">Rp 0</span>
                        </div>
                        <div>
                            <span>Diskon Referral:</span>
                            <span id="diskon_referral_display" class="float-right font-medium text-green-600">Rp
                                0</span>
                        </div>
                        <div class="border-t pt-2">
                            <span class="font-bold">Total:</span>
                            <span id="total_display" class="float-right font-bold">Rp 0</span>
                        </div>
                    </div>
                </div>

                {{-- Uang Diterima --}}
                <div>
                    <label class="block mb-1 font-medium">Uang Diterima <span class="text-red-500">*</span></label>
                    <input type="number" name="uang_diterima" id="uang_diterima" min="0" step="0.01"
                        value="{{ old('uang_diterima', 0) }}" required class="w-full p-2 border rounded" />
                    @error('uang_diterima')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kembalian --}}
                <div>
                    <label class="block mb-1 font-medium">Kembalian</label>
                    <input type="text" id="kembalian_display" readonly
                        class="w-full p-2 border rounded bg-gray-100" value="Rp 0" />
                </div>

            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <a href="{{ route('transaksi.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <script>
        // CSRF Token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            '{{ csrf_token() }}';

        function formatRupiah(n) {
            if (isNaN(n) || n < 0) return 'Rp 0';
            return 'Rp ' + Math.floor(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        const pointsMap = JSON.parse(document.querySelector('#id_konsumen').dataset.pointsJson || '{}');
        const redeemInput = document.getElementById('redeem_points');
        const maxDisplay = document.getElementById('max_points_display');
        const sisaDisplay = document.getElementById('sisa_points_display');
        const jasaCheckboxes = document.querySelectorAll('.jasa-cb');
        const barangCheckboxes = document.querySelectorAll('.barang-cb');
        const estimasiWrapper = document.getElementById('estimasi_wrapper');

        let diskonReferral = 0; // variable untuk menyimpan diskon referral

        function normalizeRedeem() {
            let v = parseInt(redeemInput.value) || 0;
            v = Math.floor(v / 10) * 10; // Kelipatan 10
            const max = parseInt(redeemInput.max) || 0;
            v = Math.min(Math.max(0, v), max);
            redeemInput.value = v;
        }

        function updateSisa() {
            const id = parseInt(document.getElementById('id_konsumen').value) || 0;
            const saldo = pointsMap[id] || 0;
            const used = parseInt(redeemInput.value) || 0;
            sisaDisplay.textContent = Math.max(0, saldo - used);
        }

        function toggleEstimasi() {
            const anyJasa = Array.from(jasaCheckboxes).some(cb => cb.checked);
            estimasiWrapper.classList.toggle('hidden', !anyJasa);
        }

        function validateStok() {
            let valid = true;
            barangCheckboxes.forEach(cb => {
                if (cb.checked) {
                    const qtyInput = cb.closest('div').querySelector('.qty-input');
                    const qty = parseInt(qtyInput.value) || 1;
                    const stok = parseInt(cb.dataset.stok) || 0;

                    if (qty > stok) {
                        qtyInput.classList.add('border-red-500');
                        valid = false;
                    } else {
                        qtyInput.classList.remove('border-red-500');
                    }
                }
            });
            return valid;
        }

        function calculate() {
            let subtotal = 0;

            // Hitung barang
            barangCheckboxes.forEach(cb => {
                if (cb.checked) {
                    const harga = parseFloat(cb.dataset.harga) || 0;
                    const qtyInput = cb.closest('div').querySelector('.qty-input');
                    const qty = parseInt(qtyInput.value) || 1;
                    subtotal += harga * qty;
                }
            });

            // Hitung jasa
            jasaCheckboxes.forEach(cb => {
                if (cb.checked) {
                    const harga = parseFloat(cb.dataset.harga) || 0;
                    subtotal += harga;
                }
            });

            // Diskon poin
            const redeemPts = parseInt(redeemInput.value) || 0;
            const diskonPoin = (redeemPts / 10) * 10000;

            // Total setelah semua diskon
            const total = Math.max(0, subtotal - diskonPoin - diskonReferral);

            // Update display
            document.getElementById('subtotal_display').textContent = formatRupiah(subtotal);
            document.getElementById('diskon_poin_display').textContent = '-' + formatRupiah(diskonPoin);
            document.getElementById('diskon_referral_display').textContent = '-' + formatRupiah(diskonReferral);
            document.getElementById('total_display').textContent = formatRupiah(total);

            updateSisa();
            toggleEstimasi();
            validateStok();

            const bayar = parseFloat(document.getElementById('uang_diterima').value) || 0;
            const kembali = bayar - total;
            document.getElementById('kembalian_display').value =
                kembali >= 0 ? formatRupiah(kembali) : 'Kurang Rp ' + formatRupiah(Math.abs(kembali));
        }

        // Check referral code
        document.getElementById('check_referral').addEventListener('click', function() {
            const kodeReferral = document.getElementById('kode_referral').value.trim().toUpperCase();
            const idKonsumen = document.getElementById('id_konsumen').value;
            const messageDiv = document.getElementById('referral_message');

            if (!kodeReferral) {
                messageDiv.innerHTML = '<span class="text-red-600">Masukkan kode referral terlebih dahulu</span>';
                return;
            }

            if (!idKonsumen) {
                messageDiv.innerHTML = '<span class="text-red-600">Pilih konsumen terlebih dahulu</span>';
                return;
            }

            // Set loading state
            this.disabled = true;
            this.textContent = 'Checking...';
            messageDiv.innerHTML = '<span class="text-gray-600">Memvalidasi kode...</span>';

            // AJAX call to validate referral
            fetch('{{ route('transaksi.validate-referral') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        kode_referral: kodeReferral,
                        id_konsumen: idKonsumen
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        diskonReferral = data.diskon || 0;
                        messageDiv.innerHTML = '<span class="text-green-600">' + (data.message ||
                            'Kode referral valid') + '</span>';
                        document.getElementById('kode_referral').value = kodeReferral;
                    } else {
                        diskonReferral = 0;
                        messageDiv.innerHTML = '<span class="text-red-600">' + (data.message ||
                            'Kode referral tidak valid') + '</span>';
                    }
                    calculate();
                })
                .catch(error => {
                    console.error('Error validating referral:', error);
                    diskonReferral = 0;
                    messageDiv.innerHTML = '<span class="text-red-600">Terjadi kesalahan saat validasi</span>';
                    calculate();
                })
                .finally(() => {
                    this.disabled = false;
                    this.textContent = 'Cek';
                });
        });

        // Uppercase input referral
        document.getElementById('kode_referral').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            // Reset diskon jika kode berubah
            if (diskonReferral > 0) {
                diskonReferral = 0;
                document.getElementById('referral_message').innerHTML = '';
                calculate();
            }
        });

        // Event listeners
        document.getElementById('decrement_redeem').addEventListener('click', () => {
            const current = parseInt(redeemInput.value) || 0;
            redeemInput.value = Math.max(0, current - 10);
            normalizeRedeem();
            calculate();
        });

        document.getElementById('increment_redeem').addEventListener('click', () => {
            const current = parseInt(redeemInput.value) || 0;
            const max = parseInt(redeemInput.max) || 0;
            redeemInput.value = Math.min(max, current + 10);
            normalizeRedeem();
            calculate();
        });

        document.getElementById('id_konsumen').addEventListener('change', () => {
            const id = parseInt(document.getElementById('id_konsumen').value) || 0;
            const saldo = pointsMap[id] || 0;
            maxDisplay.textContent = saldo;
            redeemInput.max = saldo;
            normalizeRedeem();

            // Reset referral jika ganti konsumen
            if (diskonReferral > 0) {
                diskonReferral = 0;
                document.getElementById('kode_referral').value = '';
                document.getElementById('referral_message').innerHTML = '';
            }
            calculate();
        });

        jasaCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                toggleEstimasi();
                calculate();
            });
        });

        barangCheckboxes.forEach(cb => {
            cb.addEventListener('change', e => {
                const qtyInput = e.target.closest('div').querySelector('.qty-input');
                qtyInput.disabled = !e.target.checked;
                if (!e.target.checked) {
                    qtyInput.value = 1;
                }
                calculate();
            });
        });

        // Qty input listeners
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('input', calculate);
            input.addEventListener('change', function() {
                const min = parseInt(this.getAttribute('min')) || 1;
                const max = parseInt(this.getAttribute('max')) || 999;
                let value = parseInt(this.value) || min;
                value = Math.min(Math.max(value, min), max);
                this.value = value;
                calculate();
            });
        });

        redeemInput.addEventListener('input', () => {
            normalizeRedeem();
            calculate();
        });

        document.getElementById('uang_diterima').addEventListener('input', calculate);

        // Search functionality
        document.getElementById('search_jasa').addEventListener('input', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('#jasa_list .jasa-item').forEach(function(item) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(val) ? '' : 'none';
            });
        });

        document.getElementById('search_barang').addEventListener('input', function() {
            const val = this.value.toLowerCase();
            document.querySelectorAll('#barang_list .barang-item').forEach(function(item) {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(val) ? '' : 'none';
            });
        });

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validateStok()) {
                e.preventDefault();
                alert('Periksa kembali quantity barang. Ada yang melebihi stok tersedia.');
                return false;
            }

            // Check if at least one item (barang atau jasa) is selected
            const hasBarang = Array.from(barangCheckboxes).some(cb => cb.checked);
            const hasJasa = Array.from(jasaCheckboxes).some(cb => cb.checked);

            if (!hasBarang && !hasJasa) {
                e.preventDefault();
                alert('Pilih minimal satu barang atau jasa untuk transaksi.');
                return false;
            }
        });

        // Initialize on page load
        window.addEventListener('load', () => {
            // Disable all qty-input initially
            barangCheckboxes.forEach(cb => {
                const qtyInput = cb.closest('div').querySelector('.qty-input');
                qtyInput.disabled = !cb.checked;
            });

            calculate();

            // Auto select konsumen baru jika ada
            @if (session('new_konsumen_id'))
                const newKonsumenId = {{ session('new_konsumen_id') }};
                const konsumenSelect = document.getElementById('id_konsumen');
                if (konsumenSelect) {
                    konsumenSelect.value = newKonsumenId;
                    konsumenSelect.dispatchEvent(new Event('change'));
                }
            @endif
        });
    </script>
</x-app-layout>
