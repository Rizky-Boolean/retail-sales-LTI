<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Log Aktivitas Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Advanced Search Section --}}
                <div class="mb-6 space-y-4">
                    {{-- Main Search Bar --}}
                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div class="w-full md:w-1/2">
                            <input type="text" id="searchInput" placeholder="Cari berdasarkan pengguna, aktivitas, IP, atau tanggal (25/12/2025)..."
                                   value="{{ request('search') }}"
                                   class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                        </div>
                        
                        {{-- Quick Date Filters --}}
                        <div class="flex gap-2 flex-wrap">
                            <button onclick="quickSearch('today')" class="px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                                Hari Ini
                            </button>
                            <button onclick="quickSearch('yesterday')" class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                Kemarin
                            </button>
                            <button onclick="quickSearch('this_week')" class="px-3 py-2 text-sm bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition">
                                Minggu Ini
                            </button>
                        </div>
                    </div>

                    {{-- Search Tips --}}
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong>Tips pencarian:</strong> Cari berdasarkan nama pengguna, deskripsi aktivitas, alamat IP, atau tanggal (format: 25/12/2025, 2025, januari, dll.)
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="logTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150"
                                    onclick="sortColumn('created_at')">
                                    <div class="flex items-center justify-between">
                                        <span>Waktu</span>
                                        <div class="sort-icons flex flex-col ml-2">
                                            <svg class="sort-asc w-3 h-3 {{ request('sort') == 'created_at' && request('direction') == 'asc' ? 'text-blue-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                            <svg class="sort-desc w-3 h-3 {{ request('sort') == 'created_at' && request('direction') == 'desc' ? 'text-blue-500' : 'text-gray-400' }} rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150"
                                    onclick="sortColumn('user_name')">
                                    <div class="flex items-center justify-between">
                                        <span>Pengguna</span>
                                        <div class="sort-icons flex flex-col ml-2">
                                            <svg class="sort-asc w-3 h-3 {{ request('sort') == 'user_name' && request('direction') == 'asc' ? 'text-blue-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                            <svg class="sort-desc w-3 h-3 {{ request('sort') == 'user_name' && request('direction') == 'desc' ? 'text-blue-500' : 'text-gray-400' }} rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150"
                                    onclick="sortColumn('description')">
                                    <div class="flex items-center justify-between">
                                        <span>Deskripsi Aktivitas</span>
                                        <div class="sort-icons flex flex-col ml-2">
                                            <svg class="sort-asc w-3 h-3 {{ request('sort') == 'description' && request('direction') == 'asc' ? 'text-blue-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                            <svg class="sort-desc w-3 h-3 {{ request('sort') == 'description' && request('direction') == 'desc' ? 'text-blue-500' : 'text-gray-400' }} rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider cursor-pointer hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-150"
                                    onclick="sortColumn('ip_address')">
                                    <div class="flex items-center justify-between">
                                        <span>Alamat IP</span>
                                        <div class="sort-icons flex flex-col ml-2">
                                            <svg class="sort-asc w-3 h-3 {{ request('sort') == 'ip_address' && request('direction') == 'asc' ? 'text-blue-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                            <svg class="sort-desc w-3 h-3 {{ request('sort') == 'ip_address' && request('direction') == 'desc' ? 'text-blue-500' : 'text-gray-400' }} rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        @forelse($logs as $log)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition data-row">
                                <td class="py-2 px-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $log->created_at->format('d M Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="py-2 px-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center text-sm font-medium mr-3">
                                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span>{{ $log->user->name ?? 'Sistem' }}</span>
                                    </div>
                                </td>
                                <td class="py-2 px-4">
                                    <p class="text-sm">{{ $log->description }}</p>
                                </td>
                                <td class="py-2 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ $log->ip_address }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                              @if(request('search'))
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Tidak ada log yang cocok untuk pencarian: "<strong>{{ request('search') }}</strong>"
                                </td>
                            @else
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Tidak ada aktivitas yang tercatat.
                                </td>
                            @endif
                        </tr>
                    @endforelse
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

    {{-- Ganti seluruh script Anda dengan ini --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let typingTimer;
        const doneTypingInterval = 700;

         // Otomatis fokus dan atur posisi kursor
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            searchInput.focus();
            // [DIUBAH] Pindahkan kursor ke akhir teks, bukan menyeleksi semuanya
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }

        // Fungsi utama untuk menjalankan filter/pencarian
        function runSearch() {
            const currentUrl = new URL(window.location);
            const searchValue = searchInput.value.trim();

            if (searchValue) {
                currentUrl.searchParams.set('search', searchValue);
            } else {
                // Hapus parameter search jika input kosong
                currentUrl.searchParams.delete('search');
            }
            currentUrl.searchParams.set('page', '1'); // Selalu reset ke halaman 1 saat pencarian baru
            
            // Hanya redirect jika URL benar-benar berubah
            if (window.location.href !== currentUrl.href) {
                window.location.href = currentUrl.toString();
            }
        }

        // Listener untuk input, dengan jeda (debounce)
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(runSearch, doneTypingInterval);
        });

        // Listener untuk tombol Enter, tanpa jeda
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                clearTimeout(typingTimer); // Batalkan timer jika ada
                runSearch();
            }
        });

        // Membuat fungsi global agar bisa dipanggil dari HTML (onclick)
        window.quickSearch = function(period) {
            let searchTerm = '';
            if (period === 'today') searchTerm = 'hari ini';
            if (period === 'yesterday') searchTerm = 'kemarin';
            if (period === 'this_week') searchTerm = 'minggu ini';
            
            if (searchTerm) {
                searchInput.value = searchTerm;
                runSearch();
            }
        }

        window.sortColumn = function(column) {
            const currentUrl = new URL(window.location);
            const currentSort = currentUrl.searchParams.get('sort');
            const currentDirection = currentUrl.searchParams.get('direction');
            
            let newDirection = 'asc';
            if (currentSort === column && currentDirection === 'asc') {
                newDirection = 'desc';
            }
            
            currentUrl.searchParams.set('sort', column);
            currentUrl.searchParams.set('direction', newDirection);
            currentUrl.searchParams.set('page', '1');
            
            window.location.href = currentUrl.toString();
        }
    });
    </script>

    {{-- Custom Styles untuk Pagination dan UI Enhancement --}}
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

        /* Animation untuk sort icons */
        .sort-icons svg {
            transition: color 0.2s ease;
        }

        /* Highlight search terms - could be enhanced with JS */
        .search-highlight {
            background-color: #fef3c7;
            padding: 1px 2px;
            border-radius: 2px;
        }

        .dark .search-highlight {
            background-color: #451a03;
            color: #fbbf24;
        }
    </style>
</x-app-layout>