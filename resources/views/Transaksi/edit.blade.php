<x-app-layout>
    <div class="">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                    Edit Transaksi
                </h2>
            </div>
            <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
                <form action="{{ route('transaksis.update', $transaksi->id_transaksi) }}" method="POST">
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
                                    <option value="{{ $konsumen->id_konsumen }}" @selected($transaksi->id_konsumen == $konsumen->id_konsumen)>
                                        {{ $konsumen->nama_konsumen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="id_barang"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Barang</label>
                            <select name="id_barang" id="id_barang"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                                <option value="">-- Pilih Barang (Opsional) --</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id_barang }}" @selected($transaksi->id_barang == $barang->id_barang)>
                                        {{ $barang->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="id_jasa"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Jasa</label>
                            <select name="id_jasa" id="id_jasa"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition">
                                <option value="">-- Pilih Jasa (Opsional) --</option>
                                @foreach ($jasas as $jasa)
                                    <option value="{{ $jasa->id_jasa }}" @selected($transaksi->id_jasa == $jasa->id_jasa)>
                                        {{ $jasa->nama_jasa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>



                        <div>
                            <label for="tanggal_transaksi"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal
                                Transaksi</label>
                            <input type="date" id="tanggal_transaksi" name="tanggal_transaksi"
                                value="{{ $transaksi->tanggal_transaksi }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="metode_pembayaran"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Metode
                                Pembayaran</label>
                            <input type="text" id="metode_pembayaran" name="metode_pembayaran"
                                value="{{ $transaksi->metode_pembayaran }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                                required>
                        </div>

                        <div>
                            <label for="total_harga"
                                class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Total Harga</label>
                            <input type="number" id="total_harga" name="total_harga"
                                value="{{ $transaksi->total_harga }}"
                                class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 rounded-lg p-2 transition"
                                required>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('transaksis.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">Batal</a>
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
