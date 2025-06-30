<x-app-layout>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <h2 class="text-lg font-semibold border py-4 pl-6 pr-8 text-red-800 flex items-center gap-2">
                Tambah User
            </h2>
        </div>
        <div class="bg-white border dark:bg-gray-800 sm:rounded-lg p-6">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_user" class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Nama
                            User</label>
                        <input type="text" name="nama_user" id="nama_user"
                            class="w-full rounded-lg p-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>
                    <div>
                        <label for="level"
                            class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Level</label>
                        <select name="level" id="level"
                            class="w-full rounded-lg p-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                            <option value="">Pilih Level</option>
                            <option value="Admin">Admin</option>
                            <option value="Kasir">Kasir</option>
                        </select>
                    </div>
                    <div>
                        <label for="username"
                            class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                        <input type="text" name="username" id="username"
                            class="w-full rounded-lg p-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>
                    <div>
                        <label for="password"
                            class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full rounded-lg p-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-red-500 focus:border-red-500 transition"
                            required>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('user.index') }}"
                        class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-4 py-2 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
