@props(['deleteRoute', 'modalId'])

<div class="flex items-center gap-4 text-sm">
    {{-- Trigger Delete --}}
    <button class="text-red-600 hover:underline" onclick="openDeleteModal('{{ $modalId }}')">
        Hapus
    </button>
</div>

{{-- Modal Delete --}}
<div id="deleteModal-{{ $modalId }}"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
    <div id="deleteModalBox-{{ $modalId }}"
        class="bg-white rounded-2xl p-6 w-[90%] max-w-[400px] shadow-xl scale-95 opacity-0 transform transition-all duration-300 relative">
        <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
        <p class="text-sm text-gray-600 mb-6">Yakin mau hapus data ini?</p>

        <div class="flex justify-end space-x-3">
            <button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300"
                onclick="closeDeleteModal('{{ $modalId }}')">Batal</button>

            <form action="{{ $deleteRoute }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id) {
        const modal = document.getElementById(`deleteModal-${id}`);
        const box = document.getElementById(`deleteModalBox-${id}`);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            box.classList.remove('scale-95', 'opacity-0');
            box.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteModal(id) {
        const modal = document.getElementById(`deleteModal-${id}`);
        const box = document.getElementById(`deleteModalBox-${id}`);
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }

    window.addEventListener('click', function(event) {
        // tutup modal jika klik di luar box
        document.querySelectorAll('[id^="deleteModal-"]').forEach(modal => {
            const id = modal.id.replace('deleteModal-', '');
            const box = document.getElementById(`deleteModalBox-${id}`);
            if (event.target === modal && !box.contains(event.target)) {
                closeDeleteModal(id);
            }
        });
    });
</script>
