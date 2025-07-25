<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Data Sparepart Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Menghapus 'overflow-hidden' dari div ini agar dropdown tidak terpotong --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700"> {{-- Shadow lebih dalam, padding lebih besar, border --}}
                <form action="{{ route('spareparts.store') }}" method="POST">
                    @csrf
                    {{-- Mengubah grid menjadi flex flex-col untuk susunan vertikal --}}
                    <div class="flex flex-col gap-6"> {{-- Gunakan flex flex-col untuk susunan vertikal --}}
                        {{-- Kode Part --}}
                        <div class="mb-0"> {{-- Margin bawah diatur ke 0 karena gap sudah menangani jarak --}}
                            <x-input-label for="kode_part" :value="__('Kode Part')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                            <x-text-input id="kode_part" name="kode_part" type="text"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out"
                                :value="old('kode_part')" required autofocus />
                            <x-input-error :messages="$errors->get('kode_part')" class="mt-2 text-red-600" />
                        </div>
                        {{-- Nama Part --}}
                        <div class="mb-0"> {{-- Margin bawah diatur ke 0 karena gap sudah menangani jarak --}}
                            <x-input-label for="nama_part" :value="__('Nama Part')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                            <x-text-input id="nama_part" name="nama_part" type="text"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out"
                                :value="old('nama_part')" required />
                            <x-input-error :messages="$errors->get('nama_part')" class="mt-2 text-red-600" />
                        </div>
                        {{-- Satuan --}}
                        <div class="mb-0"> {{-- Margin bawah diatur ke 0 karena gap sudah menangani jarak --}}
                            <x-input-label for="satuan" :value="__('Satuan')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                            <select id="satuan" name="satuan"
                                class="block w-full p-3 text-base rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition duration-150 ease-in-out appearance-none pr-8 cursor-pointer" required> {{-- Tambah appearance-none dan pr-8 --}}
                                <option value="">-- Pilih Satuan --</option>
                                @foreach(['Pcs', 'Set', 'Unit', 'Liter', 'Botol', 'Box', 'Roll'] as $satuan)
                                    <option value="{{ $satuan }}" {{ old('satuan') == $satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                                @endforeach
                            </select>
                            {{-- Tambahkan ikon dropdown kustom jika diperlukan, tapi ini akan memerlukan CSS tambahan --}}
                            {{-- Contoh: <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 6.757 7.586 5.343 9l4.95 4.95z"/></svg>
                            </div> --}}
                            <x-input-error :messages="$errors->get('satuan')" class="mt-2 text-red-600" />
                        </div>
                    </div>
                    <div class="mt-4 mb-6 text-sm text-gray-600 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
                        <p>* Markup profit standar akan diatur ke 40%. Anda dapat mengubahnya nanti melalui menu Edit.</p>
                    </div>

                     <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('spareparts.index') }}" class="inline-flex items-center justify-center px-6 py-2 border border-transparent font-medium text-red-600 dark:text-red-500 hover:underline inline-block mx-1">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                {{ __('Simpan') }}
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
