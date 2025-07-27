<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Header Section: Search and Add Button --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" placeholder="Cari No. Nota / Pembeli..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Add Button --}}
                    <a href="{{ route('penjualan.create') }}" class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Transaksi Baru
                    </a>
                </div>

                @include('partials.alert-messages')

                {{-- Sales History Table --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="penjualanTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">No. Nota</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Tanggal</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Pembeli</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Total</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($penjualans as $penjualan)
                                <tr class="data-row border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $penjualan->nomor_nota }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d M Y') }}</td>
                                    <td class="py-3 px-4">{{ $penjualan->nama_pembeli }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center items-center gap-4">
                                            <a href="{{ route('penjualan.show', $penjualan->id) }}" class="flex items-center gap-1 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Nota">
                                                <span>Nota</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada transaksi penjualan.</td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data penjualan yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $penjualans->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tbody = document.querySelector('#penjualanTable tbody');
        let typingTimer;
        const doneTypingInterval = 300;

        // Remove the existing onkeyup attribute
        searchInput.removeAttribute('onkeyup');
        
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        function performSearch() {
            const searchValue = searchInput.value;
            const noResultsRow = document.getElementById('noResultsRow');
            
            // Show loading state
            tbody.classList.add('opacity-50');

            fetch(`/penjualan/search?search=${encodeURIComponent(searchValue)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Remove loading state
                    tbody.classList.remove('opacity-50');

                    // Clear existing rows except special rows
                    const rows = tbody.querySelectorAll('tr:not(#noResultsRow):not(#initialEmptyRow)');
                    rows.forEach(row => row.remove());

                    if (data.length > 0) {
                        if (noResultsRow) noResultsRow.style.display = 'none';
                        
                        data.forEach(penjualan => {
                            const row = document.createElement('tr');
                            row.className = 'data-row border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition';
                            
                            // Format date
                            const date = new Date(penjualan.tanggal_penjualan).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            });

                            row.innerHTML = `
                                <td class="py-3 px-4 whitespace-nowrap">${penjualan.nomor_nota}</td>
                                <td class="py-3 px-4 whitespace-nowrap">${date}</td>
                                <td class="py-3 px-4">${penjualan.nama_pembeli}</td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">Rp ${Number(penjualan.total_final).toLocaleString('id-ID')}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center items-center gap-4">
                                        <a href="/penjualan/${penjualan.id}" class="flex items-center gap-1 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Nota">
                                            <span>Nota</span>
                                        </a>
                                    </div>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    } else {
                        if (noResultsRow) noResultsRow.style.display = '';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    tbody.classList.remove('opacity-50');
                    const errorRow = document.createElement('tr');
                    errorRow.innerHTML = `
                        <td colspan="5" class="text-center py-4 text-red-500">
                            Terjadi kesalahan saat mencari data. Silakan coba lagi.
                        </td>
                    `;
                    tbody.appendChild(errorRow);
                });
        }
    });
    </script>
</x-app-layout>
