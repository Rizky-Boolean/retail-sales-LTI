<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Histori Penjualan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <a href="{{ route('penjualan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Buat Transaksi Baru
                        </a>
                    </div>

                    @include('partials.alert-messages')

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">No. Nota</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Pembeli</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Total</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse($penjualans as $penjualan)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="text-left py-3 px-4">{{ $penjualan->nomor_nota }}</td>
                                        <td class="text-left py-3 px-4">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d M Y') }}</td>
                                        <td class="text-left py-3 px-4">{{ $penjualan->nama_pembeli }}</td>
                                        <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</td>
                                        <td class="text-center py-3 px-4">
                                            {{-- [UBAH] Hanya sisakan tombol Nota --}}
                                            <a href="{{ route('penjualan.show', $penjualan->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded">Nota</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Belum ada transaksi penjualan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $penjualans->links() }}
                    </div>
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
