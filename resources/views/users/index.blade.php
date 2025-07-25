<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700"> {{-- Shadow lebih dalam, padding lebih besar, border --}}
                {{-- Search dan Grup Tombol --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    
                    {{-- Search Input (Tetap sama) --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari pengguna..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Grup Tombol (Kedua tombol dibungkus dalam satu div) --}}
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
                        
                        {{-- Tombol Lihat Data Terhapus --}}
                        <a href="{{ route('users.trash') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2H7V5a2 2 0 012-2zm-2 6h8"></path>
                            </svg>
                            Lihat Data Terhapus
                        </a>

                    </div>
                </div>

                {{-- Alert Pesan --}}
                @include('partials.alert-messages')

                {{-- Tabel User --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700"> {{-- Wrapper untuk tabel agar ada scroll horizontal di mobile --}}
                    <table id="usersTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"> {{-- Tambah ID untuk search --}}
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
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"> {{-- Hover effect pada baris --}}
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="capitalize px-2 py-1 text-xs font-bold rounded
                                            {{ $user->role == 'admin_induk' ? 'bg-blue-200 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300' : 'bg-green-200 text-green-800 dark:bg-green-800/30 dark:text-green-300' }}"> {{-- Styling role badge --}}
                                            {{ str_replace('_', ' ', $user->role) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $user->cabang->nama_cabang ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">
                                            Edit
                                        </a>
                                        <button type="button" onclick="showDeleteModal({{ $user->id }})" class="text-red-500 hover:text-red-700 py-1 px-3 rounded">Hapus</button> {{-- Mengubah form menjadi button untuk memicu modal --}}
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow"> {{-- Tambah ID --}}
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Tidak ada data pengguna.
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;"> {{-- Tambah ID dan sembunyikan default --}}
                                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data pengguna yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6"> {{-- Margin atas lebih besar --}}
                    {{ $users->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-800 dark:text-gray-300">Apakah Anda yakin ingin menghapus data ini?</h3>
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="hideDeleteModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition duration-150 ease-in-out">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" action="" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Search --}}
    <script>
        // Fungsi Search Tabel
        function filterTable() {
            let input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("usersTable"); // Mengubah ID tabel
            tr = table.getElementsByTagName("tr");
            let noResultsRow = document.getElementById("noResultsRow"); // Ambil baris "Tidak ada data yang cocok."
            let initialEmptyRow = document.getElementById("initialEmptyRow"); // Ambil baris "Tidak ada data pengguna." (jika ada)
            let foundDataRows = 0; // Menghitung berapa banyak baris data yang terlihat

            // Loop melalui semua baris tabel, sembunyikan yang tidak cocok dengan kueri pencarian
            for (i = 0; i < tr.length; i++) {
                // Lewati baris header dan baris pesan kosong (jika ada)
                if (tr[i].getElementsByTagName("th").length > 0 || tr[i].id === "noResultsRow" || tr[i].id === "initialEmptyRow") {
                    continue;
                }
                
                // Cari di kolom Nama (indeks 0) dan Email (indeks 1)
                let tdName = tr[i].getElementsByTagName("td")[0];
                let tdEmail = tr[i].getElementsByTagName("td")[1];

                let rowMatchesFilter = false;
                if (tdName && (tdName.textContent || tdName.innerText).toUpperCase().indexOf(filter) > -1) {
                    rowMatchesFilter = true;
                }
                if (!rowMatchesFilter && tdEmail && (tdEmail.textContent || tdEmail.innerText).toUpperCase().indexOf(filter) > -1) {
                    rowMatchesFilter = true;
                }

                if (rowMatchesFilter) {
                    tr[i].style.display = "";
                    foundDataRows++; // Tambah hitungan jika baris cocok dan ditampilkan
                } else {
                    tr[i].style.display = "none";
                }
            }

            // Atur visibilitas pesan "Tidak ada data pengguna yang cocok."
            if (noResultsRow) {
                if (foundDataRows === 0 && filter !== "") { // Jika tidak ada baris data yang terlihat DAN filter tidak kosong
                    noResultsRow.style.display = ""; // Tampilkan pesan "Tidak ada data yang cocok."
                } else {
                    noResultsRow.style.display = "none"; // Sembunyikan pesan ini
                }
            }

            // Atur visibilitas pesan "Tidak ada data pengguna." (initialEmptyRow)
            if (initialEmptyRow) {
                if (foundDataRows === 0 && filter === "") { // Jika tidak ada baris data yang terlihat DAN filter kosong
                    initialEmptyRow.style.display = ""; // Tampilkan pesan "Tidak ada data pengguna."
                } else {
                    initialEmptyRow.style.display = "none"; // Sembunyikan pesan ini
                }
            }
        }

        // Fungsi untuk menampilkan modal konfirmasi hapus
        function showDeleteModal(id) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/users/${id}`; // Sesuaikan dengan rute destroy Anda
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan modal konfirmasi hapus
        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
