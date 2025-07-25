<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Stok Gudang Induk') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search Input --}}
                <div class="flex justify-between items-center mb-6">
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari sparepart..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>
                </div>

                {{-- Tabel Data Stok --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="stokTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kode Part</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $sparepart->kode_part }}</td>
                                    <td class="py-3 px-4">{{ $sparepart->nama_part }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="py-3 px-4 text-center">{{ $sparepart->stok_induk }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="3" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data sparepart di gudang induk.</td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="3" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada sparepart yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $spareparts->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Script untuk Search --}}
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput").value.toUpperCase();
            let table = document.getElementById("stokTable");
            let tr = table.getElementsByTagName("tr");
            let foundRows = 0;
            let noResultsRow = document.getElementById("noResultsRow");
            let initialEmptyRow = document.getElementById("initialEmptyRow");

            for (let i = 0; i < tr.length; i++) {
                if (tr[i].getElementsByTagName("th").length > 0 || tr[i].id === "noResultsRow" || tr[i].id === "initialEmptyRow") continue;

                let tdKode = tr[i].getElementsByTagName("td")[0];
                let tdNama = tr[i].getElementsByTagName("td")[1];
                let match = false;

                if (tdKode && tdKode.textContent.toUpperCase().includes(input)) match = true;
                if (tdNama && tdNama.textContent.toUpperCase().includes(input)) match = true;

                tr[i].style.display = match ? "" : "none";
                if (match) foundRows++;
            }

            if (noResultsRow) noResultsRow.style.display = (foundRows === 0 && input !== "") ? "" : "none";
            if (initialEmptyRow) {
                const hasData = table.querySelector('tbody tr:not(#initialEmptyRow):not(#noResultsRow)');
                initialEmptyRow.style.display = (hasData && foundRows === 0 && input === "") ? "none" : (hasData ? "none" : "");
            }
        }
    </script>
</x-app-layout>
