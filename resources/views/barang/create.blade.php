<x-app-layout>
    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-4">
                <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                    Tambah Barang
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
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Barang --}}
                        <div>
                            <label for="nama_barang" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Barang
                            </label>
                            <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                required>
                        </div>

                        {{-- Supplier --}}
                        <div>
                            <label for="id_supplier" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Supplier
                            </label>
                            <select id="id_supplier" name="id_supplier"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                required>
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id_supplier }}"
                                        {{ old('id_supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>



                        {{-- Harga Beli --}}
                        <div>
                            <label for="harga_beli" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga Beli
                            </label>
                            <input type="number" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                min="0" step="0.01" required>
                        </div>

                        {{-- Harga Jual --}}
                        <div>
                            <label for="harga_jual" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Harga Jual
                            </label>
                            <input type="number" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                min="0" step="0.01" required>
                        </div>

                        {{-- Stok --}}
                        <div>
                            <label for="stok" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Stok
                            </label>
                            <input type="number" id="stok" name="stok" value="{{ old('stok') }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                min="0" required>
                        </div>

                        {{-- Keterangan --}}
                        <div class="md:col-span-2">
                            <label for="keterangan" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Keterangan
                            </label>
                            <textarea id="keterangan" name="keterangan" rows="3"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                placeholder="Masukkan keterangan barang (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('barang.index') }}"
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
    </div>
</x-app-layout>
