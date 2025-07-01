<x-app-layout>
    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                    Edit Jasa
                </h2>
            </div>
            <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
                <form action="{{ route('jasa.update', $jasa->id_jasa) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_jasa" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                                Jasa</label>
                            <input type="text" id="nama_jasa" name="nama_jasa"
                                value="{{ old('nama_jasa', $jasa->nama_jasa) }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="harga_jasa"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Jasa</label>
                            <input type="number" id="harga_jasa" name="harga_jasa"
                                value="{{ old('harga_jasa', $jasa->harga_jasa) }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="keterangan"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan</label>
                            <textarea id="keterangan" name="keterangan"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition">{{ old('keterangan', $jasa->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('jasa.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
