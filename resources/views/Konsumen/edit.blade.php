<x-app-layout>
    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                    Edit Konsumen
                </h2>
            </div>
            <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
                <form action="{{ route('konsumen.update', $konsumen->id_konsumen) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_konsumen"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Konsumen</label>
                            <input type="text" id="nama_konsumen" name="nama_konsumen"
                                value="{{ $konsumen->nama_konsumen }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="no_kendaraan" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">No
                                Kendaraan</label>
                            <input type="text" id="no_kendaraan" name="no_kendaraan"
                                value="{{ $konsumen->no_kendaraan }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                        </div>

                        <div>
                            <label for="no_telp" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">No
                                Telepon</label>
                            <input type="text" id="no_telp" name="no_telp" value="{{ $konsumen->no_telp }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                        </div>

                        <div>
                            <label for="jumlah_point"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Point</label>
                            <input type="number" id="jumlah_point" name="jumlah_point"
                                value="{{ $konsumen->jumlah_point }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                            <textarea id="alamat" name="alamat"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">{{ $konsumen->alamat }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label for="keterangan"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan</label>
                            <textarea id="keterangan" name="keterangan"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">{{ $konsumen->keterangan }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('konsumen.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">Batal</a>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
