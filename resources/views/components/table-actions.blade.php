<div class="flex items-center gap-4 text-sm">
    {{-- Edit --}}
    <a href="{{ $editRoute }}" class="text-blue-600 hover:underline">
        Edit
    </a>

    {{-- Trigger Delete --}}
    <button class="text-red-600 hover:underline" onclick="openDeleteModal()">
        Hapus
    </button>
</div>

{{-- Modal Delete --}}
<div id="deleteModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
    <div id="deleteModalBox"
        class="bg-white rounded-2xl p-6 w-[90%] max-w-[400px] shadow-xl scale-95 opacity-0 transform transition-all duration-300 relative">
        <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
        <p class="text-sm text-gray-600 mb-6">Yakin mau hapus data ini? Tindakan ini tidak bisa dibatalkan.</p>

        <div class="flex justify-end space-x-3">
            <button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300"
                onclick="closeDeleteModal()">
                Batal
            </button>

            <form action="{{ $deleteRoute }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const box = document.getElementById('deleteModalBox');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Trigger animasi
        setTimeout(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10); // Delay dikit buat transisi
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const box = document.getElementById('deleteModalBox');

        // Reverse animasi dulu
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-95', 'opacity-0');

        // Delay biar animasi kelar baru di-hide
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }

    // Close modal kalau klik di luar
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        const box = document.getElementById('deleteModalBox');
        if (event.target === modal && !box.contains(event.target)) {
            closeDeleteModal();
        }
    });
</script>
