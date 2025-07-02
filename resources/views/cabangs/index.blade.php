<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Cabang') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Tombol Tambah Cabang --}}
                <div class="flex justify-end mb-4">
                    <a href="{{ route('cabangs.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Cabang
                    </a>
                </div>

                {{-- Alert Message --}}
                @include('partials.alert-messages')

                {{-- Tabel Data Cabang --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Nama Cabang</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Alamat</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($cabangs as $cabang)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $cabang->nama_cabang }}</td>
                                    <td class="py-3 px-4">{{ $cabang->alamat }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('cabangs.edit', $cabang) }}" class="text-blue-500 hover:text-blue-700 py-1 px-3 rounded">Edit</a>
                                            <form action="{{ route('cabangs.destroy', $cabang) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 py-1 px-3 rounded">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data cabang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $cabangs->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
