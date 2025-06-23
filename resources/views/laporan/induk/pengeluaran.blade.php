<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Rekap Pengeluaran') }}
            </h2>
            <button onclick="window.print()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Cetak Laporan
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-4">
                        <a href="{{ route('laporan.induk.index') }}" class="text-blue-500 hover:text-blue-700">
                            &larr; Kembali ke Pusat Laporan
                        </a>
                    </div>
                    
                    <!-- Form Filter Tanggal -->
                    <form action="{{ route('laporan.induk.pengeluaran') }}" method="GET" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-end space-x-4">
                        <div>
                            <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <div>
                            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                    </form>

                    <!-- Tabel Laporan -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">ID Transaksi</th>
                                    <th class="py-3 px-4 text-left">Tanggal</th>
                                    <th class="py-3 px-4 text-left">Supplier</th>
                                    <th class="py-3 px-4 text-right">Total Pembelian</th>
                                    <th class="py-3 px-4 text-right">PPN</th>
                                    <th class="py-3 px-4 text-right">Total Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengeluarans as $trx)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">TR-{{ $trx->id }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($trx->tanggal_masuk)->format('d M Y') }}</td>
                                        <td class="py-3 px-4">{{ $trx->supplier->nama_supplier }}</td>
                                        <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($trx->total_pembelian, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($trx->total_ppn_supplier, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right font-semibold">{{ 'Rp ' . number_format($trx->total_final, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">Tidak ada data pembelian pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="font-bold bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <td colspan="5" class="py-3 px-4 text-right text-lg">Total Pengeluaran Periode Ini:</td>
                                    <td class="py-3 px-4 text-right text-lg text-red-600 dark:text-red-400">{{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
