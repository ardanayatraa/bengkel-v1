<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="mb-4 flex items-center border pr-4 justify-between">
            <h2 class="text-lg font-semibold text-red-800 py-4 pl-6 pr-8 flex items-center gap-2">
                Data Teknisi
            </h2>
            <a href="{{ route('teknisi.create') }}"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                Tambah Teknisi
            </a>
        </div>
        <div class="border p-4 md:p-6 bg-white rounded-lg shadow-sm">
            @livewire('table.teknisi-table')
        </div>
    </div>
</x-app-layout>
