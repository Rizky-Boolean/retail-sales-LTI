<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Cabang Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('cabangs.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="nama_cabang" :value="__('Nama Cabang')" />
                            <x-text-input id="nama_cabang" class="block mt-1 w-full" type="text" name="nama_cabang" :value="old('nama_cabang')" required autofocus />
                            <x-input-error :messages="$errors->get('nama_cabang')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="alamat" :value="__('Alamat Cabang')" />
                            <textarea id="alamat" name="alamat" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('alamat') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                            <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700"> {{-- Margin atas lebih besar, padding atas, border atas untuk pemisah --}}
                            <a href="{{ route('cabangs.index') }}" class="inline-flex items-center px-6 py-2.5 border border-gray-300 text-base font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition duration-150 ease-in-out mr-4"> {{-- Styling tombol Batal --}}
                                {{ __('Batal') }}
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"> {{-- Styling tombol Simpan --}}
                                {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
