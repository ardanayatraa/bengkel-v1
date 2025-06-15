{{-- resources/views/trx-barang-masuk/create.blade.php --}}
<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl p-8">

                {{-- Header --}}
                <h2 class="flex items-center space-x-3 text-2xl font-semibold text-gray-800 mb-8">
                    <span class="p-3 bg-red-600 rounded-full text-white">
                        {{-- Cube icon (Heroicons) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m0 0l8 4 8-4m-8 4v6" />
                        </svg>
                    </span>
                    <span>Tambah Transaksi Barang Masuk</span>
                </h2>

                <form action="{{ route('trx-barang-masuk.store') }}" method="POST">
                    @csrf

                    {{-- Tanggal Masuk --}}
                    <div class="mb-8">
                        <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Masuk
                        </label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            required>
                    </div>

                    {{-- Items Container --}}
                    <div id="items-container" class="space-y-4">
                        <template id="item-template">
                            <div
                                class="item-row bg-gray-50 border border-gray-200 rounded-lg p-4 grid grid-cols-12 gap-4 items-end">
                                {{-- Barang --}}
                                <div class="col-span-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                                    <select name="id_barang[]"
                                        class="barang-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        data-harga="0" required>
                                        <option value="">— Pilih Barang —</option>
                                        @foreach ($barangs as $b)
                                            <option value="{{ $b->id_barang }}" data-harga="{{ $b->harga_beli }}">
                                                {{ $b->nama_barang }} — {{ $b->supplier->nama_supplier }}
                                                (Rp{{ number_format($b->harga_beli, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Jumlah --}}
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                    <input type="number" name="jumlah[]" min="1"
                                        class="jumlah-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        required>
                                </div>

                                {{-- Subtotal --}}
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                    <div class="flex space-x-2">
                                        <input type="text" readonly
                                            class="subtotal-view flex-1 bg-white border border-gray-300 rounded-lg px-3 py-2">
                                        <button type="button"
                                            class="remove-item inline-flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-2 transition"
                                            title="Hapus baris">
                                            {{-- Trash icon --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                             00-1-1h-4a1 1 0 00-1 1v3m5 0H6" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="total_harga[]" class="subtotal-input">
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Controls --}}
                    <div class="mt-8 flex items-center justify-between">
                        <button type="button" id="add-item"
                            class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-5 py-2 transition">
                            {{-- Plus icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Barang</span>
                        </button>
                        <button type="submit"
                            class="inline-flex items-center space-x-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg px-5 py-2 transition">
                            {{-- Save icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 17a2 2 0 002 2h6l4-4V5a2 2 0 00-2-2H7a2 2 0 00-2 2v12z" />
                            </svg>
                            <span>Simpan Semua</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- JS Dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('items-container');
            const template = document.getElementById('item-template').content;
            const addBtn = document.getElementById('add-item');

            const formatRupiah = num => new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(num);

            // Tambah baris pertama otomatis
            addBtn.click();

            // Event: tambah baris baru
            addBtn.addEventListener('click', () => {
                const clone = document.importNode(template, true);
                container.appendChild(clone);
            });

            // Hitung subtotal & hapus baris
            container.addEventListener('input', e => {
                if (e.target.matches('.barang-select, .jumlah-input')) {
                    const row = e.target.closest('.item-row');
                    const harga = parseFloat(row.querySelector('.barang-select')
                        .selectedOptions[0]?.dataset.harga) || 0;
                    const qty = parseInt(row.querySelector('.jumlah-input').value) || 0;
                    const total = harga * qty;
                    row.querySelector('.subtotal-input').value = total;
                    row.querySelector('.subtotal-view').value = total ? formatRupiah(total) : '';
                }
            });

            container.addEventListener('click', e => {
                if (e.target.closest('.remove-item')) {
                    e.target.closest('.item-row').remove();
                }
            });
        });
    </script>
</x-app-layout>
