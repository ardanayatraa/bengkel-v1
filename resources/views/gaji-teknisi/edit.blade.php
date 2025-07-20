<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold">Edit Gaji Teknisi</h2>
            <a href="{{ route('gaji-teknisi.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                Kembali
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('gaji-teknisi.update', $gajiTeknisi->id_gaji_teknisi) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teknisi -->
                    <div>
                        <label for="id_teknisi" class="block text-sm font-medium text-gray-700 mb-2">
                            Teknisi <span class="text-red-500">*</span>
                        </label>
                        <select name="id_teknisi" id="id_teknisi" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Pilih Teknisi</option>
                            @foreach($teknisis as $teknisi)
                                <option value="{{ $teknisi->id_teknisi }}" 
                                        {{ old('id_teknisi', $gajiTeknisi->id_teknisi) == $teknisi->id_teknisi ? 'selected' : '' }}>
                                    {{ $teknisi->nama_teknisi }} ({{ $teknisi->persentase_gaji }}%)
                                </option>
                            @endforeach
                        </select>
                        @error('id_teknisi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transaksi -->
                    <div>
                        <label for="id_transaksi" class="block text-sm font-medium text-gray-700 mb-2">
                            Transaksi <span class="text-red-500">*</span>
                        </label>
                        <select name="id_transaksi" id="id_transaksi" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Pilih Transaksi</option>
                            @foreach($transaksis as $transaksi)
                                <option value="{{ $transaksi->id_transaksi }}" 
                                        {{ old('id_transaksi', $gajiTeknisi->id_transaksi) == $transaksi->id_transaksi ? 'selected' : '' }}>
                                    #{{ $transaksi->id_transaksi }} - {{ $transaksi->konsumen->nama_konsumen ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_transaksi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jasa -->
                    <div>
                        <label for="id_jasa" class="block text-sm font-medium text-gray-700 mb-2">
                            Jasa <span class="text-red-500">*</span>
                        </label>
                        <select name="id_jasa" id="id_jasa" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">Pilih Jasa</option>
                            @foreach($jasas as $jasa)
                                <option value="{{ $jasa->id_jasa }}" 
                                        data-harga="{{ $jasa->harga_jasa }}"
                                        {{ old('id_jasa', $gajiTeknisi->id_jasa) == $jasa->id_jasa ? 'selected' : '' }}>
                                    {{ $jasa->nama_jasa }} - Rp {{ number_format($jasa->harga_jasa, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_jasa')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Jasa -->
                    <div>
                        <label for="harga_jasa" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Jasa <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="harga_jasa" id="harga_jasa" 
                               value="{{ old('harga_jasa', $gajiTeknisi->harga_jasa) }}" required min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Masukkan harga jasa">
                        @error('harga_jasa')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Persentase Gaji -->
                    <div>
                        <label for="persentase_gaji" class="block text-sm font-medium text-gray-700 mb-2">
                            Persentase Gaji (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="persentase_gaji" id="persentase_gaji" 
                               value="{{ old('persentase_gaji', $gajiTeknisi->persentase_gaji) }}" required min="0" max="100" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Masukkan persentase gaji">
                        @error('persentase_gaji')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Kerja -->
                    <div>
                        <label for="tanggal_kerja" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Kerja <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_kerja" id="tanggal_kerja" 
                               value="{{ old('tanggal_kerja', $gajiTeknisi->tanggal_kerja) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        @error('tanggal_kerja')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mt-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Masukkan keterangan (opsional)">{{ old('keterangan', $gajiTeknisi->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Jumlah Gaji -->
                <div class="mt-6 p-4 bg-gray-50 rounded-md">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Preview Jumlah Gaji:</h3>
                    <div class="text-lg font-semibold text-red-800" id="preview-gaji">
                        Rp {{ number_format($gajiTeknisi->jumlah_gaji, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-red-800 text-white rounded hover:bg-red-900">
                        Update Gaji
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-fill harga jasa when jasa is selected
        document.getElementById('id_jasa').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga');
            if (harga) {
                document.getElementById('harga_jasa').value = harga;
                calculateGaji();
            }
        });

        // Auto-fill persentase gaji when teknisi is selected
        document.getElementById('id_teknisi').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const text = selectedOption.textContent;
            const match = text.match(/\((\d+(?:\.\d+)?)%\)/);
            if (match) {
                document.getElementById('persentase_gaji').value = match[1];
                calculateGaji();
            }
        });

        // Calculate gaji when harga or persentase changes
        document.getElementById('harga_jasa').addEventListener('input', calculateGaji);
        document.getElementById('persentase_gaji').addEventListener('input', calculateGaji);

        function calculateGaji() {
            const harga = parseFloat(document.getElementById('harga_jasa').value) || 0;
            const persentase = parseFloat(document.getElementById('persentase_gaji').value) || 0;
            const jumlahGaji = (harga * persentase) / 100;
            
            document.getElementById('preview-gaji').textContent = 
                'Rp ' + jumlahGaji.toLocaleString('id-ID');
        }

        // Calculate on page load
        calculateGaji();
    </script>
</x-app-layout> 