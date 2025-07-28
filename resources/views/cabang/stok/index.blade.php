<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Stok Sparepart Gudang Anda') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Gudang: {{ auth()->user()->cabang->nama_cabang }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar semua sparepart yang tersedia di gudang Anda.</p>
                    </div>
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" placeholder="Cari sparepart..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>
                </div>

                {{-- Alert jika ada --}}
                @include('partials.alert-messages')

                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table id="stokTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kode Part</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Jual</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Jumlah Stok</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition data-row">
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $sparepart->kode_part }}</td>
                                    <td class="py-3 px-4">{{ $sparepart->nama_part }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ 'Rp ' . number_format($sparepart->harga_jual, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center">{{ $sparepart->pivot->stok }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($sparepart->pivot->stok <= 0)
                                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Habis
                                            </span>
                                        @elseif($sparepart->pivot->stok <= 5)
                                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Hampir Habis
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Tersedia
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Belum ada stok barang di gudang Anda.
                                    </td>
                                </tr>
                            @endforelse
                             <tr id="noResultsRow" style="display: none;">
                                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada sparepart yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6 pagination-container">
                    {{ $spareparts->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Search --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tbody = document.querySelector('#stokTable tbody');
        const paginationContainer = document.querySelector('.pagination-container');
        let typingTimer;
        const doneTypingInterval = 300;

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        function performSearch() {
            const searchValue = searchInput.value;
            const noResultsRow = document.getElementById('noResultsRow');
            const initialEmptyRow = document.getElementById('initialEmptyRow');

            // [UBAH] Ganti route() dengan window.location.reload()
            if (searchValue.trim() === '') {
                window.location.reload();
                return;
            }

            if (paginationContainer) {
                paginationContainer.style.display = 'none';
            }
            
            tbody.classList.add('opacity-50');

            fetch(`/stok-cabang/search?search=${encodeURIComponent(searchValue)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    tbody.classList.remove('opacity-50');

                    const dataRows = tbody.querySelectorAll('tr.data-row');
                    dataRows.forEach(row => row.remove());
                    
                    if(initialEmptyRow) initialEmptyRow.style.display = 'none';

                    if (Array.isArray(data) && data.length > 0) {
                        if (noResultsRow) noResultsRow.style.display = 'none';

                        data.forEach(sparepart => {
                            const row = document.createElement('tr');
                            row.className = 'border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition data-row';

                            let statusBadge = '';
                            if (sparepart.pivot.stok <= 0) {
                                statusBadge = '<span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Habis</span>';
                            } else if (sparepart.pivot.stok <= 5) {
                                statusBadge = '<span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Hampir Habis</span>';
                            } else {
                                statusBadge = '<span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Tersedia</span>';
                            }

                            row.innerHTML = `
                                <td class="py-3 px-4 whitespace-nowrap">${sparepart.kode_part}</td>
                                <td class="py-3 px-4">${sparepart.nama_part}</td>
                                <td class="py-3 px-4 whitespace-nowrap">Rp ${Number(sparepart.harga_jual).toLocaleString('id-ID')}</td>
                                <td class="py-3 px-4 text-center">${sparepart.pivot.stok}</td>
                                <td class="py-3 px-4 text-center">${statusBadge}</td>
                            `;
                            tbody.insertBefore(row, noResultsRow);
                        });
                    } else {
                        if (noResultsRow) noResultsRow.style.display = '';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    tbody.classList.remove('opacity-50');
                    const dataRows = tbody.querySelectorAll('tr.data-row');
                    dataRows.forEach(row => row.remove());
                    noResultsRow.innerHTML = `<td colspan="5" class="text-center py-4 text-red-500">Terjadi kesalahan saat mencari data.</td>`;
                    noResultsRow.style.display = '';
                });
        }
    });
    </script>

    {{-- Gaya Kustom untuk Paginasi --}}
    <style>
        .pagination-container span[aria-current="page"] span {
            background-color: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
            font-weight: 600;
        }
        .dark .pagination-container span[aria-current="page"] span {
            background-color: #60a5fa !important;
            color: #1f2937 !important;
            border-color: #60a5fa !important;
        }
        .pagination-container a:hover {
            background-color: #f3f4f6;
        }
        .dark .pagination-container a:hover {
            background-color: #374151;
        }
    </style>
</x-app-layout>
