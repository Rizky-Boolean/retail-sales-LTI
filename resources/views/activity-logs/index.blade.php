<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Log Aktivitas Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search Bar --}}
                <div class="mb-6 w-full md:w-1/3">
                    <input type="text" id="searchInput" placeholder="Cari log aktivitas..."
                           class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                </div>

                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="logTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Waktu</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Pengguna</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Deskripsi Aktivitas</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Alamat IP</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            {{-- [UBAH] Menggunakan @if dan @foreach untuk kontrol yang lebih baik --}}
                            @if($logs->isNotEmpty())
                                @foreach($logs as $log)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition data-row">
                                        <td class="py-2 px-4 whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                        <td class="py-2 px-4">{{ $log->user->name ?? 'Sistem' }}</td>
                                        <td class="py-2 px-4">{{ $log->description }}</td>
                                        <td class="py-2 px-4">{{ $log->ip_address }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Pesan ini hanya akan ditampilkan oleh Blade jika tidak ada data sama sekali --}}
                            <tr id="initialEmptyRow" @if($logs->isNotEmpty()) style="display: none;" @endif>
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada aktivitas yang tercatat.</td>
                            </tr>
                            
                            {{-- Pesan ini dikontrol oleh JavaScript untuk hasil pencarian --}}
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada log yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6 pagination-container">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script Search Table (Logika JS tetap sama karena sudah benar) --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tbody = document.querySelector('#logTable tbody');
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

            if (searchValue.trim() === '') {
                window.location.href = '{{ route("activity-logs.index") }}';
                return;
            }

            if (paginationContainer) {
                paginationContainer.style.display = 'none';
            }

            tbody.classList.add('opacity-50');

            fetch(`/activity-logs/search?search=${encodeURIComponent(searchValue)}`)
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
                        noResultsRow.style.display = 'none';
                        
                        data.forEach(log => {
                            const row = document.createElement('tr');
                            row.className = 'border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition data-row';
                            
                            const date = new Date(log.created_at).toLocaleString('id-ID', {
                                day: 'numeric', month: 'short', year: 'numeric',
                                hour: '2-digit', minute: '2-digit', second: '2-digit'
                            }).replace(/\./g, ':');

                            row.innerHTML = `
                                <td class="py-2 px-4 whitespace-nowrap">${date}</td>
                                <td class="py-2 px-4">${log.user ? log.user.name : 'Sistem'}</td>
                                <td class="py-2 px-4">${log.description}</td>
                                <td class="py-2 px-4">${log.ip_address}</td>
                            `;
                            tbody.insertBefore(row, noResultsRow);
                        });
                    } else {
                        noResultsRow.style.display = '';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    tbody.classList.remove('opacity-50');
                    const dataRows = tbody.querySelectorAll('tr.data-row');
                    dataRows.forEach(row => row.remove());
                    noResultsRow.innerHTML = `<td colspan="4" class="text-center py-4 text-red-500">Terjadi kesalahan saat mencari.</td>`;
                    noResultsRow.style.display = '';
                });
        }
    });
    </script>

    {{-- [TAMBAH] Gaya Kustom untuk Paginasi --}}
    <style>
        /* Gaya untuk link halaman aktif */
        .pagination-container span[aria-current="page"] span {
            background-color: #3b82f6 !important; /* blue-500 */
            color: white !important;
            border-color: #3b82f6 !important;
            font-weight: 600;
        }

        /* Gaya untuk link halaman aktif dalam mode gelap */
        .dark .pagination-container span[aria-current="page"] span {
            background-color: #60a5fa !important; /* blue-400 */
            color: #1f2937 !important; /* gray-800 */
            border-color: #60a5fa !important;
        }

        /* Efek hover untuk link lain */
        .pagination-container a:hover {
            background-color: #f3f4f6; /* gray-100 */
        }

        /* Efek hover untuk link lain dalam mode gelap */
        .dark .pagination-container a:hover {
            background-color: #374151; /* gray-700 */
        }
    </style>
</x-app-layout>
