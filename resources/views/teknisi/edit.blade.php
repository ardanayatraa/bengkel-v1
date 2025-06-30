<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <div class="mb-4">
            <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                Edit Teknisi
            </h2>
        </div>
        <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
            <form action="{{ route('teknisi.update', $teknisi->id_teknisi) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_teknisi" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Teknisi
                        </label>
                        <input type="text" id="nama_teknisi" name="nama_teknisi"
                            value="{{ old('nama_teknisi', $teknisi->nama_teknisi) }}" required
                            class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200
                     focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition">
                        @error('nama_teknisi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kontak" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kontak
                        </label>
                        <input type="text" id="kontak" name="kontak"
                            value="{{ old('kontak', $teknisi->kontak) }}"
                            class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200
                     focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg p-2 transition">
                        @error('kontak')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('teknisi.index') }}"
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
</x-app-layout>
