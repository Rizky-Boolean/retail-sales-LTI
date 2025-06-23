<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ================================================================= --}}
            {{-- TAMPILAN UNTUK ADMIN GUDANG INDUK --}}
            {{-- ================================================================= --}}
            @if(auth()->user()->role === 'admin_induk')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Stat Card Total Sparepart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Total Jenis Sparepart</h3>
                        <p class="text-3xl font-bold">{{ $totalSparepart }}</p>
                    </div>
                </div>
                 <!-- Stat Card Total Supplier -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Total Supplier</h3>
                        <p class="text-3xl font-bold">{{ $totalSupplier }}</p>
                    </div>
                </div>
                 <!-- Stat Card Total Cabang -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Total Cabang</h3>
                        <p class="text-3xl font-bold">{{ $totalCabang }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Daftar Stok Hampir Habis -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-semibold mb-4">Stok Gudang Induk Hampir Habis (Stok < 10)</h3>
                        <ul class="space-y-2">
                            @forelse($stokHampirHabis as $item)
                            <li class="flex justify-between items-center text-sm">
                                <span>{{ $item->nama_part }}</span>
                                <span class="font-bold text-red-500">{{ $item->stok_induk }}</span>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500">Tidak ada stok yang hampir habis.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <!-- Daftar Distribusi Terbaru -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-semibold mb-4">5 Distribusi Terakhir</h3>
                        <ul class="space-y-2">
                            @forelse($distribusiTerbaru as $distribusi)
                            <li class="flex justify-between items-center text-sm">
                                <span><a href="{{ route('distribusi.show', $distribusi) }}" class="text-blue-500 hover:underline">DIST-{{ $distribusi->id }}</a> ke {{ $distribusi->cabangTujuan->nama_cabang }}</span>
                                <span class="text-gray-500">{{ $distribusi->created_at->diffForHumans() }}</span>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500">Belum ada transaksi distribusi.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            @endif


            {{-- ================================================================= --}}
            {{-- TAMPILAN UNTUK ADMIN GUDANG CABANG --}}
            {{-- ================================================================= --}}
            @if(auth()->user()->role === 'admin_cabang')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Stat Card Penjualan Hari Ini -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Penjualan Hari Ini</h3>
                        <p class="text-3xl font-bold text-green-500">{{ 'Rp ' . number_format($penjualanHariIni, 0, ',', '.') }}</p>
                    </div>
                </div>
                 <!-- Stat Card Keuntungan Bulan Ini -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Keuntungan Bulan Ini</h3>
                        <p class="text-3xl font-bold text-blue-500">{{ 'Rp ' . number_format($keuntunganBulanIni, 0, ',', '.') }}</p>
                    </div>
                </div>
                 <!-- Stat Card Kiriman Menunggu -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Kiriman Menunggu</h3>
                        <p class="text-3xl font-bold text-yellow-500">{{ $kirimanMenunggu }}</p>
                    </div>
                </div>
            </div>

             <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Daftar Stok Hampir Habis -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-semibold mb-4">Stok Cabang Hampir Habis (Stok < 10)</h3>
                        <ul class="space-y-2">
                            @forelse($stokHampirHabis as $item)
                            <li class="flex justify-between items-center text-sm">
                                <span>{{ $item->nama_part }}</span>
                                <span class="font-bold text-red-500">{{ $item->pivot->stok }}</span>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500">Tidak ada stok yang hampir habis.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <!-- Daftar Penjualan Terbaru -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-semibold mb-4">5 Penjualan Terakhir</h3>
                        <ul class="space-y-2">
                            @forelse($penjualanTerbaru as $penjualan)
                            <li class="flex justify-between items-center text-sm">
                                <span><a href="{{ route('penjualan.show', $penjualan) }}" class="text-blue-500 hover:underline">{{ $penjualan->nomor_nota }}</a></span>
                                <span class="text-gray-500">{{ $penjualan->created_at->diffForHumans() }}</span>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500">Belum ada transaksi penjualan.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>