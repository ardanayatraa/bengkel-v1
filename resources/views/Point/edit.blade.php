<x-app-layout>
    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                    Edit Point
                </h2>
            </div>
            <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
                <form action="{{ route('point.update', $point->id_point) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="id_konsumen"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Konsumen</label>
                            <select name="id_konsumen" id="id_konsumen"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                                required>
                                <option value="">-- Pilih Konsumen --</option>
                                @foreach ($konsumens as $konsumen)
                                    <option value="{{ $konsumen->id_konsumen }}"
                                        {{ $point->id_konsumen == $konsumen->id_konsumen ? 'selected' : '' }}>
                                        {{ $konsumen->nama_konsumen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ $point->tanggal }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="jumlah_point"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Point</label>
                            <input type="number" id="jumlah_point" name="jumlah_point"
                                value="{{ $point->jumlah_point }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                                required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('point.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">Batal</a>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
