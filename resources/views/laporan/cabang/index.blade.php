<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pusat Laporan - Gudang Cabang
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <h3 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">
                        Pilih Jenis Laporan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Kartu: Laporan Keuntungan Kotor -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl shadow-lg p-8 flex flex-col justify-between h-full">
                            <div>
                                <h4 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-100">Laporan Keuntungan Kotor</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Lihat total keuntungan penjualan berdasarkan periode yang Anda tentukan.
                                </p>
                            </div>
                            <a href="{{ route('laporan.cabang.keuntungan') }}"
                               class="mt-6 inline-flex items-center justify-center w-full px-6 py-3 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-150 ease-in-out">
                                Buka Laporan
                            </a>
                        </div>

                        <!-- Kartu: Laporan Cashflow -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl shadow-lg p-8 flex flex-col justify-between h-full">
                            <div>
                                <h4 class="text-xl font-semibold mb-3 text-gray-800 dark:text-gray-100">Laporan Cashflow</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Lihat total pemasukan dan pengeluaran cabang dalam rentang waktu tertentu.
                                </p>
                            </div>
                            <a href="{{ route('laporan.cabang.cashflow') }}"
                               class="mt-6 inline-flex items-center justify-center w-full px-6 py-3 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-150 ease-in-out">
                                Buka Laporan
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
