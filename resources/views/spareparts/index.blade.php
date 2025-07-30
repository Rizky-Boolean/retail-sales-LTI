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

                        {{-- Lihat Data Nonaktif --}}
                        <a href="{{ route('spareparts.inactive') }}"
                           class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-150 ease-in-out">
                           <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639l4.316-4.316a1.012 1.012 0 0 1 1.415 0l4.316 4.316a1.012 1.012 0 0 1 0 .639l-4.316 4.316a1.012 1.012 0 0 1-1.415 0l-4.316-4.316ZM12.322 2.036a1.012 1.012 0 0 1 .639 0l4.316 4.316a1.012 1.012 0 0 1 0 1.415l-4.316 4.316a1.012 1.012 0 0 1-.639 0l-4.316-4.316a1.012 1.012 0 0 1 0-1.415l4.316-4.316Z" /></svg>
                            Lihat Data Nonaktif
                        </a>
                    </div>
                </div>

                @include('partials.alert-messages')
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="sparepartsTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150" onclick="sortColumn('kode_part')">
                                    <div class="flex items-center justify-between">
                                        <span>Kode Part</span>
                                        <div class="sort-icons flex flex-col ml-2">
                                            <svg class="sort-asc w-3 h-3 {{ request('sort') == 'kode_part' && request('direction') == 'asc' ? 'text-blue-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                            <svg class="sort-desc w-3 h-3 {{ request('sort') == 'kode_part' && request('direction') == 'desc' ? 'text-blue-500' : 'text-gray-400' }} rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150" onclick="sortColumn('nama_part')">
                                    <div class="flex items-center justify-between">
                                        <span>Nama Part</span>
                                        <div class="sort-icons flex flex-col ml-2">
                                            <svg class="sort-asc w-3 h-3 {{ request('sort') == 'nama_part' && request('direction') == 'asc' ? 'text-blue-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                            <svg class="sort-desc w-3 h-3 {{ request('sort') == 'nama_part' && request('direction') == 'desc' ? 'text-blue-500' : 'text-gray-400' }} rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150" onclick="sortColumn('satuan')">
                                    <div class="flex items-center justify-between">
                                        <span>Satuan</span>
                                        <div class="sort-icons flex flex-col ml-2">
                                            <svg class="sort-asc w-3 h-3 {{ request('sort') == 'satuan' && request('direction') == 'asc' ? 'text-blue-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                            <svg class="sort-desc w-3 h-3 {{ request('sort') == 'satuan' && request('direction') == 'desc' ? 'text-blue-500' : 'text-gray-400' }} rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-right py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150" onclick="sortColumn('harga_jual')">
                                    <div class="flex items-center justify-end">
                                        <span>Harga Jual</span>
                                        <div class="sort-icons flex flex-col ml-2">
                                            <svg class="sort-asc w-3 h-3 {{ request('sort') == 'harga_jual' && request('direction') == 'asc' ? 'text-blue-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                            <svg class="sort-desc w-3 h-3 {{ request('sort') == 'harga_jual' && request('direction') == 'desc' ? 'text-blue-500' : 'text-gray-400' }} rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-center py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 data-row">
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->kode_part }}</td>
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->nama_part }}</td>
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->satuan }}</td>
                                    <td class="text-right py-3 px-4 whitespace-nowrap" data-price="{{ $sparepart->harga_jual }}">{{ 'Rp ' . number_format($sparepart->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-center py-3 px-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-4">
                                            <a href="{{ route('spareparts.edit', $sparepart->id) }}" class="flex items-center gap-1 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                                <span>Edit</span>
                                            </a>
                                            {{-- [DIUBAH] Tombol Nonaktifkan kini membuka modal --}}
                                            <button type="button" onclick="showDeactivateModal('{{ route('spareparts.toggleStatus', $sparepart) }}', '{{ addslashes($sparepart->nama_part) }}')" class="flex items-center gap-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Nonaktifkan">
                                                <i data-lucide="power-off" class="w-4 h-4"></i>
                                                <span>Nonaktifkan</span>
                                            </button>
                                        </div>
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

                <div class="mt-6">
                    {{ $spareparts->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- [BARU] Modal Konfirmasi Nonaktifkan --}}
    <div id="deactivateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto">
            <div class="text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 id="deactivateModalTitle" class="mb-5 text-lg font-normal text-gray-800 dark:text-gray-300"></h3>
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="hideDeactivateModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <form id="deactivateForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Ya, Nonaktifkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Search, Modal, dan Server-side Sorting --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let typingTimer;
        const doneTypingInterval = 300;

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            if (searchInput.value.trim() === '') {
                // Jika search kosong, reload halaman untuk menampilkan data asli dengan sorting
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.delete('search');
                window.location.href = currentUrl.toString();
            } else {
                typingTimer = setTimeout(performSearch, doneTypingInterval);
            }
        });

        function performSearch() {
            const searchValue = searchInput.value;
            if (searchValue.trim() === '') return;
            
            // Redirect ke halaman dengan parameter search (dan pertahankan sorting)
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('search', searchValue);
            currentUrl.searchParams.set('page', '1'); // Reset ke halaman 1
            window.location.href = currentUrl.toString();
        }
    });

    // [BARU] Server-side Sorting Function
    function sortColumn(column) {
        const currentUrl = new URL(window.location);
        const currentSort = currentUrl.searchParams.get('sort');
        const currentDirection = currentUrl.searchParams.get('direction');
        
        let newDirection = 'asc';
        
        // Jika klik kolom yang sama, toggle direction
        if (currentSort === column) {
            newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
        }
        
        // Set parameter sorting
        currentUrl.searchParams.set('sort', column);
        currentUrl.searchParams.set('direction', newDirection);
        currentUrl.searchParams.set('page', '1'); // Reset ke halaman 1
        
        // Redirect dengan parameter sorting baru
        window.location.href = currentUrl.toString();
    }

    // [BARU] Script untuk Modal Nonaktifkan
    function showDeactivateModal(actionUrl, itemName) {
        const deactivateForm = document.getElementById('deactivateForm');
        const deactivateModalTitle = document.getElementById('deactivateModalTitle');
        deactivateForm.action = actionUrl;
        deactivateModalTitle.innerHTML = `Anda yakin ingin menonaktifkan <strong>${itemName}</strong>?`;
        document.getElementById('deactivateModal').classList.remove('hidden');
    }

    function hideDeactivateModal() {
        document.getElementById('deactivateModal').classList.add('hidden');
    }
    </script>
</x-app-layout>