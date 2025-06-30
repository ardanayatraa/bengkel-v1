<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-4">
            <h2 class="text-lg font-semibold border-b pb-2 text-red-800 flex items-center gap-2">
                Tambah Barang Masuk (Multi‐Row)
            </h2>
        </div>

        <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
            {{-- Flash Message Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tampilkan daftar semua error validasi --}}
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('trx-barang-masuk.store') }}" method="POST" id="formBarangMasuk">
                @csrf

                {{-- Container untuk menyimpan semua baris input --}}
                <div id="input-rows">
                    {{-- Baris Pertama (index = 0) --}}
                    <div class="row-barang grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 items-end">
                        {{-- Pilih Barang --}}
                        <div>
                            <label class="block mb-2 text-gray-700 dark:text-gray-300">Barang</label>
                            <select name="details[0][id_barang]"
                                class="barang-select w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                                required>
                                <option value="">Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id_barang }}" data-harga="{{ $barang->harga_beli }}">
                                        {{ $barang->nama_barang }}
                                        – Supplier [{{ $barang->supplier->nama_supplier ?? '-' }}]
                                        – Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Masuk --}}
                        <div>
                            <label class="block mb-2 text-gray-700 dark:text-gray-300">Tanggal Masuk</label>
                            <input type="date" name="details[0][tanggal_masuk]"
                                class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                                required>
                        </div>

                        {{-- Jumlah --}}
                        <div>
                            <label class="block mb-2 text-gray-700 dark:text-gray-300">Jumlah</label>
                            <input type="number" name="details[0][jumlah]" min="1"
                                class="jumlah-input w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                                required>
                        </div>

                        {{-- Total Harga --}}
                        <div>
                            <label class="block mb-2 text-gray-700 dark:text-gray-300">Total Harga</label>
                            {{-- Field tampilan (read‐only) --}}
                            <input type="text"
                                class="total-harga-view w-full rounded-lg p-2 bg-gray-100 border dark:bg-gray-800 dark:text-gray-400 focus:ring-red-500 focus:border-red-500 transition"
                                readonly placeholder="Rp0">
                            {{-- Hidden field untuk nilai sebenarnya dikirim ke server --}}
                            <input type="hidden" name="details[0][total_harga]" class="total-harga">
                        </div>

                        {{-- Tombol Hapus Baris (hanya muncul di baris > 0) --}}
                        <div class="md:col-span-2 text-right mt-2">
                            <button type="button" class="btn-hapus-row hidden text-red-600 hover:underline">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div> {{-- #input-rows End --}}

                {{-- Tombol “+ Tambah Baris” --}}
                <div class="flex items-center justify-between mt-2 mb-6">
                    <button type="button" id="btn-add-row"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center gap-1">
                        <span class="text-lg font-bold">+</span>
                        Tambah Baris
                    </button>
                </div>

                {{-- Tombol Aksi (Batal & Simpan) --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('trx-barang-masuk.index') }}"
                        class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-4 py-2 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== JavaScript untuk Dinamika Baris (Tanpa Livewire) ===== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowIdx = 1; // Indeks untuk baris baru yang akan datang

            // Fungsi format angka menjadi Rupiah, contohnya 15000 → "Rp15.000"
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            // Fungsi menghitung total harga pada satu baris
            function hitungTotal(row) {
                const barangSelect = row.querySelector('.barang-select');
                const jumlahInput = row.querySelector('.jumlah-input');
                const totalHargaInp = row.querySelector('.total-harga');
                const totalView = row.querySelector('.total-harga-view');

                const hargaBeli = parseFloat(
                    barangSelect.options[barangSelect.selectedIndex]?.getAttribute('data-harga')
                ) || 0;
                const jumlah = parseInt(jumlahInput.value) || 0;
                const total = hargaBeli * jumlah;

                totalHargaInp.value = total;
                totalView.value = total ? formatRupiah(total) : '';
            }

            // Pasang event listener (change/input) pada satu baris agar hitungTotal dipanggil
            function initRowEvent(row) {
                const barangSelect = row.querySelector('.barang-select');
                const jumlahInput = row.querySelector('.jumlah-input');
                barangSelect.addEventListener('change', function() {
                    hitungTotal(row);
                });
                jumlahInput.addEventListener('input', function() {
                    hitungTotal(row);
                });
            }

            // Ketika tombol "+ Tambah Baris" diklik
            document.getElementById('btn-add-row').addEventListener('click', function() {
                const firstRow = document.querySelector('.row-barang');
                const clone = firstRow.cloneNode(true);

                // Reset semua input/select di clone
                clone.querySelectorAll('input, select').forEach(function(el) {
                    if (el.tagName === 'SELECT') {
                        el.selectedIndex = 0;
                    } else {
                        el.value = '';
                    }
                });

                // Update atribut name: details[0][...] → details[rowIdx][...]
                clone.querySelectorAll('input, select').forEach(function(el) {
                    if (el.name) {
                        el.name = el.name.replace(/\d+/, rowIdx);
                    }
                });

                // Tampilkan tombol hapus untuk baris baru
                clone.querySelector('.btn-hapus-row').classList.remove('hidden');

                // Pasang event listener agar total harga di baris baru ter‐hitung otomatis
                initRowEvent(clone);

                // Tambahkan clone ke dalam container #input-rows
                document.getElementById('input-rows').appendChild(clone);

                rowIdx++;
            });

            // Event delegasi: jika tombol “Hapus” diklik dalam #input-rows
            document.getElementById('input-rows').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-hapus-row')) {
                    const row = e.target.closest('.row-barang');
                    row.remove();
                }
            });

            // Inisialisasi event listener di baris pertama
            document.querySelectorAll('.row-barang').forEach(function(row, index) {
                initRowEvent(row);
                // Sembunyikan tombol Hapus di baris pertama (karena index=0)
                if (index === 0) {
                    row.querySelector('.btn-hapus-row').classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
se
