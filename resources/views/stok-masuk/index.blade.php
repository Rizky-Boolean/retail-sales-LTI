<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Stok Masuk') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search dan Tombol Tambah --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" placeholder="Cari histori stok masuk..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Tombol Tambah --}}
                    <div class="flex flex-col md:flex-row flex-wrap gap-3 md:gap-4">
                        <a href="{{ route('stok-masuk.create') }}" class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Catat Stok Masuk Baru
                        </a>
                    </div>
                </div>

                {{-- Alert Message --}}
                @include('partials.alert-messages')

                {{-- Tabel --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="stokMasukTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">#ID</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Tanggal</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Supplier</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Total Qty</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Total Final</th>
                                <th class="text-left py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($stokMasuks as $stokMasuk)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">TR-{{ $stokMasuk->id }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->format('d M Y') }}</td>
                                    <td class="py-3 px-4">{{ $stokMasuk->supplier->nama_supplier ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 ">{{ $stokMasuk->details_sum_qty }}</td>
                                    <td class="py-3 px-4 ">{{ 'Rp ' . number_format($stokMasuk->total_final, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center ">
                                        <a href="{{ route('stok-masuk.show', $stokMasuk->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Belum ada data stok masuk.</td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data stok masuk yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $stokMasuks->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Script Filter --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let typingTimer;
        const doneTypingInterval = 300;

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        function performSearch() {
            const searchValue = searchInput.value;
            const tbody = document.querySelector('#stokMasukTable tbody');
            const noResultsRow = document.getElementById('noResultsRow');

            fetch(`/stok-masuk/search?search=${encodeURIComponent(searchValue)}`)
                .then(response => response.json())
                .then(data => {
                    // Clear existing rows except the noResultsRow
                    Array.from(tbody.children).forEach(child => {
                        if (!child.id || (child.id !== 'noResultsRow' && child.id !== 'initialEmptyRow')) {
                            child.remove();
                        }
                    });

                    if (data.length > 0) {
                        noResultsRow.style.display = 'none';
                        data.forEach(stokMasuk => {
                            const row = document.createElement('tr');
                            row.className = 'border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition';
                            
                            // Format date using JavaScript
                            const date = new Date(stokMasuk.tanggal_masuk);
                            const formattedDate = date.toLocaleDateString('id-ID', { 
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            });

                            row.innerHTML = `
                                <td class="py-3 px-4">TR-${stokMasuk.id}</td>
                                <td class="py-3 px-4">${formattedDate}</td>
                                <td class="py-3 px-4">${stokMasuk.supplier ? stokMasuk.supplier.nama_supplier : 'N/A'}</td>
                                <td class="py-3 px-4">${stokMasuk.details_sum_qty}</td>
                                <td class="py-3 px-4">Rp ${Number(stokMasuk.total_final).toLocaleString('id-ID')}</td>
                                <td class="py-3 px-4 text-center">
                                    <a href="/stok-masuk/${stokMasuk.id}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">Detail</a>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    } else {
                        noResultsRow.style.display = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
    </script>
</x-app-layout>
