<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pusat Laporan - Gudang Induk') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-6">Silahkan Pilih Laporan yang Ingin Dilihat</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Baris 1 --}}
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md p-6 hover:shadow-lg transition duration-150 ease-in-out">
                        <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-2">Laporan Stok Induk</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat daftar semua sparepart beserta jumlah stok terkini.</p>
                        <a href="{{ route('laporan.induk.stok') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buka Laporan
                        </a>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md p-6 hover:shadow-lg transition duration-150 ease-in-out">
                        <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-2">Rekap Pengeluaran</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat total pengeluaran pembelian dari supplier berdasarkan periode.</p>
                        <a href="{{ route('laporan.induk.pengeluaran') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buka Laporan
                        </a>
                    </div>

                    {{-- Baris 2 --}}
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md p-6 hover:shadow-lg transition duration-150 ease-in-out">
                        <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-2">Riwayat Stok Masuk</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat histori semua transaksi pembelian dari supplier.</p>
                        <a href="{{ route('stok-masuk.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buka Riwayat
                        </a>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md p-6 hover:shadow-lg transition duration-150 ease-in-out">
                        <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200 mb-2">Riwayat Distribusi</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat histori semua transaksi pengiriman barang ke cabang.</p>
                        <a href="{{ route('distribusi.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buka Riwayat
                        </a>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                        <h4 class="font-bold text-lg mb-2">Laporan Penjualan Cabang</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat rekapitulasi penjualan dari semua cabang.</p>
                        <a href="{{ route('laporan.induk.penjualan') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buka Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
