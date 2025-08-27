<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pusat Laporan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-10">

                {{-- Kategori 1: Laporan Inventaris & Keuangan --}}
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2 border-b pb-2 dark:border-gray-600">
                        Laporan Inventaris & Keuangan
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Laporan yang berfokus pada rekapitulasi data stok dan finansial.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        {{-- Card: Laporan Stok Induk --}}
                        <a href="{{ route('laporan.induk.stok') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-lg">
                                    <i data-lucide="boxes" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200">Laporan Stok Induk</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Melihat daftar semua sparepart beserta jumlah stok terkini.</p>
                        </a>

                        {{-- Card: Rekap Pengeluaran --}}
                        <a href="{{ route('laporan.induk.pengeluaran') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="bg-red-100 dark:bg-red-900/50 p-3 rounded-lg">
                                    <i data-lucide="trending-down" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                                </div>
                                <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200">Rekap Pengeluaran</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total pengeluaran pembelian dari supplier berdasarkan periode.</p>
                        </a>

                        {{-- Card: Laporan Penjualan Cabang --}}
                        <a href="{{ route('laporan.induk.penjualan') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-lg">
                                    <i data-lucide="trending-up" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                                </div>
                                <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200">Laporan Penjualan</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Melihat rekapitulasi penjualan dari semua cabang.</p>
                        </a>
                        
                        {{-- [MODIFIKASI] Card: Laporan Cash Flow (Hanya untuk Super Admin) --}}
                        @if(auth()->user()->role == 'super_admin')
                        <a href="{{ route('laporan.induk.cashflow') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="bg-indigo-100 dark:bg-indigo-900/50 p-3 rounded-lg">
                                    <i data-lucide="scale" class="w-6 h-6 text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200">Laporan Cash Flow</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Membandingkan total pemasukan dan pengeluaran perusahaan.</p>
                        </a>
                        @endif
                        
                    </div>
                </div>

                {{-- Kategori 2: Riwayat Transaksi --}}
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2 border-b pb-2 dark:border-gray-600">
                        Riwayat Transaksi
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Laporan untuk melihat detail histori dari setiap transaksi.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        {{-- Card: Riwayat Stok Masuk --}}
                        <a href="{{ route('stok-masuk.index') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-lg">
                                    <i data-lucide="archive" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200">Riwayat Stok Masuk</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Melihat histori semua transaksi pembelian dari supplier.</p>
                        </a>

                        {{-- Card: Riwayat Distribusi --}}
                        <a href="{{ route('distribusi.index') }}" class="block bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-lg">
                                    <i data-lucide="truck" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                                <h4 class="font-bold text-lg text-gray-800 dark:text-gray-200">Riwayat Distribusi</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Melihat histori semua transaksi pengiriman barang ke cabang.</p>
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
    @endpush
</x-app-layout>