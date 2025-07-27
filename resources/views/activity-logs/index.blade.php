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
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider"">Pengguna</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider"">Deskripsi Aktivitas</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider"">Alamat IP</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            @forelse($logs as $log)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-2 px-4 whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                    <td class="py-2 px-4">{{ $log->user->name ?? 'Sistem' }}</td>
                                    <td class="py-2 px-4">{{ $log->description }}</td>
                                    <td class="py-2 px-4">{{ $log->ip_address }}</td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada aktivitas yang tercatat.</td>
                                </tr>
                            @endforelse
                            {{-- Row jika search tidak ketemu --}}
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada log yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script Search Table --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tbody = document.querySelector('#logTable tbody');
        let typingTimer;
        const doneTypingInterval = 300;

        // Make sure we have the empty state rows
        if (!document.getElementById('noResultsRow')) {
            const noResultsRow = document.createElement('tr');
            noResultsRow.id = 'noResultsRow';
            noResultsRow.innerHTML = `
                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                    Tidak ada log yang cocok.
                </td>
            `;
            noResultsRow.style.display = 'none';
            tbody.appendChild(noResultsRow);
        }

        if (!document.getElementById('initialEmptyRow')) {
            const initialEmptyRow = document.createElement('tr');
            initialEmptyRow.id = 'initialEmptyRow';
            initialEmptyRow.innerHTML = `
                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                    Tidak ada aktivitas yang tercatat.
                </td>
            `;
            tbody.appendChild(initialEmptyRow);
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        function performSearch() {
            const searchValue = searchInput.value;
            const noResultsRow = document.getElementById('noResultsRow');
            const initialEmptyRow = document.getElementById('initialEmptyRow');

            // Show loading state
            tbody.classList.add('opacity-50');

            fetch(`/activity-logs/search?search=${encodeURIComponent(searchValue)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Remove loading state
                    tbody.classList.remove('opacity-50');

                    // Clear existing rows except our special rows
                    const rows = tbody.querySelectorAll('tr:not(#noResultsRow):not(#initialEmptyRow)');
                    rows.forEach(row => row.remove());

                    if (Array.isArray(data) && data.length > 0) {
                        // Hide both special rows
                        if (noResultsRow) noResultsRow.style.display = 'none';
                        if (initialEmptyRow) initialEmptyRow.style.display = 'none';
                        
                        data.forEach(log => {
                            const row = document.createElement('tr');
                            row.className = 'border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition';
                            
                            const date = new Date(log.created_at).toLocaleString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            });

                            row.innerHTML = `
                                <td class="py-2 px-4 whitespace-nowrap">${date}</td>
                                <td class="py-2 px-4">${log.user ? log.user.name : 'Sistem'}</td>
                                <td class="py-2 px-4">${log.description}</td>
                                <td class="py-2 px-4">${log.ip_address}</td>
                            `;
                            tbody.appendChild(row);
                        });
                    } else {
                        // Show appropriate empty state
                        if (searchValue && noResultsRow) {
                            noResultsRow.style.display = '';
                            if (initialEmptyRow) initialEmptyRow.style.display = 'none';
                        } else if (initialEmptyRow) {
                            if (noResultsRow) noResultsRow.style.display = 'none';
                            initialEmptyRow.style.display = '';
                        }
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    tbody.classList.remove('opacity-50');
                    // Show error message to user
                    const errorRow = document.createElement('tr');
                    errorRow.innerHTML = `
                        <td colspan="4" class="text-center py-4 text-red-500">
                            Terjadi kesalahan saat mencari data. Silakan coba lagi.
                        </td>
                    `;
                    tbody.appendChild(errorRow);
                });
        }
    });
    </script>
</x-app-layout>
