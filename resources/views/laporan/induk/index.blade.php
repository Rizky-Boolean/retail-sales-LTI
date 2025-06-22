<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pusat Laporan - Gudang Induk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Silakan Pilih Laporan yang Ingin Dilihat</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Kartu Laporan Stok Induk -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h4 class="font-bold text-lg mb-2">Laporan Stok Induk</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat daftar semua sparepart beserta jumlah stok terkini.</p>
                            <a href="{{ route('laporan.induk.stok') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Buka Laporan</a>
                        </div>

                        <!-- Kartu Rekap Pengeluaran (Diaktifkan) -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h4 class="font-bold text-lg mb-2">Rekap Pengeluaran</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat total pengeluaran pembelian dari supplier berdasarkan periode.</p>
                            <a href="{{ route('laporan.induk.pengeluaran') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Buka Laporan</a>
                        </div>
                        
                        <!-- Kartu Riwayat Stok Masuk (Diaktifkan) -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h4 class="font-bold text-lg mb-2">Riwayat Stok Masuk</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat histori semua transaksi pembelian dari supplier.</p>
                            <a href="{{ route('stok-masuk.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Buka Riwayat</a>
                        </div>
                        
                        <!-- Kartu Riwayat Distribusi (Diaktifkan) -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h4 class="font-bold text-lg mb-2">Riwayat Distribusi</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat histori semua transaksi pengiriman barang ke cabang.</p>
                            <a href="{{ route('distribusi.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Buka Riwayat</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
