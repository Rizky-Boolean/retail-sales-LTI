<x-app-layout>
    {{-- Style khusus untuk print --}}
    @push('styles')
    <style>
        @media print {
            body > div > nav, 
            body > div > main > header,
            .print-hide {
                display: none !important;
            }
            body > div > main {
                padding: 0 !important;
            }
            #nota-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            .dark\:bg-gray-800 { background-color: white !important; }
            .dark\:text-gray-100, .dark\:text-gray-200, .dark\:text-gray-300, .dark\:text-gray-400 { color: #1f2937 !important; }
            .dark\:border-gray-700, .dark\:border-gray-600 { border-color: #e5e7eb !important; }
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="flex justify-between items-center print-hide">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detail Nota Penjualan
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        
            {{-- [DIPINDAHKAN & DIPERBAIKI] Tombol Aksi di Atas --}}
            <div class="flex justify-between items-center mb-6 print-hide">
                {{-- Grup Tombol Kiri --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('penjualan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ __('Kembali ke Histori') }}
                    </a>
                    <button type="button" onclick="showCancelModal('{{ route('penjualan.destroy', $penjualan->id) }}')" class="inline-flex items-center text-red-600 dark:text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium transition-colors">
                        <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                        <span>Batalkan Transaksi</span>
                    </button>
                </div>

                {{-- Tombol Kanan --}}
                <div class="flex items-center">
                    <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-base font-medium text-white bg-gray-600 rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <span>Cetak Nota</span>
                    </button>
                </div>
            </div>
            
            {{-- Kontainer Nota --}}
            <div id="nota-container" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-8 md:p-10">
                    {{-- Header Nota --}}
                    <div class="flex justify-between items-start pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <img src="{{ asset('images/logo lautan teduh.png') }}" alt="Logo Perusahaan" class="h-12 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $penjualan->cabang->nama_cabang }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $penjualan->cabang->alamat }}</p>
                        </div>
                        <div class="text-right">
                            <h2 class="text-2xl font-semibold uppercase text-gray-800 dark:text-gray-200">Nota Penjualan</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <span class="font-semibold">No. Nota:</span> {{ $penjualan->nomor_nota }}
                            </p>
                        </div>
                    </div>

                    {{-- Info Transaksi --}}
                    <div class="grid grid-cols-2 gap-4 my-6 text-sm">
                        <div>
                            <p><span class="font-semibold text-gray-500 dark:text-gray-400">Tanggal:</span> {{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->isoFormat('D MMMM YYYY') }}</p>
                            <p><span class="font-semibold text-gray-500 dark:text-gray-400">Kasir:</span> {{ $penjualan->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p><span class="font-semibold text-gray-500 dark:text-gray-400">Pelanggan:</span> {{ $penjualan->nama_pembeli }}</p>
                        </div>
                    </div>

                    {{-- Tabel Barang --}}
                    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-200 dark:bg-gray-700/50">
                                <tr>
                                    <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Barang</th>
                                    <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Qty</th>
                                    <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Satuan</th>
                                    <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($penjualan->details as $detail)
                                    <tr class="text-gray-700 dark:text-gray-300">
                                        <td class="py-2 px-4">{{ $detail->sparepart->nama_part }}</td>
                                        <td class="py-2 px-4 text-center">{{ $detail->qty }}</td>
                                        <td class="py-2 px-4 text-right">{{ 'Rp ' . number_format($detail->harga_jual_satuan, 0, ',', '.') }}</td>
                                        <td class="py-2 px-4 text-right">{{ 'Rp ' . number_format($detail->qty * $detail->harga_jual_satuan, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Ringkasan Total --}}
                    <div class="flex justify-end mt-6">
                        <div class="w-full md:w-2/5 space-y-2 text-sm">
                            <div class="flex justify-between py-1 text-gray-600 dark:text-gray-300">
                                <span>Subtotal</span>
                                <span>{{ 'Rp ' . number_format($penjualan->total_penjualan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-1 text-gray-600 dark:text-gray-300">
                                <span>PPN (11%)</span>
                                <span>{{ 'Rp ' . number_format($penjualan->total_ppn_penjualan, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-2 mt-2 border-t-2 border-gray-300 dark:border-gray-600 text-base font-bold text-gray-900 dark:text-gray-100">
                                <span>Grand Total</span>
                                <span>{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Footer Terima Kasih --}}
                    <div class="mt-12 text-center text-xs text-gray-500 dark:text-gray-400 italic">
                        <p>-- Terima kasih atas pembelian Anda --</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Pembatalan --}}
    <div id="cancelModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto border border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-800 dark:text-gray-300">Anda yakin ingin membatalkan transaksi ini? Stok akan dikembalikan.</h3>
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="hideCancelModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition duration-150 ease-in-out">
                        Batal
                    </button>
                    <form id="cancelForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showCancelModal(actionUrl) {
            const cancelForm = document.getElementById('cancelForm');
            cancelForm.action = actionUrl;
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
