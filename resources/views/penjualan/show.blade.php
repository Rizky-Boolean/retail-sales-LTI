<x-app-layout>
    {{-- [START] Tambahkan style khusus untuk print --}}
    @push('styles')
    <style>
        @media print {
            /* Sembunyikan semua elemen di body secara default */
            body * {
                visibility: hidden;
            }

            /* Tampilkan hanya area nota dan semua elemen di dalamnya */
            #nota-area, #nota-area * {
                visibility: visible;
            }

            /* Atur agar area nota memenuhi halaman */
            #nota-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            
            /* Sembunyikan tombol aksi saat mencetak */
            .no-print {
                display: none;
            }
        }
    </style>
    @endpush
    {{-- [END] Tambahkan style khusus untuk print --}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight no-print">
            {{ __('Nota Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- [START] Bungkus seluruh area nota dengan div baru --}}
            <div id="nota-area">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100" id="nota-content">
                        
                        <div class="flex justify-between items-start border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $penjualan->cabang->nama_cabang }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $penjualan->cabang->alamat }}</p>
                            </div>
                            <div class="text-right">
                                <h4 class="text-xl font-semibold">NOTA PENJUALAN</h4>
                                <p class="text-sm">{{ $penjualan->nomor_nota }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                            <div>
                                <p><strong class="font-semibold">Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d F Y') }}</p>
                                <p><strong class="font-semibold">Kasir:</strong> {{ $penjualan->user->name }}</p>
                            </div>
                            <div class="text-right">
                                <p><strong class="font-semibold">Pelanggan:</strong> {{ $penjualan->nama_pembeli }}</p>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="py-2 px-3 text-left font-semibold">Nama Barang</th>
                                        <th class="py-2 px-3 text-center font-semibold">Qty</th>
                                        <th class="py-2 px-3 text-right font-semibold">Harga Satuan</th>
                                        <th class="py-2 px-3 text-right font-semibold">Subtotal</th>
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
                                <div class="flex justify-between py-2 mt-2 border-t-2 border-gray-300 dark:border-gray-600 font-bold text-base">
                                    <span>GRAND TOTAL</span>
                                    <span>{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                            <p>Terima kasih telah berbelanja.</p>
                        </div>

                    </div>
                     <div class="p-6 border-t border-gray-200 dark:border-gray-700 text-center no-print">
                        <a href="{{ route('penjualan.index') }}" class="text-blue-500 hover:text-blue-700 mr-4">&larr; Kembali ke Histori</a>
                        <button onclick="window.print()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Cetak Nota</button>
                    </div>
                </div>
            </div>
             {{-- [END] Penutup div pembungkus --}}
        </div>
    </div>
</x-app-layout>
