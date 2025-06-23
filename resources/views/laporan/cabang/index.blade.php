<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pusat Laporan - Gudang Cabang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Silakan Pilih Laporan yang Ingin Dilihat</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Kartu Laporan Keuntungan -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h4 class="font-bold text-lg mb-2">Laporan Keuntungan Kotor</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat total keuntungan dari penjualan berdasarkan periode tanggal.</p>
                            <a href="{{ route('laporan.cabang.keuntungan') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buka Laporan
                            </a>
                        </div>

                        <!-- Kartu Laporan Cashflow (Diaktifkan) -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h4 class="font-bold text-lg mb-2">Laporan Cashflow</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Melihat total pemasukan dan pengeluaran cabang berdasarkan periode.</p>
                            <a href="{{ route('laporan.cabang.cashflow') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buka Laporan
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>