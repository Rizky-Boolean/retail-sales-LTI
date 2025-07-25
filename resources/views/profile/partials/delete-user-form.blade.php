<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Setelah akun Anda dihapus, semua data dan sumber daya terkait akan dihapus secara permanen. Pastikan Anda sudah menyimpan data penting sebelum melanjutkan.') }}
        </p>
    </header>

    {{-- Tombol Utama (Gaya lebih halus) --}}
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md text-red-600 bg-white border border-red-600 hover:bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-500 dark:hover:bg-red-900/20 transition-all duration-150 ease-in-out">
        Hapus Akun
    </button>

    {{-- Modal Konfirmasi (Desain Baru) --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('profile.destroy') }}" class="p-6" x-data="{ show: false }">
            @csrf
            @method('delete')

            <div class="text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Apakah Anda benar-benar yakin?
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Aksi ini tidak dapat dibatalkan. Masukkan password Anda untuk mengonfirmasi penghapusan akun secara permanen.
                </p>
            </div>

            {{-- Input Password dengan Toggle Alpine.js --}}
            <div class="mt-6">
                <x-input-label for="password_delete" value="Password" class="sr-only" />
                <div class="relative">
                    <x-text-input
                        id="password_delete"
                        name="password"
                        ::type="show ? 'text' : 'password'"
                        class="block w-full"
                        placeholder="Password"
                    />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i data-lucide="eye" class="w-5 h-5" x-show="!show"></i>
                        <i data-lucide="eye-off" class="w-5 h-5" x-show="show" x-cloak></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            {{-- Tombol Aksi Modal --}}
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition">
                    Batal
                </button>

                <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition">
                    Ya, Hapus Akun Saya
                </button>
            </div>
        </form>
    </x-modal>
    
    {{-- HAPUS BLOK SCRIPT LAMA --}}
</section>