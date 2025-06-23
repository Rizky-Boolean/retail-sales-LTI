<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Distribusi #DIST-') . $distribusi->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- [START] PERBAIKAN TOMBOL KEMBALI --}}
                    <div class="mb-6">
                        @if(auth()->user()->role === 'admin_induk')
                            {{-- Jika Admin Induk, kembali ke histori distribusi --}}
                            <a href="{{ route('distribusi.index') }}" class="text-blue-500 hover:text-blue-700">
                                &larr; Kembali ke Histori Distribusi
                            </a>
                        @elseif(auth()->user()->role === 'admin_cabang')
                             {{-- Jika Admin Cabang, tombol ini akan menutup tab detail --}}
                            <button onclick="window.close()" class="text-blue-500 hover:text-blue-700">
                                &larr; Kembali ke Halaman Penerimaan (Tutup Tab)
                            </button>
                        @endif
                    </div>
                    {{-- [END] PERBAIKAN TOMBOL KEMBALI --}}


                    <!-- Informasi Header Transaksi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                        <div>
                            <h3 class="font-bold">Tanggal Kirim</h3>
                            <p>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <h3 class="font-bold">Cabang Tujuan</h3>
                            <p>{{ $distribusi->cabangTujuan->nama_cabang ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="font-bold">Dikirim Oleh</h3>
                            <p>{{ $distribusi->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Detail Item -->
                    <h3 class="text-lg font-bold mb-2">Rincian Barang Terkirim</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Part</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Qty</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Harga Modal</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Harga Kirim (+PPN)</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Subtotal Kirim</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @foreach($distribusi->details as $detail)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="text-left py-3 px-4">{{ $detail->sparepart->nama_part ?? 'N/A' }}</td>
                                    <td class="text-center py-3 px-4">{{ $detail->qty }}</td>
                                    <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($detail->harga_modal_satuan, 0, ',', '.') }}</td>
                                    <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($detail->harga_kirim_satuan, 0, ',', '.') }}</td>
                                    <td class="text-right py-3 px-4 font-semibold">{{ 'Rp ' . number_format($detail->harga_kirim_satuan * $detail->qty, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Rangkuman Total -->
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 py-2">
                                <span class="font-semibold">Total Harga Modal</span>
                                <span>{{ 'Rp ' . number_format($distribusi->total_harga_modal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 py-2">
                                <span class="font-semibold">Total PPN Distribusi (11%)</span>
                                <span>{{ 'Rp ' . number_format($distribusi->total_ppn_distribusi, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-2 text-lg font-bold">
                                <span>Total Harga Kirim</span>
                                <span>{{ 'Rp ' . number_format($distribusi->total_harga_kirim, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
