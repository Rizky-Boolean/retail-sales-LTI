<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Kiriman Masuk') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Header Section --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Daftar Kiriman dari Gudang Induk
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Konfirmasi penerimaan atau penolakan barang yang masuk ke gudang Anda.</p>
                    </div>
                    {{-- Search Input --}}
                    <div class="w-full md:w-1/3">
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari ID Kiriman..."
                            class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                    </div>
                </div>

                @include('partials.alert-messages')

                {{-- Tabel Kiriman Masuk --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="kirimanTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">ID Kiriman</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Tanggal Kirim</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Dikirim Oleh</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Status</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($kirimanMasuk as $kiriman)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-3 px-4 whitespace-nowrap">DIST-{{ $kiriman->id }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($kiriman->tanggal_distribusi)->format('d M Y') }}</td>
                                    <td class="py-3 px-4">{{ $kiriman->user->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @php
                                            $statusClass = '';
                                            if ($kiriman->status == 'dikirim') $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200';
                                            elseif ($kiriman->status == 'diterima') $statusClass = 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200';
                                            elseif ($kiriman->status == 'ditolak') $statusClass = 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200';
                                        @endphp
                                        <span class="capitalize px-3 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                            {{ $kiriman->status }}
                                        </span>
                                    </td>
                                    <td class="text-center py-3 px-4">
                                        <div class="flex justify-center items-center gap-4">
                                            {{-- [DIUBAH] Menghapus @else yang berisi strip --}}
                                            <div class="flex justify-center items-center gap-4">
                                            @if($kiriman->status == 'dikirim')
                                                <button type="button" onclick="showTerimaModal('{{ route('cabang.penerimaan.terima', $kiriman->id) }}')" class="flex items-center gap-1 text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300" title="Terima">
                                                    <span>Terima</span>
                                                </button>
                                                <button type="button" onclick="showTolakModal('{{ route('cabang.penerimaan.tolak', $kiriman->id) }}')" class="flex items-center gap-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Tolak">
                                                    <span>Tolak</span>
                                                </button>
                                            @endif
                                            <a href="{{ route('distribusi.show', $kiriman->id) }}" class="flex items-center gap-1 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
                                                <span>Detail</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="initialEmptyRow"><td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada riwayat kiriman.</td></tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;"><td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada kiriman yang cocok.</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $kirimanMasuk->links() }}</div>
            </div>
        </div>
    </div>
    {{-- [BARU] Modal untuk Konfirmasi Terima --}}
    <div id="terimaModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto">
            <form id="terimaForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="text-center">
                    <svg class="mx-auto mb-4 h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mb-5 text-lg font-semibold text-gray-900 dark:text-gray-100">Konfirmasi Penerimaan</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menerima kiriman ini? Stok akan otomatis ditambahkan ke gudang Anda.</p>
                </div>
                <div class="mt-6 flex justify-center gap-4">
                    <button type="button" onclick="hideTerimaModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">Ya, Terima</button>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal untuk Alasan Penolakan --}}
    <div id="tolakModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md mx-auto">
            <form id="tolakForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="text-center">
                    <svg class="mx-auto mb-4 h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-100">Tolak Kiriman</h3>
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Mohon berikan alasan penolakan. Alasan ini akan dikirimkan ke gudang induk.</p>
                </div>
                <div>
                    <label for="alasan_penolakan" class="sr-only">Alasan Penolakan</label>
                    <textarea id="alasan_penolakan" name="alasan_penolakan" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-gray-900 dark:text-gray-100 focus:ring-red-500 focus:border-red-500" required placeholder="Contoh: Jumlah barang tidak sesuai dengan surat jalan"></textarea>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="hideTolakModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Tolak Kiriman</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function filterTable() {
            let input = document.getElementById("searchInput").value.toUpperCase();
            let table = document.getElementById("kirimanTable");
            let tr = table.getElementsByTagName("tr");
            let noResultsRow = document.getElementById("noResultsRow");
            let initialEmptyRow = document.getElementById("initialEmptyRow");
            let foundRows = 0;

            for (let i = 0; i < tr.length; i++) {
                if (tr[i].getElementsByTagName("th").length > 0 || tr[i].id === "noResultsRow" || tr[i].id === "initialEmptyRow") continue;
                let tdId = tr[i].getElementsByTagName("td")[0];
                if (tdId && tdId.textContent.toUpperCase().includes(input)) {
                    tr[i].style.display = "";
                    foundRows++;
                } else {
                    tr[i].style.display = "none";
                }
            }
            if(noResultsRow) noResultsRow.style.display = (foundRows === 0 && input !== "") ? "" : "none";
            if(initialEmptyRow) initialEmptyRow.style.display = (foundRows > 0 || input !== "") ? "none" : "";
        }
        // [BARU] Script untuk Modal Terima
        function showTerimaModal(actionUrl) {
            document.getElementById('terimaForm').action = actionUrl;
            document.getElementById('terimaModal').classList.remove('hidden');
        }
        function hideTerimaModal() {
            document.getElementById('terimaModal').classList.add('hidden');
        }

        function showTolakModal(actionUrl) {
            document.getElementById('tolakForm').action = actionUrl;
            document.getElementById('tolakModal').classList.remove('hidden');
        }

        function hideTolakModal() {
            document.getElementById('tolakModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
