<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Cabang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('cabangs.update', $cabang) }}">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="nama_cabang" :value="__('Nama Cabang')" />
                            <x-text-input id="nama_cabang" class="block mt-1 w-full" type="text" name="nama_cabang" :value="old('nama_cabang', $cabang->nama_cabang)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_cabang')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="alamat" :value="__('Alamat Cabang')" />
                            <textarea id="alamat" name="alamat" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('alamat', $cabang->alamat) }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('cabangs.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
