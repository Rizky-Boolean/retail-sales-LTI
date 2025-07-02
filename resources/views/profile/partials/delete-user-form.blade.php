<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Hapus Akun
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Setelah akun Anda dihapus, semua data dan sumber daya terkait akan dihapus secara permanen. Pastikan Anda sudah menyimpan data penting sebelum melanjutkan.
        </p>
    </header>

    {{-- Tombol Utama untuk Buka Modal --}}
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-6 py-2.5 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
        Hapus Akun
    </button>

    {{-- Modal Konfirmasi --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Apakah Anda yakin ingin menghapus akun?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Setelah akun Anda dihapus, semua data terkait akan dihapus secara permanen. Masukkan password Anda untuk konfirmasi.
            </p>

            {{-- Input Password dengan Toggle --}}
            <div class="mt-6 relative">
                <x-input-label for="password" value="Password" class="sr-only" />
                <x-text-input
                    id="delete_password"
                    name="password"
                    type="password"
                    class="block w-full pr-10"
                    placeholder="Masukkan Password Anda" />

                {{-- Tombol Eye Icon --}}
                <button type="button" onclick="toggleVisibility('delete_password')"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 dark:text-gray-300">
                    👁️
                </button>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            {{-- Tombol Batal dan Hapus --}}
            <div class="mt-6 flex justify-end">
                <button type="button" x-on:click="$dispatch('close')"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition duration-150 ease-in-out">
                    Batal
                </button>

                <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 ml-3 text-base font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-lg shadow-sm transition duration-150 ease-in-out">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>

    {{-- Script Toggle Password --}}
    <script>
        function toggleVisibility(id) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</section>
