<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Form -->
                    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <!-- Nama Supplier -->
                            <div>
                                <x-input-label for="nama_supplier" :value="__('Nama Supplier')" />
                                <x-text-input id="nama_supplier" name="nama_supplier" type="text" class="mt-1 block w-full" :value="old('nama_supplier', $supplier->nama_supplier)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('nama_supplier')" />
                            </div>

                            <!-- Alamat -->
                            <div>
                                <x-input-label for="alamat" :value="__('Alamat')" />
                                <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('alamat', $supplier->alamat) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                            </div>

                            <!-- Kontak -->
                            <div>
                                <x-input-label for="kontak" :value="__('Kontak (No. Telepon/HP)')" />
                                <x-text-input id="kontak" name="kontak" type="text" class="mt-1 block w-full" :value="old('kontak', $supplier->kontak)" />
                                <x-input-error class="mt-2" :messages="$errors->get('kontak')" />
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
