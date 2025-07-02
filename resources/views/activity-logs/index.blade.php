<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Log Aktivitas Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search Bar (Optional) --}}
                <div class="mb-6 w-full md:w-1/3">
                    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari log aktivitas..."
                        class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                </div>

                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="logTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Waktu</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-700 dark:text-gray-300"">Pengguna</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-700 dark:text-gray-300"">Deskripsi Aktivitas</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-700 dark:text-gray-300"">Alamat IP</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            @forelse($logs as $log)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-2 px-4 whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                    <td class="py-2 px-4">{{ $log->user->name ?? 'Sistem' }}</td>
                                    <td class="py-2 px-4">{{ $log->description }}</td>
                                    <td class="py-2 px-4">{{ $log->ip_address }}</td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada aktivitas yang tercatat.</td>
                                </tr>
                            @endforelse
                            {{-- Row jika search tidak ketemu --}}
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada log yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script Search Table --}}
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput").value.toUpperCase();
            let table = document.getElementById("logTable");
            let tr = table.getElementsByTagName("tr");
            let foundRows = 0;
            let noResultsRow = document.getElementById("noResultsRow");
            let initialEmptyRow = document.getElementById("initialEmptyRow");

            for (let i = 0; i < tr.length; i++) {
                if (tr[i].getElementsByTagName("th").length > 0 || tr[i].id === "noResultsRow" || tr[i].id === "initialEmptyRow") continue;

                let found = false;
                let tdUser = tr[i].getElementsByTagName("td")[1];
                let tdDesc = tr[i].getElementsByTagName("td")[2];

                if (tdUser && tdUser.textContent.toUpperCase().includes(input)) found = true;
                if (tdDesc && tdDesc.textContent.toUpperCase().includes(input)) found = true;

                tr[i].style.display = found ? "" : "none";
                if (found) foundRows++;
            }

            if (noResultsRow) noResultsRow.style.display = (foundRows === 0 && input !== "") ? "" : "none";
            if (initialEmptyRow) initialEmptyRow.style.display = (foundRows === 0 && input === "") ? "" : "none";
        }
    </script>
</x-app-layout>
