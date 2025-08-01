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
                        <a href="{{ route('suppliers.create') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Supplier
                        </a>
                        <a href="{{ route('suppliers.inactive') }}"
                           class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639l4.316-4.316a1.012 1.012 0 0 1 1.415 0l4.316 4.316a1.012 1.012 0 0 1 0 .639l-4.316 4.316a1.012 1.012 0 0 1-1.415 0l-4.316-4.316ZM12.322 2.036a1.012 1.012 0 0 1 .639 0l4.316 4.316a1.012 1.012 0 0 1 0 1.415l-4.316 4.316a1.012 1.012 0 0 1-.639 0l-4.316-4.316a1.012 1.012 0 0 1 0-1.415l4.316-4.316Z" /></svg>
                            Lihat Data Nonaktif
                        </a>
                    </div>
                </div>

                @include('partials.alert-messages')

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
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition data-row">
                                <td class="py-3 px-4">{{ $supplier->nama_supplier }}</td>
                                <td class="py-3 px-4">{{ $supplier->alamat }}</td>
                                <td class="py-3 px-4">{{ $supplier->kontak }}</td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center items-center gap-4">
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="flex items-center gap-1 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i><span>Edit</span>
                                        </a>
                                        <button type="button" onclick="showDeactivateModal('{{ route('suppliers.toggleStatus', $supplier) }}', '{{ addslashes($supplier->nama_supplier) }}')" class="flex items-center gap-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Nonaktifkan">
                                            <i data-lucide="power-off" class="w-4 h-4"></i><span>Nonaktifkan</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- Pesan dinamis berdasarkan pencarian --}}
                                @if(request('search'))
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Tidak ada supplier yang cocok untuk pencarian: "<strong>{{ request('search') }}</strong>"
                                    </td>
                                @else
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Tidak ada data supplier aktif.
                                    </td>
                                @endif
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $suppliers->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Nonaktifkan --}}
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

    {{-- Script untuk Search, Modal, dan Server-side Logic --}}
    <script>
        // Logika untuk Search (Sudah Benar)
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            let typingTimer;
            const doneTypingInterval = 500;

            const urlParams = new URLSearchParams(window.location.search);
            const currentSearch = urlParams.get('search');
            if (currentSearch) {
                searchInput.value = currentSearch;
                searchInput.focus();
                
                // Trik untuk memindahkan kursor ke akhir
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    const searchValue = searchInput.value.trim();
                    const currentUrl = new URL(window.location);

                    if (searchValue) {
                        currentUrl.searchParams.set('search', searchValue);
                    } else {
                        currentUrl.searchParams.delete('search');
                    }
                    
                    currentUrl.searchParams.set('page', '1');

                    if (window.location.href !== currentUrl.href) {
                        window.location.href = currentUrl.toString();
                    }
                }, doneTypingInterval);
            });
        });

        // [TAMBAHKAN INI] Logika untuk Modal Nonaktifkan
        function showDeactivateModal(actionUrl, itemName) {
            const deactivateForm = document.getElementById('deactivateForm');
            const deactivateModalTitle = document.getElementById('deactivateModalTitle');
            
            deactivateForm.action = actionUrl;
            deactivateModalTitle.innerHTML = `Anda yakin ingin menonaktifkan supplier <strong>${itemName}</strong>?`;
            document.getElementById('deactivateModal').classList.remove('hidden');
        }

        function hideDeactivateModal() {
            document.getElementById('deactivateModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
