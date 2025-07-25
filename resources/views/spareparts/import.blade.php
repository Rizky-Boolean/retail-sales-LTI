<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Data Sparepart dari Excel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> {{-- Mengubah max-w-4xl untuk lebar yang lebih pas --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700"> {{-- Shadow lebih dalam, padding lebih besar, border --}}
                
                {{-- Tombol Kembali --}}
                <div class="mb-6"> {{-- Margin bawah untuk jarak --}}
                    <a href="{{ route('spareparts.index') }}" class="inline-flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-semibold rounded-lg transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ __('Kembali ke Master Sparepart') }}
                    </a>
                </div>

                <!-- Instruksi dan Tombol Download Template -->
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800 text-blue-800 dark:text-blue-200 shadow-sm"> {{-- Styling card instruksi --}}
                    <h4 class="font-bold text-lg mb-2">Panduan Import Data Sparepart</h4> {{-- Judul lebih besar --}}
                    <ol class="list-decimal list-inside text-sm leading-relaxed">
                        <li>Unduh format template Excel yang telah disediakan di bawah ini.</li>
                        <li>Isi data sparepart sesuai dengan kolom yang ada di template. Pastikan tidak mengubah nama *header* kolom.</li>
                        <li>Pastikan kolom `kode_part` unik dan tidak kosong.</li>
                        <li>Upload file Excel (.xlsx) yang sudah diisi pada form di bawah ini.</li>
                        <li>Sistem akan menampilkan pratinjau data dan validasi kesalahan sebelum data disimpan permanen.</li>
                    </ol>
                    <a href="{{ route('spareparts.import.template') }}" class="mt-4 inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        {{ __('Unduh Template Excel') }}
                    </a>
                </div>
                
                {{-- Alert Pesan Error Import --}}
                @if(session('import_errors'))
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800 text-red-800 dark:text-red-200 shadow-sm"> {{-- Styling card error --}}
                        <h4 class="font-bold mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Import Gagal! Ditemukan beberapa kesalahan:
                        </h4>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            @foreach(session('import_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Alert Pesan Sukses --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800 text-green-800 dark:text-green-200 shadow-sm">
                        <h4 class="font-bold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Sukses!
                        </h4>
                        <p class="mt-1 text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Form Upload File -->
                <form action="{{ route('spareparts.import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4"> {{-- Margin bawah untuk jarak --}}
                        <x-input-label for="file_import" :value="__('Pilih File Excel (.xlsx)')" class="mb-1 text-gray-700 dark:text-gray-300 font-medium" />
                        <input type="file" name="file_import" id="file_import"
                            class="block w-full text-sm text-gray-700 dark:text-gray-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-200 dark:hover:file:bg-blue-800
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out" required>
                        <x-input-error class="mt-2 text-red-600" :messages="$errors->get('file_import')" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            {{ __('Upload & Pratinjau') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
