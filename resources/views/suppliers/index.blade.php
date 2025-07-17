<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Supplier') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Search dan Tombol Tambah --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari supplier..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>

                    {{-- Tombol Tambah Supplier --}}
                    <div class="flex justify-end">
                        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Supplier
                        </a>
                        <a href="{{ route('suppliers.trash') }}" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out ml-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2H7V5a2 2 0 012-2zm-2 6h8"></path>
                            </svg>
                            Lihat Data Terhapus
                        </a>
                    </div>
                </div>

                {{-- Alert Message --}}
                @include('partials.alert-messages')

                {{-- Tabel Data --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="suppliersTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Nama Supplier</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Alamat</th>
                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Kontak</th>
                                <th class="text-center py-3 px-4 uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($suppliers as $supplier)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $supplier->nama_supplier }}</td>
                                    <td class="py-3 px-4">{{ $supplier->alamat }}</td>
                                    <td class="py-3 px-4">{{ $supplier->kontak }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded">Edit</a>
                                            <button type="button" onclick="showDeleteModal({{ $supplier->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 py-1 px-2 rounded">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow">
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data supplier.</td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data supplier yang cocok.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $suppliers->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-800 dark:text-gray-300">Apakah Anda yakin ingin menghapus data ini?</h3>
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="hideDeleteModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition">
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

    {{-- Script untuk Search dan Delete Modal --}}
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("suppliersTable");
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
            if (initialEmptyRow) initialEmptyRow.style.display = filter !== "" ? "none" : "";
        }

        function showDeleteModal(id) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/suppliers/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
