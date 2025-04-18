<x-app-layout>
    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                    Edit Supplier
                </h2>
            </div>
            <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
                <form action="{{ route('supplier.update', $supplier->id_supplier) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_supplier"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Supplier</label>
                            <input type="text" id="nama_supplier" name="nama_supplier"
                                value="{{ $supplier->nama_supplier }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="no_telp" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">No.
                                Telp</label>
                            <input type="text" id="no_telp" name="no_telp" value="{{ $supplier->no_telp }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">{{ $supplier->alamat }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('supplier.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">Batal</a>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
