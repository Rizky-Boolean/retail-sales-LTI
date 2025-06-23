<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Sparepart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('spareparts.update', $sparepart->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Input Kode, Nama, Satuan masih sama -->
                            <div>
                                <x-input-label for="kode_part" :value="__('Kode Part')" />
                                <x-text-input id="kode_part" name="kode_part" type="text" class="mt-1 block w-full" :value="old('kode_part', $sparepart->kode_part)" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="nama_part" :value="__('Nama Part')" />
                                <x-text-input id="nama_part" name="nama_part" type="text" class="mt-1 block w-full" :value="old('nama_part', $sparepart->nama_part)" required />
                            </div>
                            <div>
                                <x-input-label for="satuan" :value="__('Satuan')" />
                                <select id="satuan" name="satuan" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach($satuans as $satuan)
                                        {{-- Logika ini akan otomatis memilih satuan yang sudah tersimpan di database --}}
                                        <option value="{{ $satuan }}" {{ old('satuan', $sparepart->satuan) == $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('satuan')" />
                            </div>
                            <!-- Input baru untuk Markup -->
                            <div>
                                <x-input-label for="markup_persen" :value="__('Markup Profit (%)')" />
                                <x-text-input id="markup_persen" name="markup_persen" type="number" step="0.01" class="mt-1 block w-full" :value="old('markup_persen', $sparepart->markup_persen)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('markup_persen')" />
                            </div>

                             <!-- Menampilkan Harga Beli dan Harga Jual (Read-Only) -->
                             <div>
                                <x-input-label for="harga_beli" :value="__('Harga Beli Terakhir (Rp)')" />
                                <x-text-input id="harga_beli" type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" value="{{ number_format($sparepart->harga_beli_terakhir, 0, ',', '.') }}" readonly />
                            </div>
                             <div>
                                <x-input-label for="harga_jual" :value="__('Harga Jual Saat Ini (Rp) - Otomatis')" />
                                <x-text-input id="harga_jual" type="text" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" value="{{ number_format($sparepart->harga_jual, 0, ',', '.') }}" readonly />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('spareparts.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Update Markup') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
