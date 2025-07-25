<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pusat Laporan - Gudang Cabang
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Header Section --}}
                <div class="mb-8 text-center">
                    <h3 class="text-3xl font-bold text-gray-800 dark:text-white">
                        Pusat Laporan Cabang
                    </h3>
                    <p class="text-md text-gray-500 dark:text-gray-400 mt-2">
                        Pilih jenis laporan yang ingin Anda lihat atau cetak.
                    </p>
                </div>

                {{-- Report Cards Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Kartu: Laporan Keuntungan Kotor -->
                    <div class="group bg-gray-50 dark:bg-gray-800/50 rounded-xl shadow-lg p-8 flex flex-col h-full border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-600 transition-all duration-300">
                        <div class="flex-grow">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="p-3 bg-green-100 dark:bg-green-900/50 rounded-full">
                                    <svg class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Laporan Keuntungan Kotor</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                Analisis total keuntungan kotor dari penjualan sparepart berdasarkan periode yang Anda tentukan. Laporan ini membantu mengukur profitabilitas penjualan Anda.
                            </p>
                        </div>
                        <a href="{{ route('laporan.cabang.keuntungan') }}"
                           class="mt-8 inline-flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-transform duration-200 group-hover:scale-105">
                            Buka Laporan
                        </a>
                    </div>

                    <!-- Kartu: Laporan Cashflow -->
                    <div class="group bg-gray-50 dark:bg-gray-800/50 rounded-xl shadow-lg p-8 flex flex-col h-full border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-600 transition-all duration-300">
                        <div class="flex-grow">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="p-3 bg-purple-100 dark:bg-purple-900/50 rounded-full">
                                    <svg class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Laporan Cashflow</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                Dapatkan rincian arus kas masuk (pemasukan) dari penjualan dan arus kas keluar (pengeluaran) dalam rentang waktu yang dipilih.
                            </p>
                        </div>
                        <a href="{{ route('laporan.cabang.cashflow') }}"
                           class="mt-8 inline-flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-transform duration-200 group-hover:scale-105">
                            Buka Laporan
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
