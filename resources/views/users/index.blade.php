    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Pengguna') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">
                    {{-- Search dan Grup Tombol --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

                        {{-- Search Input --}}
                        <div class="w-full md:w-1/3">
                            <input type="text" id="searchInput" placeholder="Cari pengguna..."
                                class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                        </div>

                        {{-- Grup Tombol --}}
                        <div class="flex items-center gap-3">
                            
                            {{-- Tombol Tambah Pengguna --}}
                            <a href="{{ route('users.create') }}"
                                class="inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Pengguna
                            </a>
                            {{-- Tombol Lihat Pengguna Nonaktif --}}
                            <a href="{{ route('users.inactive') }}"
                            class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all duration-150 ease-in-out">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639l4.316-4.316a1.012 1.012 0 0 1 1.415 0l4.316 4.316a1.012 1.012 0 0 1 0 .639l-4.316 4.316a1.012 1.012 0 0 1-1.415 0l-4.316-4.316ZM12.322 2.036a1.012 1.012 0 0 1 .639 0l4.316 4.316a1.012 1.012 0 0 1 0 1.415l-4.316 4.316a1.012 1.012 0 0 1-.639 0l-4.316-4.316a1.012 1.012 0 0 1 0-1.415l4.316-4.316Z" /></svg>
                                Lihat Data Nonaktif
                            </a>
                        </div>
                    </div>

                    {{-- Alert Pesan --}}
                    @include('partials.alert-messages')

                    {{-- Tabel User --}}
                    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <table id="usersTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Email</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-widerr">Role</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Cabang</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                                @forelse($users as $user)
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap">
                                            <span class="capitalize px-2 py-1 text-xs font-bold rounded
                                                {{ $user->role == 'admin_induk' ? 'bg-blue-200 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300' : 'bg-green-200 text-green-800 dark:bg-green-800/30 dark:text-green-300' }}">
                                                {{ str_replace('_', ' ', $user->role) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 whitespace-nowrap">{{ $user->cabang->nama_cabang ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-center whitespace-nowrap">
                                            {{-- [UBAH] Tombol Aksi diperbarui --}}
                                        <div class="flex justify-center items-center gap-4">
                                                <a href="{{ route('users.edit', $user) }}" class="flex items-center gap-1 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                                    <i data-lucide="edit" class="w-4 h-4"></i><span>Edit</span>
                                                </a>
                                                <button type="button" onclick="showDeactivateModal('{{ route('users.toggleStatus', $user) }}', '{{ addslashes($user->name) }}')" class="flex items-center gap-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Nonaktifkan">
                                                    <i data-lucide="power-off" class="w-4 h-4"></i><span>Nonaktifkan</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    @if(request('search'))
                                        <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            Tidak ada pengguna yang cocok untuk pencarian: "<strong>{{ request('search') }}</strong>"
                                        </td>
                                    @else
                                        <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            Tidak ada data pengguna aktif.
                                        </td>
                                    @endif
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Nonaktifkan --}}
    <div id="deactivateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto">
            <div class="text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{-- [UBAH] Menggunakan ID yang benar --}}
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

  {{-- Script untuk Search dan Modal --}}
    <script>
        // [DIUBAH] Logika Pencarian Server-side
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            let typingTimer;
            const doneTypingInterval = 500;

            const urlParams = new URLSearchParams(window.location.search);
            const currentSearch = urlParams.get('search');
            if (currentSearch) {
                searchInput.value = currentSearch;
                searchInput.focus();
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

        // [TIDAK DIUBAH] Fungsi untuk Modal
        const deactivateModal = document.getElementById('deactivateModal');
        const deactivateForm = document.getElementById('deactivateForm');
        const deactivateModalTitle = document.getElementById('deactivateModalTitle');

        function showDeactivateModal(actionUrl, userName) {
            deactivateForm.action = actionUrl;
            deactivateModalTitle.innerHTML = `Anda yakin ingin menonaktifkan "<strong>${userName}</strong>"?`;
            deactivateModal.classList.remove('hidden');
        }

        function hideDeactivateModal() {
            deactivateModal.classList.add('hidden');
        }
    </script>
</x-app-layout>
