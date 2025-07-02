<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Histori Penjualan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Tombol Tambah --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari penjualan..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('penjualan.create') }}"
                           class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            + Buat Transaksi Baru
                        </a>
                    </div>
                </div>

                {{-- Alert Message --}}
                @include('partials.alert-messages')

                {{-- Tabel Penjualan --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="penjualanTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase font-bold"> {{-- Mengubah bg-gray-100 menjadi bg-gray-50 --}}
                            <tr>
                                <th class="py-3 px-4 text-left">No. Nota</th>
                                <th class="py-3 px-4 text-left">Tanggal</th>
                                <th class="py-3 px-4 text-left">Pembeli</th>
                                <th class="py-3 px-4 text-right">Total</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @forelse($penjualans as $penjualan)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 data-row"> {{-- Tambah class 'data-row' --}}
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $penjualan->nomor_nota }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d M Y') }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $penjualan->nama_pembeli }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center space-x-2 whitespace-nowrap">
                                        <a href="{{ route('penjualan.show', $penjualan->id) }}"
                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-150 ease-in-out">
                                            Nota
                                        </a>
                                        <button type="button" onclick="showCancelModal({{ $penjualan->id }})"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150 ease-in-out">
                                            Batal
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow"> {{-- Tambah ID --}}
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Belum ada transaksi penjualan.
                                    </td>
                                </tr>
                            @endforelse
                            {{-- Baris jika hasil search tidak ditemukan --}}
                            <tr id="noResultsRow" class="hidden"> {{-- Tambah ID dan sembunyikan default --}}
                                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Tidak ada transaksi yang cocok dengan pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $penjualans->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Pembatalan --}}
    <div id="cancelModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-800 dark:text-gray-300">Apakah Anda yakin ingin membatalkan transaksi ini? Stok akan dikembalikan.</h3>
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="hideCancelModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition duration-150 ease-in-out">
                        Batal
                    </button>
                    <form id="cancelForm" method="POST" action="" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            Batalkan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Search Filter --}}
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("penjualanTable"); // Mengubah ID tabel
            let dataRows = table.querySelectorAll('tbody .data-row'); // Hanya ambil baris data
            let noResultsRow = document.getElementById("noResultsRow");
            let initialEmptyRow = document.getElementById("initialEmptyRow");
            let visibleDataRowsCount = 0;

            // Sembunyikan semua baris data dan baris pesan kosong terlebih dahulu
            dataRows.forEach(row => {
                row.style.display = "none";
            });
            noResultsRow.style.display = "none";
            if (initialEmptyRow) {
                initialEmptyRow.style.display = "none";
            }

            // Jika filter kosong, tampilkan semua baris data (atau initialEmptyRow jika tidak ada data)
            if (filter === "") {
                if (dataRows.length > 0) {
                    dataRows.forEach(row => {
                        row.style.display = "";
                        visibleDataRowsCount++;
                    });
                } else if (initialEmptyRow) {
                    initialEmptyRow.style.display = ""; // Tampilkan pesan "Belum ada transaksi penjualan."
                }
            } else {
                // Jika filter tidak kosong, filter baris data
                dataRows.forEach(row => {
                    let tdNota = row.getElementsByTagName("td")[0]; // Kolom No. Nota
                    let tdPembeli = row.getElementsByTagName("td")[2]; // Kolom Pembeli

                    let rowMatchesFilter = false;

                    if (tdNota && tdNota.textContent.toUpperCase().indexOf(filter) > -1) {
                        rowMatchesFilter = true;
                    }
                    if (!rowMatchesFilter && tdPembeli && tdPembeli.textContent.toUpperCase().indexOf(filter) > -1) {
                        rowMatchesFilter = true;
                    }

                    if (rowMatchesFilter) {
                        row.style.display = "";
                        visibleDataRowsCount++;
                    } else {
                        row.style.display = "none";
                    }
                });

                // Setelah filtering, jika tidak ada baris data yang terlihat, tampilkan noResultsRow
                if (visibleDataRowsCount === 0) {
                    noResultsRow.style.display = "";
                }
            }
        }

        // Fungsi untuk menampilkan modal konfirmasi pembatalan
        function showCancelModal(id) {
            const cancelForm = document.getElementById('cancelForm');
            cancelForm.action = `/penjualan/${id}`; // Sesuaikan dengan rute destroy Anda
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan modal konfirmasi pembatalan
        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
