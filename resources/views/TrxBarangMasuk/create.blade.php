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
                    <div>
                        <label for="id_barang" class="block mb-2 text-gray-700 dark:text-gray-300">Barang</label>
                        <select name="id_barang" id="id_barang"
                            class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                            <option value="">Pilih Barang</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_masuk" class="block mb-2 text-gray-700 dark:text-gray-300">Tanggal
                            Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                            class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>
                    <div>
                        <label for="nama_supplier" class="block mb-2 text-gray-700 dark:text-gray-300">Nama
                            Supplier</label>
                        <input type="text" name="nama_supplier" id="nama_supplier"
                            class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>
                    <div>
                        <label for="jumlah" class="block mb-2 text-gray-700 dark:text-gray-300">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah"
                            class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>
                    <div>
                        <label for="total_harga" class="block mb-2 text-gray-700 dark:text-gray-300">Total Harga</label>
                        <input type="number" name="total_harga" id="total_harga"
                            class="w-full rounded-lg p-2 border dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>
                </div>
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
</x-app-layout>
