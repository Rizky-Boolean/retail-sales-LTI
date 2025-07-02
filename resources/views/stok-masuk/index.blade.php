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
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari histori stok masuk..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Tombol Tambah --}}
                    <div class="flex justify-end">
                        <a href="{{ route('stok-masuk.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">#ID</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Tanggal</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Supplier</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Total Final</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($stokMasuks as $stokMasuk)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">TR-{{ $stokMasuk->id }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->format('d M Y') }}</td>
                                    <td class="py-3 px-4">{{ $stokMasuk->supplier->nama_supplier ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($stokMasuk->total_final, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('stok-masuk.show', $stokMasuk->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada data stok masuk.</td>
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
        function filterTable() {
            let input = document.getElementById("searchInput").value.toUpperCase();
            let table = document.getElementById("stokMasukTable");
            let tr = table.getElementsByTagName("tr");
            let foundRows = 0;
            let noResultsRow = document.getElementById("noResultsRow");
            let initialEmptyRow = document.getElementById("initialEmptyRow");

            for (let i = 0; i < tr.length; i++) {
                if (tr[i].getElementsByTagName("th").length > 0 || tr[i].id === "noResultsRow" || tr[i].id === "initialEmptyRow") continue;

                let tdId = tr[i].getElementsByTagName("td")[0];
                let tdSupplier = tr[i].getElementsByTagName("td")[2];
                let match = false;

                if (tdId && tdId.textContent.toUpperCase().includes(input)) match = true;
                if (tdSupplier && tdSupplier.textContent.toUpperCase().includes(input)) match = true;

                tr[i].style.display = match ? "" : "none";
                if (match) foundRows++;
            }

            if (noResultsRow) noResultsRow.style.display = (foundRows === 0 && input !== "") ? "" : "none";
            if (initialEmptyRow) initialEmptyRow.style.display = (foundRows === 0 && input === "") ? "" : "none";
        }
    </script>
</x-app-layout>
