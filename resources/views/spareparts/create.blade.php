<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Data Sparepart Baru') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('spareparts.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="kode_part" :value="__('Kode Part')" />
                                <x-text-input id="kode_part" name="kode_part" type="text" class="mt-1 block w-full" :value="old('kode_part')" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="nama_part" :value="__('Nama Part')" />
                                <x-text-input id="nama_part" name="nama_part" type="text" class="mt-1 block w-full" :value="old('nama_part')" required />
                            </div>
                            <div>
                                <x-input-label for="satuan" :value="__('Satuan')" />
                                <select id="satuan" name="satuan" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach(['Pcs', 'Set', 'Unit', 'Liter', 'Botol', 'Box', 'Roll'] as $satuan)
                                        <option value="{{ $satuan }}" {{ old('satuan') == $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            * Markup profit standar akan diatur ke 40%. Anda dapat mengubahnya nanti melalui menu Edit.
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('spareparts.index') }}" class="text-sm mr-4">Batal</a>
                            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
