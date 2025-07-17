<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pengguna: ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <!-- Role (Hanya Tampilan) -->
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <div class="mt-1 block w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm">
                                <span class="capitalize">{{ str_replace('_', ' ', $user->role) }}</span>
                            </div>
                        </div>

                        <!-- Cabang (Hanya tampil jika rolenya admin cabang) -->
                        @if($user->role === 'admin_cabang')
                        <div class="mt-4">
                            <x-input-label for="cabang_id" :value="__('Penempatan Cabang')" />
                            <select name="cabang_id" id="cabang_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($cabangs as $cabang)
                                <option value="{{ $cabang->id }}" {{ old('cabang_id', $user->cabang_id) == $cabang->id ? 'selected' : '' }}>{{ $cabang->nama_cabang }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('cabang_id')" class="mt-2" />
                        </div>
                        @endif
                        
                        <!-- Password (Opsional) -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password Baru (Kosongkan jika tidak diubah)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>