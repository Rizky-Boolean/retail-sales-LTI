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
                {{-- TAMPILAN UNTUK SUPER ADMIN --}}
                {{-- ================================================================= --}}
                @if(auth()->user()->role === 'super_admin')
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Ringkasan Utama Sistem </h3>
                {{-- Kartu Statistik --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Stat Card Total Jenis Sparepart -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg shadow-md border border-blue-100 dark:border-blue-800 flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-700 rounded-full flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Jenis Sparepart</p>
                            <p class="text-3xl font-bold text-blue-800 dark:text-blue-200">{{ $totalSparepart ?? 0 }}</p>
                        </div>
                    </div>
                    <!-- Stat Card Total Supplier -->
                    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg shadow-md border border-green-100 dark:border-green-800 flex items-center space-x-4">
                        <div class="p-3 bg-green-100 dark:bg-green-700 rounded-full flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5m-6 0h6"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Supplier</p>
                            <p class="text-3xl font-bold text-green-800 dark:text-green-200">{{ $totalSupplier ?? 0 }}</p>
                        </div>
                    </div>
                    <!-- Stat Card Total Cabang -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg shadow-md border border-purple-100 dark:border-purple-800 flex items-center space-x-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-700 rounded-full flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 005.356-1.857m7.044 0C9.59 16.521 2.68 15.653 2 15.653v-1.894c0-.264.043-.526.128-.775l2.257-6.772A2 2 0 016.292 4h11.416a2 2 0 011.907 2.212l2.257 6.772c.085.249.128.511.128.775v1.894c-.68.001-7.59.869-7.59 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cabang</p>
                            <p class="text-3xl font-bold text-purple-800 dark:text-purple-200">{{ $totalCabang ?? 0 }}</p>
                        </div>
                    </div>
                    <!-- Stat Card Total Stok Seluruh Gudang -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg shadow-md border border-yellow-100 dark:border-yellow-800 flex items-center space-x-4">
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-700 rounded-full flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4m0-10h.01M12 12h.01"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Stok Seluruh Gudang</p>
                            <p class="text-3xl font-bold text-yellow-800 dark:text-yellow-200">{{ number_format($totalStokSeluruhGudang ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Grafik --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8"> {{-- Tambah mb-8 --}}
                    <!-- Grafik Pengeluaran -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">Pengeluaran Pembelian (6 Bulan Terakhir)</h3>
                        <div class="relative h-80">
                            <canvas id="pengeluaranChart"></canvas>
                        </div>
                    </div>
                    <!-- Grafik Keuntungan -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">Keuntungan Penjualan Cabang (6 Bulan Terakhir)</h3>
                        <div class="relative h-80">
                            <canvas id="keuntunganChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Daftar Stok Hampir Habis --}}
                    <div class="lg:col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                            <svg class="h-6 w-6 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Stok Gudang Induk Hampir Habis (Stok < 10)
                        </h3>
                        <ul class="space-y-3">
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
                    {{-- Grafik Distribusi Stok --}}
                    <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">Distribusi Stok per Cabang</h3>
                        <div class="relative h-80">
                            <canvas id="distribusiStokChart"></canvas>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ================================================================= --}}
                {{-- TAMPILAN KHUSUS UNTUK ADMIN GUDANG INDUK --}}
                {{-- ================================================================= --}}
                @if(auth()->user()->role === 'admin_gudang_induk')
                <div class="space-y-6">
                    {{-- Baris Pertama: Stat Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        {{-- Total Jenis Sparepart --}}
                        <div class="bg-blue-50 dark:bg-gray-800/50 p-6 rounded-lg shadow-md border border-blue-100 dark:border-gray-700 flex items-center space-x-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-700 rounded-full flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Jenis Sparepart</p>
                                <p class="text-3xl font-bold text-blue-800 dark:text-blue-200">{{ $totalSparepart ?? 0 }}</p>
                            </div>
                        </div>

                        {{-- Total Supplier --}}
                        <div class="bg-green-50 dark:bg-gray-800/50 p-6 rounded-lg shadow-md border border-green-100 dark:border-gray-700 flex items-center space-x-4">
                            <div class="p-3 bg-green-100 dark:bg-green-700 rounded-full flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5m-6 0h6"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Supplier</p>
                                <p class="text-3xl font-bold text-green-800 dark:text-green-200">{{ $totalSupplier ?? 0 }}</p>
                            </div>
                        </div>

                        {{-- Total Cabang --}}
                        <div class="bg-purple-50 dark:bg-gray-800/50 p-6 rounded-lg shadow-md border border-purple-100 dark:border-gray-700 flex items-center space-x-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-700 rounded-full flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 005.356-1.857m7.044 0C9.59 16.521 2.68 15.653 2 15.653v-1.894c0-.264.043-.526.128-.775l2.257-6.772A2 2 0 016.292 4h11.416a2 2 0 011.907 2.212l2.257 6.772c.085.249.128.511.128.775v1.894c-.68.001-7.59.869-7.59 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cabang</p>
                                <p class="text-3xl font-bold text-purple-800 dark:text-purple-200">{{ $totalCabang ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Baris Kedua: Daftar Informasi Penting --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Kolom Kiri: Stok Hampir Habis --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="h-6 w-6 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Stok Gudang Induk Hampir Habis (Stok < 10)
                            </h3>
                            <ul class="space-y-3 max-h-60 overflow-y-auto">
                                @forelse($stokHampirHabis as $item)
                                <li class="flex justify-between items-center bg-red-50 dark:bg-red-900/20 p-3 rounded-md">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->nama_part }}</span>
                                    <span class="font-bold text-red-600 dark:text-red-400">Stok: {{ $item->stok_induk }}</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">Tidak ada stok yang hampir habis.</li>
                                @endforelse
                            </ul>
                        </div>
                        
                        {{-- Kolom Kanan: 5 Distribusi Terakhir --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                            <svg class="h-6 w-6 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                5 Distribusi Terakhir
                            </h3>
                            <ul class="space-y-3 max-h-60 overflow-y-auto">
                                @forelse($distribusiTerbaru as $distribusi)
                                <li class="flex justify-between items-center bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-md">
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">#DIST-{{ $distribusi->id }} ke {{ $distribusi->cabangTujuan->nama_cabang }}</span>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $distribusi->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $distribusi->created_at->diffForHumans() }}</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">Belum ada transaksi distribusi.</li>
                                @endforelse
                            </ul>
                        </div>
                        {{-- Top Moving Items --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="h-6 w-6 text-teal-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                5 Sparepart Paling Sering Didistribusikan (30 Hari Terakhir)
                            </h3>
                            <ul class="space-y-3">
                                @forelse($topMovingItems as $item)
                                <li class="flex justify-between items-center bg-teal-50 dark:bg-teal-900/20 p-3 rounded-md">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->nama_part }}</span>
                                    <span class="font-bold text-teal-600 dark:text-teal-400">{{ $item->total_didistribusi }} unit</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">
                                    Belum ada data distribusi dalam 30 hari terakhir.
                                </li>
                                @endforelse
                            </ul>
                        </div>
                        {{-- 5 Pembelian Terakhir --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="h-6 w-6 text-cyan-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                5 Pembelian Terakhir dari Supplier
                            </h3>
                            <ul class="space-y-3 max-h-60 overflow-y-auto">
                                @forelse($pembelianTerbaru as $pembelian)
                                <li class="flex justify-between items-center bg-cyan-50 dark:bg-cyan-900/20 p-3 rounded-md">
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">#TR-{{ $pembelian->id }} dari {{ $pembelian->supplier->nama_supplier }}</span>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $pembelian->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <span class="text-sm font-medium text-cyan-700 dark:text-cyan-400">Rp {{ number_format($pembelian->total_final, 0, ',', '.') }}</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">
                                    Belum ada transaksi pembelian.
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ================================================================= --}}
                {{-- TAMPILAN UNTUK ADMIN GUDANG CABANG --}}
                {{-- ================================================================= --}}
                @if(auth()->user()->role === 'admin_gudang_cabang')
                <div class="space-y-6">
                    {{-- Baris 1: Stat Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Penjualan Hari Ini -->
                        <div class="bg-indigo-50 dark:bg-gray-800/50 p-6 rounded-lg shadow-md border border-indigo-100 dark:border-gray-700 flex items-center space-x-4">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex-shrink-0">
                                <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penjualan Hari Ini</p>
                                <p class="text-3xl font-bold text-indigo-800 dark:text-indigo-200">{{ 'Rp ' . number_format($penjualanHariIni ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <!-- Keuntungan Bulan Ini -->
                        <div class="bg-orange-50 dark:bg-gray-800/50 p-6 rounded-lg shadow-md border border-orange-100 dark:border-gray-700 flex items-center space-x-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/50 rounded-full flex-shrink-0">
                                <svg class="h-8 w-8 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Keuntungan Bulan Ini</p>
                                <p class="text-3xl font-bold text-orange-800 dark:text-orange-200">{{ 'Rp ' . number_format($keuntunganBulanIni ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <!-- Kiriman Menunggu -->
                        <div class="bg-cyan-50 dark:bg-gray-800/50 p-6 rounded-lg shadow-md border border-cyan-100 dark:border-gray-700 flex items-center space-x-4">
                            <div class="p-3 bg-cyan-100 dark:bg-cyan-900/50 rounded-full flex-shrink-0">
                            <svg class="h-8 w-8 text-cyan-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 19v-5h-5M4 19h5v-5M20 4h-5v5"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kiriman Menunggu</p>
                                <p class="text-3xl font-bold text-cyan-800 dark:text-cyan-200">{{ $kirimanMenunggu ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Baris 2: Informasi Stok dan Penjualan --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="h-6 w-6 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Stok Cabang Hampir Habis (Stok < 10)
                            </h3>
                            <ul class="space-y-3 max-h-60 overflow-y-auto">
                                @forelse($stokHampirHabis as $item)
                                <li class="flex justify-between items-center bg-red-50 dark:bg-red-900/20 p-3 rounded-md">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->nama_part }}</span>
                                    <span class="font-bold text-red-600 dark:text-red-400">Stok: {{ $item->stok }}</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">Tidak ada stok yang hampir habis.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="h-6 w-6 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                5 Penjualan Terakhir
                            </h3>
                            <ul class="space-y-3 max-h-60 overflow-y-auto">
                                @forelse($penjualanTerbaru as $penjualan)
                                <li class="flex justify-between items-center bg-blue-50 dark:bg-blue-900/20 p-3 rounded-md">
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300"><a href="{{ route('penjualan.show', $penjualan) }}" class="text-blue-600 hover:underline dark:text-blue-400">NOTA-{{ $penjualan->id }}</a></span>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Total: Rp {{ number_format($penjualan->total_final, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $penjualan->created_at->diffForHumans() }}</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">Belum ada transaksi penjualan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Baris 3: Grafik dan Stok Mati --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- [DIUBAH] Grafik Komposisi Penjualan -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="h-6 w-6 text-pink-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.539 1.118l-3.975-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                Sparepart Terlaris (Bulan Ini)
                            </h3>
                            <div class="flex items-center h-80">
                                <div class="relative flex-shrink-0 w-1/2 h-full">
                                    <canvas id="komposisiPenjualanChart"></canvas>
                                </div>
                                <div id="chart-legend" class="flex-grow ml-6 space-y-2">
                                </div>
                            </div>
                        </div>
                        <!-- Daftar Stok Mati -->
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                <svg class="h-6 w-6 text-gray-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                Stok Jarang Terjual (90 Hari Terakhir)
                            </h3>
                            <ul class="space-y-3 max-h-80 overflow-y-auto">
                                @forelse($slowMovingItems as $item)
                                <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/30 p-3 rounded-md">
                                    <div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->nama_part }}</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Stok Saat Ini: {{ $item->stok }}</p>
                                    </div>
                                    <span class="font-bold text-gray-500 dark:text-gray-400">{{ $item->total_terjual_90_hari }} terjual</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-md">
                                    Semua stok memiliki penjualan.
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>


        @push('scripts')
        {{-- Script hanya akan dimuat jika role-nya sesuai --}}
        @if(auth()->user()->role === 'admin_gudang_cabang')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sparepartTerlarisData = @json($chartSparepartTerlaris ?? []);

                if (sparepartTerlarisData.length > 0) {
                    const chartCanvas = document.getElementById('komposisiPenjualanChart');
                    const legendContainer = document.getElementById('chart-legend');

                    const chartData = {
                        labels: sparepartTerlarisData.map(item => item.nama_part),
                        datasets: [{
                            label: 'Total Terjual',
                            data: sparepartTerlarisData.map(item => item.total_terjual),
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.7)',
                                'rgba(16, 185, 129, 0.7)',
                                'rgba(245, 158, 11, 0.7)',
                                'rgba(239, 68, 68, 0.7)',
                                'rgba(139, 92, 246, 0.7)'
                            ],
                            borderColor: [
                                'rgba(59, 130, 246, 1)',
                                'rgba(16, 185, 129, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(239, 68, 68, 1)',
                                'rgba(139, 92, 246, 1)'
                            ],
                            borderWidth: 1
                        }]
                    };

                    const komposisiChart = new Chart(chartCanvas, {
                        type: 'doughnut',
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    display: false // Sembunyikan legend bawaan
                                }
                            }
                        }
                    });

                    // Fungsi untuk membuat legend custom
                    const generateLegend = () => {
                        legendContainer.innerHTML = ''; // Kosongkan legend sebelumnya
                        const legendItems = komposisiChart.data.labels.map((label, index) => {
                            const backgroundColor = komposisiChart.data.datasets[0].backgroundColor[index];
                            const value = komposisiChart.data.datasets[0].data[index];
                            return `
                                <div class="flex items-center text-sm">
                                    <span class="block w-3 h-3 mr-2 rounded-full" style="background-color: ${backgroundColor}"></span>
                                    <span class="text-gray-600 dark:text-gray-400">${label} (${value})</span>
                                </div>
                            `;
                        });
                        legendContainer.innerHTML = legendItems.join('');
                    };
                    
                    generateLegend();
                }
            });
        </script>
        @endif
        @if(auth()->user()->role === 'super_admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Data dari Controller
                const pengeluaranData = @json($chartPengeluaran ?? []);
                const keuntunganData = @json($chartKeuntungan ?? []);
                const distribusiStokData = @json($chartDistribusiStok ?? []);

                // Fungsi untuk membuat label bulan
                const last6Months = Array.from({length: 6}, (v, i) => {
                    const d = new Date();
                    d.setMonth(d.getMonth() - i);
                    return d.toLocaleString('id-ID', { month: 'short' });
                }).reverse();

                // Memproses data agar sesuai dengan label
                const processData = (data, labels) => {
                    const dataMap = new Map(data.map(item => [item.bulan, item.total || item.total_profit]));
                    return labels.map(label => dataMap.get(label) || 0);
                };

                // 1. Grafik Pengeluaran
                new Chart(document.getElementById('pengeluaranChart'), {
                    type: 'bar',
                    data: { labels: last6Months, datasets: [{ label: 'Total Pengeluaran (Rp)', data: processData(pengeluaranData, last6Months), backgroundColor: 'rgba(239, 68, 68, 0.5)', borderColor: 'rgba(239, 68, 68, 1)', borderWidth: 1 }] },
                    options: { scales: { y: { beginAtZero: true } } }
                });

                // 2. Grafik Keuntungan
                new Chart(document.getElementById('keuntunganChart'), {
                    type: 'line',
                    data: { labels: last6Months, datasets: [{ label: 'Total Keuntungan (Rp)', data: processData(keuntunganData, last6Months), backgroundColor: 'rgba(59, 130, 246, 0.5)', borderColor: 'rgba(59, 130, 246, 1)', borderWidth: 2, tension: 0.3 }] },
                    options: { scales: { y: { beginAtZero: true } } }
                });

                // 3. Grafik Distribusi Stok
                new Chart(document.getElementById('distribusiStokChart'), {
                    type: 'doughnut',
                    data: {
                        labels: distribusiStokData.map(item => item.nama_cabang),
                        datasets: [{ label: 'Total Stok', data: distribusiStokData.map(item => item.total_stok), backgroundColor: ['rgba(236, 72, 153, 0.7)', 'rgba(168, 85, 247, 0.7)', 'rgba(34, 197, 94, 0.7)', 'rgba(245, 158, 11, 0.7)'] }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
                });
            });
        </script>
        @endif
        @endpush
    </x-app-layout>