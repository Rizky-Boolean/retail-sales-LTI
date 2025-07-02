<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Transaksi Stok Masuk #TR-') . $stokMasuk->id }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100 space-y-8">

                    {{-- Tombol Kembali --}}
                    <div>
                        <a href="{{ route('stok-masuk.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium">
                            &larr; Kembali ke Histori
                        </a>
                    </div>

                    {{-- Informasi Utama Transaksi --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-b pb-6">
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Tanggal Masuk</h3>
                            <p class="text-base">{{ \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Supplier</h3>
                            <p class="text-base">{{ $stokMasuk->supplier->nama_supplier ?? '-' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Dicatat Oleh</h3>
                            <p class="text-base">{{ $stokMasuk->user->name ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Rincian Barang --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Rincian Barang</h3>
                        <div class="overflow-x-auto rounded-md border border-gray-300 dark:border-gray-700">
                            <table class="min-w-full bg-white dark:bg-gray-900">
                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs uppercase font-bold">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Kode Part</th>
                                        <th class="py-3 px-4 text-left">Nama Part</th>
                                        <th class="py-3 px-4 text-center">Qty</th>
                                        <th class="py-3 px-4 text-right">Harga Beli</th>
                                        <th class="py-3 px-4 text-right">Harga Modal Akhir</th>
                                        <th class="py-3 px-4 text-right">Subtotal Modal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    @foreach($stokMasuk->details as $detail)
                                        <tr>
                                            <td class="py-3 px-4 font-medium">{{ $detail->sparepart->kode_part ?? '-' }}</td>
                                            <td class="py-3 px-4 font-medium">{{ $detail->sparepart->nama_part ?? '-' }}</td>
                                            <td class="py-3 px-4 text-center font-medium">{{ $detail->qty }}</td>
                                            <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($detail->harga_beli_satuan, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4 text-right font-medium">{{ 'Rp ' . number_format($detail->harga_modal_satuan, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($detail->harga_modal_satuan * $detail->qty, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Ringkasan Total --}}
                    <div class="w-full md:w-1/3 ml-auto space-y-2">
                        <div class="flex justify-between border-b py-2 text-sm font-medium">
                            <span>Total Pembelian</span>
                            <span>{{ 'Rp ' . number_format($stokMasuk->total_pembelian, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2 text-sm font-medium">
                            <span>PPN Supplier (11%)</span>
                            <span>{{ 'Rp ' . number_format($stokMasuk->total_ppn_supplier, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-2 text-base font-bold text-gray-800 dark:text-gray-100">
                            <span>Total Final</span>
                            <span>{{ 'Rp ' . number_format($stokMasuk->total_final, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    @if($stokMasuk->catatan)
                        <div class="border-t pt-4">
                            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Catatan:</h3>
                            <p class="mt-1 text-gray-700 dark:text-gray-300 text-sm">{{ $stokMasuk->catatan }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
