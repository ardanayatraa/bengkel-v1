<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 mb-4">Edit Transaksi</h2>
        <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
            <form action="{{ route('transaksi.update', $transaksi->id_transaksi) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Konsumen -->
                    <div>
                        <label class="block mb-2 font-medium">Konsumen</label>
                        <select id="id_konsumen" name="id_konsumen" required class="block w-full p-2 border rounded-lg">
                            @foreach ($konsumens as $k)
                                <option value="{{ $k->id_konsumen }}" data-keterangan="{{ strtolower($k->keterangan) }}"
                                    data-point="{{ $k->jumlah_point }}"
                                    {{ $k->id_konsumen == $transaksi->id_konsumen ? 'selected' : '' }}>
                                    {{ $k->nama_konsumen }} — {{ $k->jumlah_point }} Point
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Teknisi -->
                    <div>
                        <label class="block mb-2 font-medium">Teknisi</label>
                        <select name="id_teknisi" class="block w-full p-2 border rounded-lg">
                            <option value="">— Pilih Teknisi —</option>
                            @foreach ($teknisis as $t)
                                <option value="{{ $t->id_teknisi }}"
                                    {{ $t->id_teknisi == $transaksi->id_teknisi ? 'selected' : '' }}>
                                    {{ $t->nama_teknisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Status Service -->
                    <div>
                        <label class="block mb-2 font-medium">Status Service</label>
                        <select name="status_service" class="block w-full p-2 border rounded-lg">
                            @foreach (['proses', 'selesai', 'diambil'] as $s)
                                <option value="{{ $s }}"
                                    {{ $transaksi->status_service == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Barang -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-medium">Barang</label>
                        <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded-lg p-2">
                            @foreach ($barangs as $b)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="id_barang[]" value="{{ $b->id_barang }}"
                                        data-harga="{{ $b->harga_jual }}"
                                        {{ in_array($b->id_barang, $transaksi->id_barang) ? 'checked' : '' }}
                                        class="h-4 w-4">
                                    <span>{{ $b->nama_barang }} — Rp
                                        {{ number_format($b->harga_jual, 0, ',', '.') }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <!-- Jasa -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-medium">Jasa</label>
                        <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded-lg p-2">
                            @foreach ($jasas as $j)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="id_jasa[]" value="{{ $j->id_jasa }}"
                                        data-harga="{{ $j->harga_jasa }}"
                                        {{ in_array($j->id_jasa, $transaksi->id_jasa) ? 'checked' : '' }}
                                        class="h-4 w-4">
                                    <span>{{ $j->nama_jasa }} — Rp
                                        {{ number_format($j->harga_jasa, 0, ',', '.') }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <!-- Estimasi -->
                    <div id="estimasi_wrapper" class="hidden md:col-span-2">
                        <label class="block mb-2 font-medium">Estimasi Pengerjaan</label>
                        <input type="text" name="estimasi_pengerjaan" value="{{ $transaksi->estimasi_pengerjaan }}"
                            class="block w-full p-2 border rounded-lg">
                        @error('estimasi_pengerjaan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Tanggal -->
                    <div>
                        <label class="block mb-2 font-medium">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi"
                            value="{{ $transaksi->tanggal_transaksi->format('Y-m-d') }}"
                            class="block w-full p-2 border rounded-lg">
                    </div>
                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block mb-2 font-medium">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="block w-full p-2 border rounded-lg">
                            @foreach (['Cash', 'QRIS'] as $m)
                                <option value="{{ $m }}"
                                    {{ $transaksi->metode_pembayaran == $m ? 'selected' : '' }}>
                                    {{ $m }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Total Harga -->
                    <div>
                        <label class="block mb-2 font-medium">Total Harga</label>
                        <input type="text" id="total_harga_display" readonly
                            value="Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}"
                            class="block w-full p-2 border rounded-lg">
                        <input type="hidden" name="total_harga" id="total_harga"
                            value="{{ $transaksi->total_harga }}">
                    </div>
                </div>
                <div class="mt-6 flex justify-between">
                    <a href="{{ route('transaksi.index') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Batal</a>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatRupiah(n) {
            return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function calc() {
            let sum = 0;
            document.querySelectorAll('input[name="id_barang[]"]:checked').forEach(o => sum += +o.dataset.harga);
            document.querySelectorAll('input[name="id_jasa[]"]:checked').forEach(o => sum += +o.dataset.harga);
            const kons = document.querySelector('#id_konsumen option:checked');
            const member = kons.dataset.keterangan === 'member';
            const pts = +kons.dataset.point;
            if (member && pts >= 10) sum -= 10000;
            document.getElementById('estimasi_wrapper').classList.toggle('hidden', document.querySelectorAll(
                'input[name="id_jasa[]"]:checked').length === 0);
            document.getElementById('total_harga').value = sum;
            document.getElementById('total_harga_display').value = formatRupiah(sum);
        }
        document.querySelectorAll('#id_konsumen,input[name="id_barang[]"],input[name="id_jasa[]"]').forEach(e => e
            .addEventListener('change', calc));
        window.addEventListener('load', calc);
    </script>
</x-app-layout>
