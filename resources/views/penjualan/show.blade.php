<x-app-layout>
    {{-- [START] Style khusus untuk print --}}
    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #nota-area, #nota-area * {
                visibility: visible;
            }

            #nota-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
    @endpush
    {{-- [END] Style print --}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-print">
            Nota Penjualan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div id="nota-area">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                        {{-- Header Nota --}}
                        <div class="flex justify-between items-start border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ $penjualan->cabang->nama_cabang }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $penjualan->cabang->alamat }}</p>
                            </div>
                            <div class="text-right">
                                <h4 class="text-xl font-semibold">NOTA PENJUALAN</h4>
                                <p class="text-sm">{{ $penjualan->nomor_nota }}</p>
                            </div>
                        </div>

                        {{-- Info Transaksi --}}
                        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                            <div>
                                <p><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d F Y') }}</p>
                                <p><span class="font-medium">Kasir:</span> {{ $penjualan->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p><span class="font-medium">Pelanggan:</span> {{ $penjualan->nama_pembeli }}</p>
                            </div>
                        </div>

                        {{-- Tabel Barang --}}
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="py-2 px-3 text-left">Nama Barang</th>
                                        <th class="py-2 px-3 text-center">Qty</th>
                                        <th class="py-2 px-3 text-right">Harga Satuan</th>
                                        <th class="py-2 px-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penjualan->details as $detail)
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="py-2 px-3">{{ $detail->sparepart->nama_part }}</td>
                                            <td class="py-2 px-3 text-center">{{ $detail->qty }}</td>
                                            <td class="py-2 px-3 text-right">{{ 'Rp ' . number_format($detail->harga_jual_satuan, 0, ',', '.') }}</td>
                                            <td class="py-2 px-3 text-right">{{ 'Rp ' . number_format($detail->qty * $detail->harga_jual_satuan, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Ringkasan Total --}}
                        <div class="flex justify-end">
                            <div class="w-full md:w-1/2 text-sm">
                                <div class="flex justify-between py-1">
                                    <span>Subtotal</span>
                                    <span>{{ 'Rp ' . number_format($penjualan->total_penjualan, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span>PPN (11%)</span>
                                    <span>{{ 'Rp ' . number_format($penjualan->total_ppn_penjualan, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-2 mt-2 border-t-2 border-gray-300 dark:border-gray-600 text-base font-bold">
                                    <span>Grand Total</span>
                                    <span>{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Terima Kasih --}}
                        <div class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400 italic">
                            <p>Terima kasih atas pembelian Anda.</p>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 text-center no-print">
                        <a href="{{ route('penjualan.index') }}"
                           class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-900 transition duration-150 ease-in-out mr-3">
                            &larr; Kembali ke Histori
                        </a>
                        <button onclick="window.print()"
                            class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            Cetak Nota
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
