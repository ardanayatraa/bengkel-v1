{{-- resources/views/transaksi/edit.blade.php --}}
<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <h2 class="text-lg font-semibold border-b pb-2 mb-6">
            Edit Transaksi #{{ $transaksi->id_transaksi }}
        </h2>
        <form action="{{ route('transaksi.update', $transaksi->id_transaksi) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Konsumen -->
                <div>
                    <label class="block mb-1 font-medium">Konsumen</label>
                    <select id="id_konsumen" name="id_konsumen" required class="w-full p-2 border rounded">
                        @foreach ($konsumens as $k)
                            <option value="{{ $k->id_konsumen }}" data-keterangan="{{ strtolower($k->keterangan) }}"
                                data-point="{{ $k->jumlah_point }}"
                                {{ old('id_konsumen', $transaksi->id_konsumen) == $k->id_konsumen ? 'selected' : '' }}>
                                {{ $k->nama_konsumen }} — {{ $k->jumlah_point }} Point
                            </option>
                        @endforeach
                    </select>
                    @error('id_konsumen')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teknisi -->
                <div>
                    <label class="block mb-1 font-medium">Teknisi</label>
                    <select name="id_teknisi" class="w-full p-2 border rounded">
                        <option value="">— Pilih Teknisi —</option>
                        @foreach ($teknisis as $t)
                            <option value="{{ $t->id_teknisi }}"
                                {{ old('id_teknisi', $transaksi->id_teknisi) == $t->id_teknisi ? 'selected' : '' }}>
                                {{ $t->nama_teknisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_teknisi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Service -->
                <div>
                    <label class="block mb-1 font-medium">Status Service</label>
                    <select name="status_service" required class="w-full p-2 border rounded">
                        @foreach (['proses', 'selesai', 'diambil'] as $s)
                            <option value="{{ $s }}"
                                {{ old('status_service', $transaksi->status_service) == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_service')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Transaksi -->
                <div>
                    <label class="block mb-1 font-medium">Tanggal Transaksi</label>
                    <input type="date" name="tanggal_transaksi"
                        value="{{ old('tanggal_transaksi', $transaksi->tanggal_transaksi->format('Y-m-d')) }}" required
                        class="w-full p-2 border rounded">
                    @error('tanggal_transaksi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block mb-1 font-medium">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required class="w-full p-2 border rounded">
                        @foreach (['Cash', 'QRIS'] as $m)
                            <option value="{{ $m }}"
                                {{ old('metode_pembayaran', $transaksi->metode_pembayaran) == $m ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endforeach
                    </select>
                    @error('metode_pembayaran')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jasa -->
                <div class="md:col-span-2">
                    <label class="block mb-1 font-medium">Jasa (bisa pilih banyak)</label>
                    <div class="grid grid-cols-2 gap-2 p-2 border rounded max-h-40 overflow-auto">
                        @foreach ($jasas as $j)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="id_jasa[]" value="{{ $j->id_jasa }}"
                                    data-harga="{{ $j->harga_jasa }}" class="jasa-cb"
                                    {{ in_array($j->id_jasa, old('id_jasa', $transaksi->id_jasa)) ? 'checked' : '' }}>
                                <span>{{ $j->nama_jasa }} — Rp {{ number_format($j->harga_jasa, 0, ',', '.') }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('id_jasa')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estimasi -->
                <div id="estimasi_wrapper" class="hidden md:col-span-2">
                    <label class="block mb-1 font-medium">Estimasi Pengerjaan</label>
                    <input type="text" name="estimasi_pengerjaan"
                        value="{{ old('estimasi_pengerjaan', $transaksi->estimasi_pengerjaan) }}"
                        class="w-full p-2 border rounded" placeholder="Misal: 2 hari kerja">
                    @error('estimasi_pengerjaan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Barang -->
                <div class="md:col-span-2">
                    <label class="block mb-1 font-medium">Barang (qty per item)</label>
                    <div class="grid grid-cols-2 gap-2 p-2 border rounded max-h-40 overflow-auto">
                        @foreach ($barangs as $b)
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" name="id_barang[]" value="{{ $b->id_barang }}"
                                    data-harga="{{ $b->harga_jual }}" class="barang-cb"
                                    {{ array_key_exists($b->id_barang, $transaksi->id_barang) ? 'checked' : '' }}>
                                <span class="flex-1">{{ $b->nama_barang }} — Rp
                                    {{ number_format($b->harga_jual, 0, ',', '.') }}</span>
                                <input type="number" name="qty_barang[{{ $b->id_barang }}]"
                                    value="{{ $transaksi->id_barang[$b->id_barang] ?? 1 }}" min="1" disabled
                                    class="qty-input w-16 p-1 border rounded text-sm" />
                            </div>
                        @endforeach
                    </div>
                    @error('id_barang')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Harga -->
                <div>
                    <label class="block mb-1 font-medium">Total Harga</label>
                    <input type="text" id="total_display" readonly
                        value="Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}"
                        class="w-full p-2 border rounded mb-1" />
                    <input type="hidden" name="total_harga" id="total_harga" value="{{ $transaksi->total_harga }}" />
                </div>

                <!-- Uang Diterima -->
                <div>
                    <label class="block mb-1 font-medium">Uang Diterima</label>
                    <input type="number" name="uang_diterima" id="uang_diterima" min="0"
                        value="{{ old('uang_diterima', $transaksi->uang_diterima) }}"
                        class="w-full p-2 border rounded" />
                    @error('uang_diterima')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kembalian -->
                <div>
                    <label class="block mb-1 font-medium">Kembalian</label>
                    <input type="text" id="kembalian_display" readonly class="w-full p-2 border rounded" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('transaksi.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 mr-2">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Update
                </button>
            </div>
        </form>
    </div>

    <script>
        function formatRupiah(n) {
            // ubah angka jadi format 'Rp xxx.xxx'
            return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function calculate() {
            let sum = 0;
            // hitung subtotal barang
            document.querySelectorAll('.barang-cb:checked').forEach(cb => {
                const harga = +cb.dataset.harga;
                const qty = +document.querySelector(`[name="qty_barang[${cb.value}]"]`).value || 1;
                sum += harga * qty;
            });
            // hitung subtotal jasa
            document.querySelectorAll('.jasa-cb:checked').forEach(cb => {
                sum += +cb.dataset.harga;
            });

            // toggle estimasi
            document.getElementById('estimasi_wrapper')
                .classList.toggle('hidden',
                    document.querySelectorAll('.jasa-cb:checked').length === 0
                );

            // diskon member
            const kons = document.querySelector('#id_konsumen option:checked');
            let total = sum;
            if (kons.dataset.keterangan === 'member' && +kons.dataset.point >= 10) {
                total -= 10000;
            }

            // tampilkan total
            document.getElementById('total_harga').value = total;
            document.getElementById('total_display').value = formatRupiah(total);

            // hitung kembalian = bayar – total
            const bayar = +document.getElementById('uang_diterima').value || 0;
            const kembali = bayar - total;

            // jika kembalian ≥ 0 tampilkan, jika negatif kosongkan
            document.getElementById('kembalian_display').value =
                kembali >= 0 ? formatRupiah(kembali) : '';
        }

        // pasang listener ke semua input terkait
        document.querySelectorAll(
            '#id_konsumen, .barang-cb, .jasa-cb, .qty-input, #uang_diterima'
        ).forEach(el => el.addEventListener('change', calculate));

        window.addEventListener('load', calculate);
    </script>

</x-app-layout>
