<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Wrapper utama untuk konten dashboard --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700"> {{-- Shadow lebih dalam, padding lebih besar, border --}}

                {{-- ================================================================= --}}
                {{-- TAMPILAN UNTUK ADMIN GUDANG INDUK --}}
                {{-- ================================================================= --}}
                @if(auth()->user()->role === 'admin_induk')
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Ringkasan Gudang Induk</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8"> {{-- gap lebih besar, margin bawah --}}

                    <!-- Stat Card Total Jenis Sparepart -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg shadow-md border border-blue-100 dark:border-blue-800 flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-700 rounded-full flex-shrink-0">
                            {{-- Icon Sparepart (roda gigi) dari Heroicons SVG --}}
                            <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Jenis Sparepart</p>
                            <p class="text-3xl font-bold text-blue-800 dark:text-blue-200">{{ $totalSparepart ?? 0 }}</p> {{-- Pastikan variabel di-handle jika null --}}
                        </div>
                    </div>

                    <!-- Stat Card Total Supplier -->
                    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg shadow-md border border-green-100 dark:border-green-800 flex items-center space-x-4">
                        <div class="p-3 bg-green-100 dark:bg-green-700 rounded-full flex-shrink-0">
                            {{-- Icon Supplier (gedung) dari Heroicons SVG --}}
                            <svg class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5m-6 0h6"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Supplier</p>
                            <p class="text-3xl font-bold text-green-800 dark:text-green-200">{{ $totalSupplier ?? 0 }}</p> {{-- Pastikan variabel di-handle jika null --}}
                        </div>
                    </div>

                    <!-- Stat Card Total Cabang -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg shadow-md border border-purple-100 dark:border-purple-800 flex items-center space-x-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-700 rounded-full flex-shrink-0">
                            {{-- Icon Cabang (gedung kantor) dari Heroicons SVG --}}
                            <svg class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 005.356-1.857m7.044 0C9.59 16.521 2.68 15.653 2 15.653v-1.894c0-.264.043-.526.128-.775l2.257-6.772A2 2 0 016.292 4h11.416a2 2 0 011.907 2.212l2.257 6.772c.085.249.128.511.128.775v1.894c-.68.001-7.59.869-7.59 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cabang</p>
                            <p class="text-3xl font-bold text-purple-800 dark:text-purple-200">{{ $totalCabang ?? 0 }}</p> {{-- Pastikan variabel di-handle jika null --}}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Daftar Stok Hampir Habis -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Stok Gudang Induk Hampir Habis (Stok < 10)
                        </h3>
                        <ul class="space-y-3"> {{-- Lebih banyak spasi antar item --}}
                            @forelse($stokHampirHabis as $item)
                            <li class="flex justify-between items-center bg-red-50 dark:bg-red-900/10 p-3 rounded-md border border-red-100 dark:border-red-800">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->nama_part }}</span>
                                <span class="font-bold text-red-600 dark:text-red-300">Stok: {{ $item->stok_induk }}</span>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">Tidak ada stok yang hampir habis.</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Daftar Distribusi Terbaru -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                            <svg class="h-6 w-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            5 Distribusi Terakhir
                        </h3>
                        <ul class="space-y-3"> {{-- Lebih banyak spasi antar item --}}
                            @forelse($distribusiTerbaru as $distribusi)
                            <li class="flex justify-between items-center bg-yellow-50 dark:bg-yellow-900/10 p-3 rounded-md border border-yellow-100 dark:border-yellow-800">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">DIST-{{ $distribusi->id }} ke {{ $distribusi->cabangTujuan->nama_cabang }}</span>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $distribusi->created_at->format('d M Y H:i') }}</p> {{-- Format tanggal lebih spesifik --}}
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $distribusi->created_at->diffForHumans() }}</span>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">Belum ada transaksi distribusi.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                @endif


                {{-- ================================================================= --}}
                {{-- TAMPILAN UNTUK ADMIN GUDANG CABANG --}}
                {{-- ================================================================= --}}
                @if(auth()->user()->role === 'admin_cabang')
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Ringkasan Gudang Cabang</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Stat Card Penjualan Hari Ini -->
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-lg shadow-md border border-indigo-100 dark:border-indigo-800 flex items-center space-x-4">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-700 rounded-full flex-shrink-0">
                            {{-- Icon Penjualan (uang) --}}
                            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penjualan Hari Ini</p>
                            <p class="text-3xl font-bold text-indigo-800 dark:text-indigo-200">{{ 'Rp ' . number_format($penjualanHariIni ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Stat Card Keuntungan Bulan Ini -->
                    <div class="bg-orange-50 dark:bg-orange-900/20 p-6 rounded-lg shadow-md border border-orange-100 dark:border-orange-800 flex items-center space-x-4">
                        <div class="p-3 bg-orange-100 dark:bg-orange-700 rounded-full flex-shrink-0">
                            {{-- Icon Keuntungan (grafik naik) --}}
                            <svg class="h-8 w-8 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M18 14v4h-4m2-10V4h-4m2 10l-3-3-3 3-4-4"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Keuntungan Bulan Ini</p>
                            <p class="text-3xl font-bold text-orange-800 dark:text-orange-200">{{ 'Rp ' . number_format($keuntunganBulanIni ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Stat Card Kiriman Menunggu -->
                    <div class="bg-red-50 dark:bg-red-900/20 p-6 rounded-lg shadow-md border border-red-100 dark:border-red-800 flex items-center space-x-4">
                        <div class="p-3 bg-red-100 dark:bg-red-700 rounded-full flex-shrink-0">
                            {{-- Icon Kiriman Menunggu (paket) --}}
                            <svg class="h-8 w-8 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kiriman Menunggu</p>
                            <p class="text-3xl font-bold text-red-800 dark:text-red-200">{{ $kirimanMenunggu ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Daftar Stok Cabang Hampir Habis -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Stok Cabang Hampir Habis (Stok < 10)
                        </h3>
                        <ul class="space-y-3">
                            @forelse($stokHampirHabis as $item)
                            <li class="flex justify-between items-center bg-red-50 dark:bg-red-900/10 p-3 rounded-md border border-red-100 dark:border-red-800">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->nama_part }}</span>
                                <span class="font-bold text-red-600 dark:text-red-300">Stok: {{ $item->pivot->stok }}</span>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">Tidak ada stok yang hampir habis.</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Daftar Penjualan Terbaru -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                            <svg class="h-6 w-6 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            5 Penjualan Terakhir
                        </h3>
                        <ul class="space-y-3">
                            @forelse($penjualanTerbaru as $penjualan)
                            <li class="flex justify-between items-center bg-blue-50 dark:bg-blue-900/10 p-3 rounded-md border border-blue-100 dark:border-blue-800">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300"><a href="{{ route('penjualan.show', $penjualan) }}" class="text-blue-600 hover:underline dark:text-blue-400">NOTA-{{ $penjualan->id }}</a></span> {{-- Ubah DIST- menjadi NOTA- --}}
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Total: Rp {{ number_format($penjualan->total_setelah_ppn ?? $penjualan->total ?? 0, 0, ',', '.') }}</p> {{-- Tampilkan total penjualan --}}
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $penjualan->created_at->diffForHumans() }}</span>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">Belum ada transaksi penjualan.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                @endif

            </div> {{-- Penutup div.bg-white --}}
        </div>
    </div>
</x-app-layout>
