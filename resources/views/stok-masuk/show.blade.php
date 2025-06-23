<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Transaksi Stok Masuk #TR-') . $stokMasuk->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Tombol Kembali -->
                    <div class="mb-6">
                        <a href="{{ route('stok-masuk.index') }}" class="text-blue-500 hover:text-blue-700">
                            &larr; Kembali ke Histori
                        </a>
                    </div>

                    <!-- Informasi Header Transaksi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 border-b pb-4">
                        <div>
                            <h3 class="font-bold">Tanggal Masuk</h3>
                            <p>{{ \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <h3 class="font-bold">Nama Supplier</h3>
                            <p>{{ $stokMasuk->supplier->nama_supplier ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="font-bold">Dicatat Oleh</h3>
                            <p>{{ $stokMasuk->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Detail Item -->
                    <h3 class="text-lg font-bold mb-2">Rincian Barang</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kode Part</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Part</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Qty</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Harga Beli</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Harga Modal Akhir</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Subtotal Modal</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @foreach($stokMasuk->details as $detail)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="text-left py-3 px-4">{{ $detail->sparepart->kode_part ?? 'N/A' }}</td>
                                    <td class="text-left py-3 px-4">{{ $detail->sparepart->nama_part ?? 'N/A' }}</td>
                                    <td class="text-center py-3 px-4">{{ $detail->qty }}</td>
                                    <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($detail->harga_beli_satuan, 0, ',', '.') }}</td>
                                    <td class="text-right py-3 px-4 font-semibold">{{ 'Rp ' . number_format($detail->harga_modal_satuan, 0, ',', '.') }}</td>
                                    <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($detail->harga_modal_satuan * $detail->qty, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Rangkuman Total -->
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between border-b py-2">
                                <span class="font-semibold">Total Pembelian</span>
                                <span>{{ 'Rp ' . number_format($stokMasuk->total_pembelian, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-b py-2">
                                <span class="font-semibold">PPN dari Supplier (11%)</span>
                                <span>{{ 'Rp ' . number_format($stokMasuk->total_ppn_supplier, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-2 text-lg font-bold">
                                <span>Total Final</span>
                                <span>{{ 'Rp ' . number_format($stokMasuk->total_final, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    @if($stokMasuk->catatan)
                        <div class="mt-6 border-t pt-4">
                            <h3 class="font-bold">Catatan:</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ $stokMasuk->catatan }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
