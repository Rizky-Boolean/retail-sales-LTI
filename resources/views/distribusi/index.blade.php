<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Distribusi ke Cabang') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search dan Tombol Tambah --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari distribusi..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Tombol Tambah --}}
                    <div class="flex justify-end">
                        <a href="{{ route('distribusi.create') }}" class="inline-flex items-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Buat Distribusi Baru
                        </a>
                    </div>
                </div>

                {{-- Alert Pesan --}}
                @include('partials.alert-messages')

                {{-- Tabel Histori Distribusi --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="distribusiTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">#ID</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Tanggal</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Cabang Tujuan</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Total Kirim</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Status</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($distribusis as $distribusi)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">DIST-{{ $distribusi->id }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->format('d M Y') }}</td>
                                    <td class="py-3 px-4">{{ $distribusi->cabangTujuan->nama_cabang ?? '-' }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($distribusi->total_harga_kirim, 0, ',', '.') }}</td>
                                    
                                    {{-- [DIKEMBALIKAN] Kolom Status dengan Teks Alasan yang Diperbesar --}}
                                    <td class="py-3 px-4 text-center">
                                        @php
                                            $statusClass = '';
                                            if ($distribusi->status == 'dikirim') $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200';
                                            elseif ($distribusi->status == 'diterima') $statusClass = 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200';
                                            elseif ($distribusi->status == 'ditolak') $statusClass = 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200';
                                        @endphp
                                        
                                        <span class="inline-block px-3 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                            {{ ucfirst($distribusi->status) }}
                                        </span>

                                        @if($distribusi->status == 'ditolak' && !empty($distribusi->alasan_penolakan))
                                            {{-- [DIUBAH] Ukuran font diperbesar dan gaya italic dihilangkan --}}
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">"{{ $distribusi->alasan_penolakan }}"</p>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('distribusi.show', $distribusi->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded transition">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada data distribusi.</td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data distribusi yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $distribusis->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Script Search --}}
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput").value.toUpperCase();
            let table = document.getElementById("distribusiTable");
            let tr = table.getElementsByTagName("tr");
            let foundRows = 0;
            let noResultsRow = document.getElementById("noResultsRow");
            let initialEmptyRow = document.getElementById("initialEmptyRow");

            for (let i = 0; i < tr.length; i++) {
                if (tr[i].getElementsByTagName("th").length > 0 || tr[i].id === "noResultsRow" || tr[i].id === "initialEmptyRow") continue;

                let tdId = tr[i].getElementsByTagName("td")[0];
                let tdCabang = tr[i].getElementsByTagName("td")[2];
                let match = false;

                if (tdId && tdId.textContent.toUpperCase().includes(input)) match = true;
                if (tdCabang && tdCabang.textContent.toUpperCase().includes(input)) match = true;

                tr[i].style.display = match ? "" : "none";
                if (match) foundRows++;
            }

            if (noResultsRow) noResultsRow.style.display = (foundRows === 0 && input !== "") ? "" : "none";
            if (initialEmptyRow) initialEmptyRow.style.display = (foundRows === 0 && input === "") ? "" : "none";
        }
    </script>
</x-app-layout>
