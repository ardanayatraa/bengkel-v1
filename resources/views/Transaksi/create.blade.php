<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                Tambah Transaksi
            </h2>
        </div>
        <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Konsumen -->
                    <div>
                        <label for="id_konsumen"
                            class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Konsumen</label>
                        <select id="id_konsumen" name="id_konsumen" required
                            class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                            @foreach ($konsumens as $konsumen)
                                <option value="{{ $konsumen->id_konsumen }}"
                                    data-keterangan="{{ strtolower($konsumen->keterangan) }}"
                                    data-point="{{ $konsumen->jumlah_point }}">
                                    {{ $konsumen->nama_konsumen }} - {{ $konsumen->jumlah_point }} Point
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Barang -->
                    <div>
                        <label for="id_barang"
                            class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Barang</label>
                        <select id="id_barang" name="id_barang"
                            class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                            <option value="">Tidak ada</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id_barang }}" data-harga="{{ $barang->harga_jual }}">
                                    {{ $barang->nama_barang }} -
                                    {{ 'Rp ' . number_format($barang->harga_jual, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jasa -->
                    <div>
                        <label for="id_jasa"
                            class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Jasa</label>
                        <select id="id_jasa" name="id_jasa"
                            class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                            <option value="">Tidak ada</option>
                            @foreach ($jasas as $jasa)
                                <option value="{{ $jasa->id_jasa }}" data-harga="{{ $jasa->harga_jasa }}">
                                    {{ $jasa->nama_jasa }} -
                                    {{ 'Rp ' . number_format($jasa->harga_jasa, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label for="tanggal_transaksi"
                            class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Transaksi</label>
                        <input type="date" id="tanggal_transaksi" name="tanggal_transaksi"
                            class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                            required>
                    </div>

                    <!-- Total Harga (Tampilan Format Rupiah) -->
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Total Harga</label>
                        <input type="text" id="total_harga_display"
                            class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                            readonly>
                    </div>

                    <!-- Total Harga (Value untuk Submit) -->
                    <input type="hidden" id="total_harga" name="total_harga" required>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label for="metode_pembayaran"
                            class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Metode Pembayaran</label>
                        <select id="metode_pembayaran" name="metode_pembayaran" required
                            class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                            <option value="">Pilih Metode</option>
                            <option value="Cash">Cash</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>

                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('transaksi.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">Batal</a>
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script untuk menghitung total harga -->
    <script>
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function calculateTotal() {
            const barangSelect = document.getElementById('id_barang');
            const jasaSelect = document.getElementById('id_jasa');
            const konsumenSelect = document.getElementById('id_konsumen');
            const totalHargaHidden = document.getElementById('total_harga');
            const totalHargaDisplay = document.getElementById('total_harga_display');

            const barangHarga = parseInt(barangSelect.selectedOptions[0]?.dataset.harga || 0);
            const jasaHarga = parseInt(jasaSelect.selectedOptions[0]?.dataset.harga || 0);
            const keterangan = konsumenSelect.selectedOptions[0]?.dataset.keterangan || 'bukan member';
            const jumlahPoint = parseInt(konsumenSelect.selectedOptions[0]?.dataset.point || 0);

            let total = barangHarga + jasaHarga;

            // Estimasi diskon Rp10.000 jika member dan point >= 10
            if (keterangan === 'member' && jumlahPoint >= 10) {
                total -= 10000;
            }

            totalHargaHidden.value = total;
            totalHargaDisplay.value = formatRupiah(total);
        }



        document.getElementById('id_barang').addEventListener('change', calculateTotal);
        document.getElementById('id_jasa').addEventListener('change', calculateTotal);
        document.getElementById('id_konsumen').addEventListener('change', calculateTotal);

        window.addEventListener('load', calculateTotal);
    </script>
</x-app-layout>
