<x-app-layout>
    {{-- CSS Khusus untuk Print --}}
    @push('styles')
    <style>
        @media print {
            /* Sembunyikan semua elemen yang tidak perlu saat mencetak */
            body > div > nav, 
            body > div > main > header,
            .print-hide {
                display: none !important;
            }

            /* Atur ulang layout utama untuk pencetakan */
            body > div > main {
                padding: 0 !important;
            }

            /* Pastikan konten faktur menggunakan seluruh halaman */
            #invoice-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            
            /* Hapus background gelap dan sesuaikan warna teks agar hemat tinta */
            .dark\:bg-gray-800, .dark\:bg-gray-700\/50, .dark\:bg-gray-900\/50 {
                background-color: white !important;
            }
            .dark\:text-gray-100, .dark\:text-gray-200, .dark\:text-gray-300, .dark\:text-gray-400 {
                color: #1f2937 !important; /* gray-800 */
            }
            .dark\:border-gray-700, .dark\:border-gray-600 {
                border-color: #e5e7eb !important; /* gray-200 */
            }
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="flex justify-between items-center print-hide">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Detail Transaksi Stok Masuk') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tombol Aksi di Atas --}}
            <div class="flex justify-between items-center mb-6 print-hide">
                <a href="{{ route('stok-masuk.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ __('Kembali ke Histori') }}
                </a>
                
                {{-- Tombol Cetak Memanggil Fungsi Baru --}}
                <button onclick="printInvoice()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-base font-medium text-white bg-gray-600 rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out">

                    <span>Cetak Bukti</span>
                </button>
                </div>

            {{-- Kontainer Faktur --}}
            <div id="invoice-container" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-8 md:p-10">
                    {{-- Kop Surat / Header Faktur --}}
                    <div class="flex justify-between items-start pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <img src="{{ asset('images/logo lautan teduh.png') }}" alt="Logo Perusahaan" class="h-12 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">PT. LAUTAN TEDUH INTERNIAGA</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Jl. Ikan Tenggiri No.20, Pesawahan, Teluk Betung Selatan</p>
                        </div>
                        <div class="text-right">
                            <h2 class="text-2xl font-semibold uppercase text-gray-800 dark:text-gray-200">Bukti Stok Masuk</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <span class="font-semibold">ID Transaksi:</span> #TR-{{ $stokMasuk->id }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->isoFormat('D MMMM YYYY') }}
                            </p>
                        </div>
                    </div>

                    {{-- Info Supplier --}}
                    <div class="py-6">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400">DITERIMA DARI SUPPLIER:</h3>
                        <p class="text-base font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $stokMasuk->supplier->nama_supplier ?? '-' }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $stokMasuk->supplier->alamat ?? '' }}</p>
                    </div>

                    {{-- Tabel Rincian Barang --}}
                    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kode Part</th>
                                    <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                    <th class="text-center py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Qty</th>
                                    <th class="text-right py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Beli</th>
                                    <th class="text-right py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-300">
                                @foreach($stokMasuk->details as $detail)
                                    <tr>
                                        <td class="py-3 px-4 whitespace-nowrap font-bold" >{{ $detail->sparepart->kode_part ?? '-' }}</td>
                                        <td class="py-3 px-4 whitespace-nowrap font-bold">{{ $detail->sparepart->nama_part ?? '-' }}</td>
                                        <td class="py-3 px-4 text-center whitespace-nowrap font-bold">{{ $detail->qty }}</td>
                                        <td class="py-3 px-4 text-right whitespace-nowrap font-bold">{{ 'Rp ' . number_format($detail->harga_beli_satuan, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right whitespace-nowrap font-bold">{{ 'Rp ' . number_format($detail->harga_beli_satuan * $detail->qty, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Catatan dan Total --}}
                    <div class="flex flex-col md:flex-row justify-between mt-8">
                        <div class="w-full md:w-1/2">
                            @if($stokMasuk->catatan)
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Catatan:</h3>
                                <p class="mt-1 text-gray-700 dark:text-gray-300 text-sm italic">{{ $stokMasuk->catatan }}</p>
                            @endif
                        </div>
                        <div class="w-full md:w-2/5 mt-6 md:mt-0">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Total Pembelian</span>
                                    <span class="text-gray-700 dark:text-gray-200">{{ 'Rp ' . number_format($stokMasuk->total_pembelian, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">PPN Supplier (11%)</span>
                                    <span class="text-gray-700 dark:text-gray-200">{{ 'Rp ' . number_format($stokMasuk->total_ppn_supplier, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-600 pt-3 mt-3">
                                    <span class="text-gray-800 dark:text-gray-100">Total Final</span>
                                    <span class="text-gray-800 dark:text-gray-600">{{ 'Rp ' . number_format($stokMasuk->total_final, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Faktur --}}
                    <div class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>Dicatat oleh: <span class="font-semibold">{{ $stokMasuk->user->name ?? '-' }}</span></p>
                        <p class="mt-1">-- Terima kasih --</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Print --}}
    <script>
        // Simpan judul asli halaman
        const originalTitle = document.title;

        function printInvoice() {
            // Buat judul baru untuk nama file PDF
            const newTitle = `Bukti Stok Masuk - TR-{{ $stokMasuk->id }}`;
            document.title = newTitle;
            
            // Panggil fungsi print bawaan browser
            window.print();
        }

        // Kembalikan judul ke asli setelah selesai mencetak
        window.addEventListener('afterprint', () => {
            document.title = originalTitle;
        });
    </script>
</x-app-layout>