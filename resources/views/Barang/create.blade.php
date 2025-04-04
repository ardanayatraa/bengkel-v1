<x-app-layout>
    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                    Tambah Barang
                </h2>
            </div>
            <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_barang" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="id_supplier"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier</label>
                            <select id="id_supplier" name="id_supplier"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition">
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="id_kategori"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                            <select id="id_kategori" name="id_kategori"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="harga_beli"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Beli</label>
                            <input type="number" id="harga_beli" name="harga_beli"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="harga_jual"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Jual</label>
                            <input type="number" id="harga_jual" name="harga_jual"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="stok"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Stok</label>
                            <input type="number" id="stok" name="stok"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="keterangan"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan</label>
                            <textarea id="keterangan" name="keterangan"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 text-right">
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
