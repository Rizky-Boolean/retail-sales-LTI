<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Sparepart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search dan Tombol Aksi --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" placeholder="Cari sparepart..."
                               class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>
                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col md:flex-row flex-wrap gap-3 md:gap-4">
                        {{-- Tambah Sparepart --}}
                        <a href="{{ route('spareparts.create') }}"
                           class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Sparepart
                        </a>

                        {{-- Import Data --}}
                        <a href="{{ route('spareparts.import.show') }}"
                           class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Import Data
                        </a>

                        {{-- [UBAH] Ganti link ke data nonaktif --}}
                        <a href="{{ route('spareparts.inactive') }}"
                           class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639l4.316-4.316a1.012 1.012 0 0 1 1.415 0l4.316 4.316a1.012 1.012 0 0 1 0 .639l-4.316 4.316a1.012 1.012 0 0 1-1.415 0l-4.316-4.316ZM12.322 2.036a1.012 1.012 0 0 1 .639 0l4.316 4.316a1.012 1.012 0 0 1 0 1.415l-4.316 4.316a1.012 1.012 0 0 1-.639 0l-4.316-4.316a1.012 1.012 0 0 1 0-1.415l4.316-4.316Z" /></svg>
                            Lihat Data Nonaktif
                        </a>
                    </div>
                </div>

                {{-- Menampilkan pesan alert --}}
                @include('partials.alert-messages')
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="sparepartsTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kode Part</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Satuan</th>
                                <th class="text-right py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Jual</th>
                                <th class="text-center py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 data-row">
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->kode_part }}</td>
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->nama_part }}</td>
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->satuan }}</td>
                                    <td class="text-right py-3 px-4 whitespace-nowrap">{{ 'Rp ' . number_format($sparepart->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-center py-3 px-4 whitespace-nowrap">
                                        <a href="{{ route('spareparts.edit', $sparepart->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">Edit</a>
                                        {{-- [UBAH] Ganti tombol hapus dengan form nonaktifkan --}}
                                        <form action="{{ route('spareparts.toggleStatus', $sparepart) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 py-1 px-2 rounded" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan data ini?')">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data sparepart aktif.</td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" class="hidden">
                                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data sparepart yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $spareparts->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Script untuk Search --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let typingTimer;
        const doneTypingInterval = 300; // Wait 300ms after user stops typing

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        function performSearch() {
            const searchValue = searchInput.value;
            const tbody = document.querySelector('#sparepartsTable tbody');
            const noResultsRow = document.getElementById('noResultsRow');

            fetch(`/spareparts/search?search=${encodeURIComponent(searchValue)}`)
                .then(response => response.json())
                .then(data => {
                    // Clear existing rows except the noResultsRow
                    Array.from(tbody.children).forEach(child => {
                        if (!child.id || (child.id !== 'noResultsRow' && child.id !== 'initialEmptyRow')) {
                            child.remove();
                        }
                    });

                    if (data.length > 0) {
                        noResultsRow.classList.add('hidden');
                        data.forEach(sparepart => {
                            const row = document.createElement('tr');
                            row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 data-row';
                            row.innerHTML = `
                                <td class="text-left py-3 px-4 whitespace-nowrap">${sparepart.kode_part}</td>
                                <td class="text-left py-3 px-4 whitespace-nowrap">${sparepart.nama_part}</td>
                                <td class="text-left py-3 px-4 whitespace-nowrap">${sparepart.satuan}</td>
                                <td class="text-right py-3 px-4 whitespace-nowrap">Rp ${Number(sparepart.harga_jual).toLocaleString('id-ID')}</td>
                                <td class="text-center py-3 px-4 whitespace-nowrap">
                                    <a href="/spareparts/${sparepart.id}/edit" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">Edit</a>
                                    <button type="button" onclick="showDeleteModal(${sparepart.id})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 py-1 px-2 rounded">Hapus</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    } else {
                        noResultsRow.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
    </script>
</x-app-layout>
