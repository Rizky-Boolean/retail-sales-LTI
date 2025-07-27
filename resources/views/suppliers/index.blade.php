<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Supplier') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search dan Tombol Tambah --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" placeholder="Cari supplier..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Grup Tombol Aksi --}}
                    <div class="flex items-center gap-3">
                        {{-- Tombol Tambah Supplier --}}
                        <a href="{{ route('suppliers.create') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Supplier
                        </a>
                        {{-- Tombol Lihat Supplier Tidak Aktif --}}
                        <a href="{{ route('suppliers.inactive') }}"
                           class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639l4.316-4.316a1.012 1.012 0 0 1 1.415 0l4.316 4.316a1.012 1.012 0 0 1 0 .639l-4.316 4.316a1.012 1.012 0 0 1-1.415 0l-4.316-4.316ZM12.322 2.036a1.012 1.012 0 0 1 .639 0l4.316 4.316a1.012 1.012 0 0 1 0 1.415l-4.316 4.316a1.012 1.012 0 0 1-.639 0l-4.316-4.316a1.012 1.012 0 0 1 0-1.415l4.316-4.316Z" /></svg>
                            Lihat Data Nonaktif
                        </a>
                    </div>
                </div>

                {{-- Alert Message --}}
                @include('partials.alert-messages')

                {{-- Tabel Data --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="suppliersTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Supplier</th>
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Alamat</th>
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kontak</th>
                                <th class="text-center py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($suppliers as $supplier)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $supplier->nama_supplier }}</td>
                                    <td class="py-3 px-4">{{ $supplier->alamat }}</td>
                                    <td class="py-3 px-4">{{ $supplier->kontak }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="text-blue-600 ...">Edit</a>
                                        <form action="{{ route('suppliers.toggleStatus', $supplier) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 ..." onclick="return confirm('Anda yakin ingin menonaktifkan data ini?')">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data supplier.</td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data supplier yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $suppliers->links() }}
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
                const tbody = document.querySelector('#suppliersTable tbody');
                const noResultsRow = document.getElementById('noResultsRow');

                fetch(`/suppliers/search?search=${encodeURIComponent(searchValue)}`)
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
                            data.forEach(supplier => {
                                const row = document.createElement('tr');
                                row.className = 'border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition';
                                row.innerHTML = `
                                    <td class="py-3 px-4">${supplier.nama_supplier}</td>
                                    <td class="py-3 px-4">${supplier.alamat || ''}</td>
                                    <td class="py-3 px-4">${supplier.kontak || ''}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="/suppliers/${supplier.id}/edit" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">Edit</a>
                                            <button type="button" onclick="showDeleteModal(${supplier.id})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 py-1 px-2 rounded">Hapus</button>
                                        </div>
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