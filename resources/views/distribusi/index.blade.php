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
                        <input type="text" id="searchInput" placeholder="Cari distribusi..."
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
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">"{{ $distribusi->alasan_penolakan }}"</p>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('distribusi.show', $distribusi->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 py-1 px-2 rounded transition">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    @if(request('search'))
                                        <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            Tidak ada histori yang cocok untuk pencarian: "<strong>{{ request('search') }}</strong>"
                                        </td>
                                    @else
                                        <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            Tidak ada data histori yang aktif.
                                        </td>
                                    @endif
                                </tr>
                            @endforelse
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let typingTimer;
        const doneTypingInterval = 500;

        // Otomatis fokus ke input search jika ada query pencarian di URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentSearch = urlParams.get('search');
        if (currentSearch) {
            searchInput.value = currentSearch;
            searchInput.focus();
            
            // Trik untuk memindahkan kursor ke akhir
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }

        // Listener untuk input, dengan jeda (debounce)
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
