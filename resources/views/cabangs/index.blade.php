<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Cabang') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search dan Tombol Tambah --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari cabang..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Grup Tombol Aksi --}}
                    <div class="flex items-center gap-3">
                        {{-- Tombol Tambah Cabang --}}
                        <a href="{{ route('cabangs.create') }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Cabang
                        </a>
                        {{-- Tombol Lihat Cabang Tidak Aktif --}}
                        <a href="{{ route('cabangs.inactive') }}"
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
                    <table id="cabangsTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Cabang</th>
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Alamat</th>
                                <th class="text-center py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($cabangs as $cabang)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $cabang->nama_cabang }}</td>
                                    <td class="py-3 px-4">{{ $cabang->alamat }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('cabangs.edit', $cabang) }}" class="text-blue-600 ...">Edit</a>
                                        <form action="{{ route('cabangs.toggleStatus', $cabang) }}" method="POST" class="inline-block ml-2">
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
                                    <td colspan="3" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data cabang.</td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="3" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data cabang yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $cabangs->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Search --}}
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("cabangsTable");
            let tr = table.getElementsByTagName("tr");
            let noResultsRow = document.getElementById("noResultsRow");
            let initialEmptyRow = document.getElementById("initialEmptyRow");
            let foundResults = false;

            for (let i = 0; i < tr.length; i++) {
                if (tr[i].getElementsByTagName("th").length > 0 || tr[i].id === "noResultsRow" || tr[i].id === "initialEmptyRow") continue;

                let foundInRow = false;
                let tdNama = tr[i].getElementsByTagName("td")[0];
                let tdAlamat = tr[i].getElementsByTagName("td")[1];

                if (tdNama && tdNama.textContent.toUpperCase().indexOf(filter) > -1) foundInRow = true;
                if (tdAlamat && tdAlamat.textContent.toUpperCase().indexOf(filter) > -1) foundInRow = true;

                tr[i].style.display = foundInRow ? "" : "none";
                if (foundInRow) foundResults = true;
            }

            if (noResultsRow) noResultsRow.style.display = foundResults || filter === "" ? "none" : "";
            if (initialEmptyRow) initialEmptyRow.style.display = filter !== "" ? "none" : (tr.length > 2 ? "" : "none");
        }
    </script>
</x-app-layout>