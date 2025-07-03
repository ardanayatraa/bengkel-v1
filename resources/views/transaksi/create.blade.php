{{-- resources/views/transaksi/create.blade.php --}}
<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <h2 class="text-lg font-semibold border-b pb-2 mb-6">Tambah Transaksi</h2>
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Konsumen --}}
                <div>
                    <label class="block mb-1 font-medium">Konsumen</label>
                    <select id="id_konsumen" name="id_konsumen" required class="w-full p-2 border rounded"
                        data-points-json='@json($konsumens->pluck('jumlah_point', 'id_konsumen'))'>
                        @foreach ($konsumens as $k)
                            <option value="{{ $k->id_konsumen }}" data-point="{{ $k->jumlah_point }}"
                                {{ old('id_konsumen') == $k->id_konsumen ? 'selected' : '' }}>
                                {{ $k->nama_konsumen }} — {{ $k->jumlah_point }} pt
                            </option>
                        @endforeach
                    </select>
                    @error('id_konsumen')
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
                    <label class="block mb-1 font-medium">Status Service</label>
                    <select name="status_service" required class="w-full p-2 border rounded">
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
                    <label class="block mb-1 font-medium">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_transaksi"
                        value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required
                        class="w-full p-2 border rounded" />
                    @error('tanggal_transaksi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block mb-1 font-medium">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required class="w-full p-2 border rounded">
                        <option value="">Pilih Metode</option>
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
                    <div class="grid grid-cols-2 gap-2 p-2 border rounded max-h-40 overflow-auto">
                        @foreach ($jasas as $j)
                            <label class="flex items-center space-x-2">
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

                {{-- Estimasi --}}
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
                    <div class="grid grid-cols-2 gap-2 p-2 border rounded max-h-40 overflow-auto">
                        @foreach ($barangs as $b)
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" name="id_barang[]" value="{{ $b->id_barang }}"
                                    data-harga="{{ $b->harga_jual }}" class="barang-cb"
                                    {{ is_array(old('id_barang')) && in_array($b->id_barang, old('id_barang')) ? 'checked' : '' }} />
                                <span class="flex-1">{{ $b->nama_barang }} — Rp
                                    {{ number_format($b->harga_jual, 0, ',', '.') }}</span>
                                <input type="number" name="qty_barang[{{ $b->id_barang }}]"
                                    value="{{ old('qty_barang.' . $b->id_barang, 1) }}" min="1"
                                    class="qty-input w-16 p-1 border rounded text-sm"
                                    {{ is_array(old('id_barang')) && in_array($b->id_barang, old('id_barang')) ? '' : 'disabled' }} />
                            </div>
                        @endforeach
                    </div>
                    @error('id_barang')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Total Harga --}}
                <div>
                    <label class="block mb-1 font-medium">Total Harga</label>
                    <input type="text" id="total_display" readonly class="w-full p-2 border rounded mb-1" />
                    <input type="hidden" name="total_harga" id="total_harga" />
                </div>

                {{-- Uang Diterima --}}
                <div>
                    <label class="block mb-1 font-medium">Uang Diterima</label>
                    <input type="number" name="uang_diterima" id="uang_diterima" min="0"
                        value="{{ old('uang_diterima', 0) }}" class="w-full p-2 border rounded" />
                    @error('uang_diterima')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kembalian --}}
                <div>
                    <label class="block mb-1 font-medium">Kembalian</label>
                    <input type="text" id="kembalian_display" readonly class="w-full p-2 border rounded" />
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <script>
        function formatRupiah(n) {
            return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        const pointsMap = JSON.parse(document.querySelector('#id_konsumen').dataset.pointsJson);
        const redeemInput = document.getElementById('redeem_points');
        const maxDisplay = document.getElementById('max_points_display');
        const sisaDisplay = document.getElementById('sisa_points_display');

        function normalizeRedeem() {
            let v = +redeemInput.value;
            v = Math.floor(v / 10) * 10;
            const max = +redeemInput.max || 0;
            redeemInput.value = Math.min(Math.max(0, v), max);
        }

        function updateSisa() {
            const id = +document.getElementById('id_konsumen').value;
            const saldo = pointsMap[id] || 0;
            sisaDisplay.textContent = saldo - (+redeemInput.value || 0);
        }

        // enable/disable qty-input sesuai checkbox
        document.querySelectorAll('.barang-cb').forEach(cb => {
            cb.addEventListener('change', e => {
                const qty = e.target.closest('div').querySelector('.qty-input');
                qty.disabled = !e.target.checked;
                calculate();
            });
        });

        // redeem buttons
        document.getElementById('decrement_redeem').addEventListener('click', () => {
            redeemInput.stepDown();
            normalizeRedeem();
            updateSisa();
            calculate();
        });
        document.getElementById('increment_redeem').addEventListener('click', () => {
            redeemInput.stepUp();
            normalizeRedeem();
            updateSisa();
            calculate();
        });

        // konsumen change
        document.getElementById('id_konsumen').addEventListener('change', () => {
            const id = +document.getElementById('id_konsumen').value;
            const saldo = pointsMap[id] || 0;
            maxDisplay.textContent = saldo;
            redeemInput.max = saldo;
            normalizeRedeem();
            updateSisa();
            calculate();
        });

        // re-calculate on input changes
        document.querySelectorAll(
            '#id_konsumen, .jasa-cb, .qty-input, #uang_diterima'
        ).forEach(el => el.addEventListener('change', calculate));
        redeemInput.addEventListener('input', () => {
            normalizeRedeem();
            updateSisa();
            calculate();
        });

        function calculate() {
            let sum = 0;
            document.querySelectorAll('.barang-cb:checked').forEach(cb => {
                const harga = +cb.dataset.harga;
                const qty = +cb.closest('div').querySelector('.qty-input').value || 1;
                sum += harga * qty;
            });
            document.querySelectorAll('.jasa-cb:checked').forEach(cb => sum += +cb.dataset.harga);
            const redeem = +redeemInput.value || 0;
            sum -= (redeem / 10) * 10000;
            updateSisa();
            document.getElementById('total_harga').value = sum;
            document.getElementById('total_display').value = formatRupiah(sum);
            const bayar = +document.getElementById('uang_diterima').value || 0;
            const kembali = bayar - sum;
            document.getElementById('kembalian_display').value = kembali >= 0 ? formatRupiah(kembali) : '';
        }

        window.addEventListener('load', () => {
            document.querySelectorAll('.barang-cb').forEach(cb => {
                const qty = cb.closest('div').querySelector('.qty-input');
                qty.disabled = !cb.checked;
            });
            document.getElementById('id_konsumen').dispatchEvent(new Event('change'));
        });
    </script>
</x-app-layout>
