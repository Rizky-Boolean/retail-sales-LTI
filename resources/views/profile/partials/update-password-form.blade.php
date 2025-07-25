<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Ubah Password') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Pastikan akun Anda menggunakan password panjang dan acak untuk keamanan lebih baik.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Password Saat Ini --}}
        <div x-data="{ show: false }">
            <x-input-label for="current_password" :value="__('Password Saat Ini')" />
            <div class="relative mt-1">
                <x-text-input id="current_password" name="current_password" ::type="show ? 'text' : 'password'" class="block w-full" autocomplete="current-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="eye-off" class="w-5 h-5" x-show="!show"></i>
                    <i data-lucide="eye" class="w-5 h-5" x-show="show" x-cloak></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Password Baru --}}
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password Baru')" />
            <div class="relative mt-1">
                <x-text-input id="password" name="password" ::type="show ? 'text' : 'password'" class="block w-full" autocomplete="new-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="eye-off" class="w-5 h-5" x-show="!show"></i>
                    <i data-lucide="eye" class="w-5 h-5" x-show="show" x-cloak></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Konfirmasi Password --}}
        <div x-data="{ show: false }">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
            <div class="relative mt-1">
                <x-text-input id="password_confirmation" name="password_confirmation" ::type="show ? 'text' : 'password'" class="block w-full" autocomplete="new-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i data-lucide="eye-off" class="w-5 h-5" x-show="!show"></i>
                    <i data-lucide="eye" class="w-5 h-5" x-show="show" x-cloak></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                Simpan Perubahan
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Password berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</section>