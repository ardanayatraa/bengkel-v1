<x-app-layout>
  <div class="mx-auto sm:px-6 lg:px-8 py-6">
    <h2 class="text-lg font-semibold border-b pb-2 mb-6">Tambah Transaksi</h2>

    <form action="{{ route('transaksi.store') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Konsumen -->
        <div>
          <label class="block mb-1 font-medium">Konsumen</label>
          <select id="id_konsumen" name="id_konsumen" required
                  class="w-full p-2 border rounded">
            @foreach($konsumens as $k)
              <option value="{{ $k->id_konsumen }}"
                      data-keterangan="{{ strtolower($k->keterangan) }}"
                      data-point="{{ $k->jumlah_point }}">
                {{ $k->nama_konsumen }} — {{ $k->jumlah_point }} Point
              </option>
            @endforeach
          </select>
        </div>

        <!-- Teknisi -->
        <div>
          <label class="block mb-1 font-medium">Teknisi</label>
          <select name="id_teknisi" class="w-full p-2 border rounded">
            <option value="">— Pilih Teknisi —</option>
            @foreach($teknisis as $t)
              <option value="{{ $t->id_teknisi }}">{{ $t->nama_teknisi }}</option>
            @endforeach
          </select>
        </div>

        <!-- Status Service -->
        <div>
          <label class="block mb-1 font-medium">Status Service</label>
          <select name="status_service" required class="w-full p-2 border rounded">
            <option value="proses">Proses</option>
            <option value="selesai">Selesai</option>
            <option value="diambil">Diambil</option>
          </select>
        </div>

        <!-- Tanggal Transaksi -->
        <div>
          <label class="block mb-1 font-medium">Tanggal Transaksi</label>
          <input type="date" name="tanggal_transaksi" required
                 class="w-full p-2 border rounded">
        </div>

        <!-- Metode Pembayaran -->
        <div>
          <label class="block mb-1 font-medium">Metode Pembayaran</label>
          <select name="metode_pembayaran" required class="w-full p-2 border rounded">
            <option value="">Pilih Metode</option>
            <option value="Cash">Cash</option>
            <option value="QRIS">QRIS</option>
          </select>
        </div>

        <!-- Jasa -->
        <div class="md:col-span-2">
          <label class="block mb-1 font-medium">Jasa (bisa pilih banyak)</label>
          <div class="grid grid-cols-2 gap-2 p-2 border rounded max-h-40 overflow-auto">
            @foreach($jasas as $j)
              <label class="flex items-center space-x-2">
                <input type="checkbox" name="id_jasa[]" value="{{ $j->id_jasa }}"
                       data-harga="{{ $j->harga_jasa }}" class="h-4 w-4 jasa-cb">
                <span>{{ $j->nama_jasa }} — Rp {{ number_format($j->harga_jasa,0,',','.') }}</span>
              </label>
            @endforeach
          </div>
        </div>

        <!-- Estimasi (tampil jika ada jasa) -->
        <div id="estimasi_wrapper" class="hidden md:col-span-2">
          <label class="block mb-1 font-medium">Estimasi Pengerjaan</label>
          <input type="text" name="estimasi_pengerjaan"
                 class="w-full p-2 border rounded"
                 placeholder="Misal: 2 hari kerja">
          @error('estimasi_pengerjaan')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Barang + Qty -->
        <div class="md:col-span-2">
          <label class="block mb-1 font-medium">Barang (qty per item)</label>
          <div class="grid grid-cols-2 gap-2 p-2 border rounded max-h-40 overflow-auto">
            @foreach($barangs as $b)
              <div class="flex items-center space-x-2">
                <input type="checkbox" class="barang-cb"
                       name="id_barang[]" value="{{ $b->id_barang }}"
                       data-harga="{{ $b->harga_jual }}">
                <span class="flex-1">{{ $b->nama_barang }} — Rp {{ number_format($b->harga_jual,0,',','.') }}</span>
                <input type="number"
                       name="qty_barang[{{ $b->id_barang }}]"
                       value="1"
                       min="1"
                       disabled
                       class="qty-input w-16 p-1 border rounded text-sm"/>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Total Harga -->
        <div>
          <label class="block mb-1 font-medium">Total Harga</label>
          <input type="text" id="total_display" readonly
                 class="w-full p-2 border rounded mb-1"/>
          <input type="hidden" name="total_harga" id="total_harga"/>
        </div>
      </div>

      <div class="mt-6 flex justify-end">
        <button type="submit"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
          Simpan
        </button>
      </div>
    </form>
  </div>

  <script>
    function formatRupiah(n) {
      return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Toggle qty-input enable/disable
    document.querySelectorAll('.barang-cb').forEach(cb => {
      cb.addEventListener('change', () => {
        const qty = document.querySelector(`input[name="qty_barang[${cb.value}]"]`);
        qty.disabled = !cb.checked;
        calculate();
      });
    });

    // Recalculate on qty change or jasa change
    document.querySelectorAll('.qty-input, .jasa-cb').forEach(el => {
      el.addEventListener('input', calculate);
      el.addEventListener('change', calculate);
    });

    function calculate() {
      let sum = 0;

      // Barang
      document.querySelectorAll('.barang-cb:checked').forEach(cb => {
        const harga = +cb.dataset.harga;
        const qty   = +document.querySelector(`input[name="qty_barang[${cb.value}]"]`).value || 1;
        sum += harga * qty;
      });

      // Jasa
      document.querySelectorAll('.jasa-cb:checked').forEach(cb => {
        sum += +cb.dataset.harga;
      });

      // Estimasi toggle
      document.getElementById('estimasi_wrapper')
              .classList.toggle('hidden',
                document.querySelectorAll('.jasa-cb:checked').length === 0
              );

      // Diskon member
      const kons = document.querySelector('#id_konsumen option:checked');
      const isMember = kons.dataset.keterangan === 'member';
      const pts = +kons.dataset.point;
      if (isMember && pts >= 10) sum -= 10000;

      document.getElementById('total_harga').value    = sum;
      document.getElementById('total_display').value  = formatRupiah(sum);
    }

    window.addEventListener('load', calculate);
  </script>
</x-app-layout>
