<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Sparepart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700"> {{-- Shadow lebih dalam, padding lebih besar, border --}}

                {{-- Search dan Tombol Aksi --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari sparepart..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>
                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col md:flex-row flex-wrap gap-3 md:gap-4">
                        {{-- Tambah Sparepart --}}
                        <a href="{{ route('spareparts.create') }}"
                            class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Sparepart
                        </a>

                        {{-- Import Data --}}
                        <a href="{{ route('spareparts.import.show') }}"
                            class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Import Data
                        </a>

                        {{-- Lihat Data Terhapus --}}
                        <a href="{{ route('spareparts.trash') }}"
                            class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2H7V5a2 2 0 012-2zm-2 6h8"></path>
                            </svg>
                            Lihat Data Terhapus
                        </a>
                    </div>
                </div>

                {{-- Menampilkan pesan alert --}}
                @include('partials.alert-messages')
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="sparepartsTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kode Part</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Satuan</th>
                                <th class="text-right py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Jual</th>
                                <th class="text-center py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 data-row">
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->kode_part }}</td>
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->nama_part }}</td>
                                    <td class="text-left py-3 px-4 whitespace-nowrap">{{ $sparepart->satuan }}</td>
                                    <td class="text-right py-3 px-4 whitespace-nowrap">{{ 'Rp ' . number_format($sparepart->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-center py-3 px-4 whitespace-nowrap">
                                        <a href="{{ route('spareparts.edit', $sparepart->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">Edit</a>
                                        <button type="button" onclick="showDeleteModal({{ $sparepart->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 py-1 px-2 rounded">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                {{-- Baris ini akan ditampilkan jika tidak ada data sparepart sama sekali dari backend --}}
                                <tr id="initialEmptyRow">
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data sparepart.</td>
                                </tr>
                            @endforelse
                            {{-- Baris ini akan ditampilkan jika pencarian tidak menemukan hasil --}}
                            <tr id="noResultsRow" class="hidden"> {{-- Selalu sembunyikan secara default dengan class 'hidden' --}}
                                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data sparepart yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6"> {{-- Margin atas lebih besar --}}
                    {{ $spareparts->links() }}
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
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
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
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("sparepartsTable");
            let dataRows = table.querySelectorAll('tbody .data-row'); // Hanya ambil baris data
            let noResultsRow = document.getElementById("noResultsRow");
            let initialEmptyRow = document.getElementById("initialEmptyRow");
            let foundDataRows = 0; // Menghitung berapa banyak baris data yang terlihat

            // Sembunyikan semua baris data dan baris pesan kosong terlebih dahulu
            dataRows.forEach(row => {
                row.style.display = "none";
            });
            noResultsRow.classList.add('hidden'); // Gunakan classList.add/remove
            if (initialEmptyRow) {
                initialEmptyRow.classList.add('hidden');
            }

            // Jika filter kosong, tampilkan semua baris data (atau initialEmptyRow jika tidak ada data)
            if (filter === "") {
                if (dataRows.length > 0) {
                    dataRows.forEach(row => {
                        row.style.display = "";
                        foundDataRows++;
                    });
                } else if (initialEmptyRow) {
                    initialEmptyRow.classList.remove('hidden'); // Tampilkan pesan "Tidak ada data sparepart."
                }
            } else {
                // Jika filter tidak kosong, filter baris data
                dataRows.forEach(row => {
                    let tdKodePart = row.getElementsByTagName("td")[0]; // Kolom Kode Part
                    let tdNamaPart = row.getElementsByTagName("td")[1]; // Kolom Nama Part

                    let rowMatchesFilter = false;
                    if (tdKodePart && (tdKodePart.textContent || tdKodePart.innerText).toUpperCase().indexOf(filter) > -1) {
                        rowMatchesFilter = true;
                    }
                    if (!rowMatchesFilter && tdNamaPart && (tdNamaPart.textContent || tdNamaPart.innerText).toUpperCase().indexOf(filter) > -1) {
                        rowMatchesFilter = true;
                    }

                    if (rowMatchesFilter) {
                        row.style.display = "";
                        foundDataRows++;
                    } else {
                        row.style.display = "none";
                    }
                });

                // Setelah filtering, jika tidak ada baris data yang terlihat, tampilkan noResultsRow
                if (foundDataRows === 0) {
                    noResultsRow.classList.remove('hidden');
                }
            }
        }

        // Fungsi untuk menampilkan modal konfirmasi hapus
        function showDeleteModal(id) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/spareparts/${id}`; // Sesuaikan dengan rute destroy Anda
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan modal konfirmasi hapus
        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
