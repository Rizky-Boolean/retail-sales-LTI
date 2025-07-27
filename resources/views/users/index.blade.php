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

                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari pengguna..."
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
                                        <a href="{{ route('users.edit', $user) }}" class="text-blue-600 ...">Edit</a>
                                        <form action="{{ route('users.toggleStatus', $user) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 ..." onclick="return confirm('Anda yakin ingin menonaktifkan data ini?')">
                                                Nonaktifkan
                                            </button>
                                        </form>
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
    </script>
</x-app-layout>
