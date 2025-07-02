<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Sparepart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700"> {{-- Shadow lebih dalam, padding lebih besar, border --}}
                <form action="{{ route('spareparts.update', $sparepart->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Penting untuk metode UPDATE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> {{-- Gap antar kolom --}}
                        {{-- Kode Part --}}
                        <div class="mb-4"> {{-- Margin bawah untuk jarak antar baris --}}
                            <x-input-label for="kode_part" :value="__('Kode Part')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" /> {{-- Label lebih menonjol --}}
                            <x-text-input id="kode_part" name="kode_part" type="text"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out"
                                :value="old('kode_part', $sparepart->kode_part)" required autofocus /> {{-- Styling input yang konsisten --}}
                            <x-input-error :messages="$errors->get('kode_part')" class="mt-2 text-red-600" /> {{-- Warna error yang jelas --}}
                        </div>
                        {{-- Nama Part --}}
                        <div class="mb-4"> {{-- Margin bawah untuk jarak antar baris --}}
                            <x-input-label for="nama_part" :value="__('Nama Part')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                            <x-text-input id="nama_part" name="nama_part" type="text"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out"
                                :value="old('nama_part', $sparepart->nama_part)" required />
                            <x-input-error :messages="$errors->get('nama_part')" class="mt-2 text-red-600" />
                        </div>
                        {{-- Satuan --}}
                        <div class="mb-4"> {{-- Margin bawah untuk jarak antar baris --}}
                            <x-input-label for="satuan" :value="__('Satuan')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                            <select id="satuan" name="satuan"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition duration-150 ease-in-out" required> {{-- Styling select yang konsisten --}}
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuans as $satuan)
                                    {{-- Logika ini akan otomatis memilih satuan yang sudah tersimpan di database --}}
                                    <option value="{{ $satuan }}" {{ old('satuan', $sparepart->satuan) == $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('satuan')" />
                        </div>
                        {{-- Markup Profit Standar --}}
                        <div class="mb-4"> {{-- Margin bawah untuk jarak antar baris --}}
                            <x-input-label for="markup_persen" :value="__('Markup Profit Standar (%)')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                            <x-text-input id="markup_persen" name="markup_persen" type="number" step="0.01"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out"
                                :value="old('markup_persen', $sparepart->markup_persen)" required />
                            <x-input-error :messages="$errors->get('markup_persen')" class="mt-2 text-red-600" />
                        </div>

                        {{-- Menampilkan Harga Beli Terakhir (Read-Only) --}}
                        <div class="mb-4"> {{-- Margin bawah untuk jarak antar baris --}}
                            <x-input-label for="harga_beli" :value="__('Harga Beli Terakhir (Rp)')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                            <x-text-input id="harga_beli" type="text"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 cursor-not-allowed" {{-- Styling read-only --}}
                                value="{{ number_format($sparepart->harga_beli_terakhir, 0, ',', '.') }}" readonly />
                        </div>
                        {{-- Menampilkan Harga Jual Saat Ini (Read-Only) --}}
                        <div class="mb-4"> {{-- Margin bawah untuk jarak antar baris --}}
                            <x-input-label for="harga_jual" :value="__('Harga Jual Saat Ini (Rp) - Otomatis')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                            <x-text-input id="harga_jual" type="text"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 cursor-not-allowed" {{-- Styling read-only --}}
                                value="{{ number_format($sparepart->harga_jual, 0, ',', '.') }}" readonly />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700"> {{-- Margin atas lebih besar, padding atas, border atas untuk pemisah --}}
                        <a href="{{ route('spareparts.index') }}" class="inline-flex items-center px-6 py-2.5 border border-gray-300 text-base font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition duration-150 ease-in-out mr-4"> {{-- Styling tombol Batal --}}
                            {{ __('Batal') }}
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"> {{-- Styling tombol Simpan --}}
                            {{ __('Update Markup') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
