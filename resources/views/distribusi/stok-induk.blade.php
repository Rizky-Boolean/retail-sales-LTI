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
                <div class="mb-6 w-full md:w-1/3">
                    <input type="text" id="searchInput" placeholder="Cari sparepart..."
                        class="block w-full p-2.5 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 transition duration-150 ease-in-out">
                </div>

                {{-- Tabel Data Stok --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="stokTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kode Part</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Stok Tersedia</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition data-row">
                                    <td class="py-3 px-4">{{ $sparepart->kode_part }}</td>
                                    <td class="py-3 px-4">{{ $sparepart->nama_part }}</td>
                                    <td class="py-3 px-4 text-center">{{ $sparepart->stok_induk }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($sparepart->stok_induk <= 0)
                                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Habis
                                            </span>
                                        @elseif($sparepart->stok_induk <= 5)
                                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Hampir Habis
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Tersedia
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    @if(request('search'))
                                        <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            Tidak ada sparepart yang cocok untuk pencarian: "<strong>{{ request('search') }}</strong>"
                                        </td>
                                    @else
                                        <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            Tidak ada data sparepart di gudang induk.
                                        </td>
                                    @endif
                                </tr>
                            @endforelse
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
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let typingTimer;
        const doneTypingInterval = 500;

        // Otomatis fokus dan atur kursor
        const urlParams = new URLSearchParams(window.location.search);
        const currentSearch = urlParams.get('search');
        if (currentSearch) {
            searchInput.value = currentSearch;
            searchInput.focus();
            
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }

        // Listener untuk input pencarian
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                const currentUrl = new URL(window.location);
                const searchValue = searchInput.value.trim();

                if (searchValue) {
                    currentUrl.searchParams.set('search', searchValue);
                } else {
                    currentUrl.searchParams.delete('search');
                }
                
                currentUrl.searchParams.set('page', '1');
                
                if (window.location.href !== currentUrl.href) {
                    window.location.href = currentUrl.toString();
                }
            }, doneTypingInterval);
        });
    });
    </script>
</x-app-layout>
