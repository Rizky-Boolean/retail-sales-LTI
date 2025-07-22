<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Data Sparepart dari Excel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-4">
                        <a href="{{ route('spareparts.index') }}" class="text-blue-500 hover:text-blue-700">
                            &larr; Kembali ke Master Sparepart
                        </a>
                    </div>

                    <!-- Instruksi dan Tombol Download Template -->
                    <div class="mb-6 p-4 bg-blue-100 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-200">
                        <h4 class="font-bold">Panduan Import</h4>
                        <ol class="list-decimal list-inside mt-2 text-sm">
                            <li>Unduh format template yang telah disediakan.</li>
                            <li>Isi data sparepart sesuai dengan kolom yang ada. Jangan mengubah nama header.</li>
                            <li>Upload file yang sudah diisi pada form di bawah ini.</li>
                            <li>Sistem akan menampilkan pratinjau data sebelum disimpan.</li>
                        </ol>
                        <a href="{{ route('spareparts.import.template') }}" class="mt-4 inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Unduh Template Excel
                        </a>
                    </div>
                    
                    
                    @if(session('import_errors'))
                        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200">
                            <h4 class="font-bold">Import Gagal! Ditemukan beberapa kesalahan:</h4>
                            <ul class="list-disc list-inside mt-2 text-sm">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Upload File -->
                    <form action="{{ route('spareparts.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="file_import" :value="__('Pilih File Excel (.xlsx)')" />
                            <input type="file" name="file_import" id="file_import" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            <x-input-error class="mt-2" :messages="$errors->get('file_import')" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Upload & Pratinjau') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
