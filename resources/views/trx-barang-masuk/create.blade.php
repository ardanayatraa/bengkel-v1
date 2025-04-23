<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                Tambah Barang Masuk
            </h2>
        </div>

        <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
            <form action="{{ route('trx-barang-masuk.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Barang --}}
                    <div>
                        <label for="id_barang" class="block mb-2 text-gray-700 dark:text-gray-300">Barang</label>
                        <select name="id_barang" id="id_barang"
                            class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                            <option value="">Pilih Barang</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id_barang }}" data-harga="{{ $barang->harga_beli }}">
                                    {{ $barang->nama_barang }} - Supplier [{{ $barang->supplier->nama_supplier }}] -
                                    Rp{{ number_format($barang->harga_beli, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal Masuk --}}
                    <div>
                        <label for="tanggal_masuk" class="block mb-2 text-gray-700 dark:text-gray-300">Tanggal
                            Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                            class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label for="jumlah" class="block mb-2 text-gray-700 dark:text-gray-300">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" min="1"
                            class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>

                    {{-- Total Harga - Tampil Format + Hidden Value --}}
                    <div>
                        <label for="total_harga_view" class="block mb-2 text-gray-700 dark:text-gray-300">Total
                            Harga</label>

                        {{-- Tampilkan format rupiah untuk user --}}
                        <input type="text" id="total_harga_view" readonly
                            class="w-full rounded-lg p-2 bg-gray-100 border dark:bg-gray-800 dark:text-gray-400 focus:ring-red-500 focus:border-red-500 transition"
                            placeholder="Rp0">

                        {{-- Kirim angka ke server --}}
                        <input type="hidden" name="total_harga" id="total_harga">
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="mt-6 flex justify-end gap-2">
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

    {{-- Script: Hitung Total Harga + Format Rupiah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const barangSelect = document.getElementById('id_barang');
            const jumlahInput = document.getElementById('jumlah');
            const totalHargaInput = document.getElementById('total_harga');
            const totalHargaView = document.getElementById('total_harga_view');

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            function hitungTotal() {
                const selectedOption = barangSelect.options[barangSelect.selectedIndex];
                const hargaBeli = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
                const jumlah = parseInt(jumlahInput.value) || 0;
                const total = hargaBeli * jumlah;

                totalHargaInput.value = total;
                totalHargaView.value = total ? formatRupiah(total) : '';
            }

            barangSelect.addEventListener('change', hitungTotal);
            jumlahInput.addEventListener('input', hitungTotal);
        });
    </script>
</x-app-layout>
