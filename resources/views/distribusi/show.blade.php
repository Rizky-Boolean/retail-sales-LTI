<x-app-layout>
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
                    {{ __('Detail Distribusi') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tombol Aksi di Atas --}}
            <div class="flex justify-between items-center mb-6 print-hide">
                {{-- [FIXED] Logika tombol kembali dipisah berdasarkan peran --}}
                @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang_induk']))
                    <a href="{{ route('distribusi.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ __('Kembali ke Histori Distribusi') }}
                    </a>
                @elseif(auth()->user()->role === 'admin_gudang_cabang')
                    <a href="{{ route('cabang.penerimaan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ __('Kembali ke Kiriman Masuk') }}
                    </a>
                @endif

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
                            <h2 class="text-2xl font-semibold uppercase text-gray-800 dark:text-gray-200">Bukti Distribusi</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <span class="font-semibold">ID Distribusi:</span> #DIST-{{ $distribusi->id }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->isoFormat('D MMMM YYYY') }}
                            </p>
                        </div>
                    </div>

                    {{-- Info Pengirim & Penerima --}}
                    <div class="grid grid-cols-2 gap-6 py-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400">DIKIRIM DARI:</h3>
                            <p class="text-base font-bold text-gray-800 dark:text-gray-200 mt-1">Gudang Induk</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">PT. Lautan Teduh Interniaga</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400">DIKIRIM KE:</h3>
                            <p class="text-base font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $distribusi->cabangTujuan->nama_cabang ?? '-' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $distribusi->cabangTujuan->alamat ?? '' }}</p>
                        </div>
                    </div>

                    {{-- Tabel Rincian Barang --}}
                    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full">
                            <thead class="bg-gray-200 dark:bg-gray-700/50">
                                <tr>
                                    <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                    <th class="text-center py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Qty</th>
                                    <th class="text-right py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Kirim</th>
                                    <th class="text-right py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                                @foreach($distribusi->details as $detail)
                                    <tr>
                                        <td class="py-3 px-4 whitespace-nowrap font-bold">{{ $detail->sparepart->nama_part ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-center whitespace-nowrap font-bold">{{ $detail->qty }}</td>
                                        <td class="py-3 px-4 text-right whitespace-nowrap font-bold">{{ 'Rp ' . number_format($detail->harga_kirim_satuan, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right whitespace-nowrap font-bold">{{ 'Rp ' . number_format($detail->harga_kirim_satuan * $detail->qty, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                             <tfoot class="bg-gray-100 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="3" class="text-right py-3.5 px-4 font-bold uppercase text-sm text-gray-800 dark:text-gray-200">Total Nilai Barang</td>
                                    <td class="text-right py-3.5 px-4 font-bold text-sm text-gray-800 dark:text-gray-800">{{ 'Rp ' . number_format($distribusi->total_harga_kirim, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Tanda Tangan --}}
                    <div class="flex justify-between mt-16">
                        <div class="text-center w-1/2">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Disiapkan Oleh,</p>
                            <p class="mt-16 text-gray-800 dark:text-gray-200">____________________</p>
                            {{-- Nama diganti dengan titik-titik untuk diisi manual --}}
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1">( .............................. )</p>
                        </div>
                        <div class="text-center w-1/2">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Diterima Oleh,</p>
                            <p class="mt-16 text-gray-800 dark:text-gray-200">____________________</p>
                            {{-- Anda bisa melakukan hal yang sama untuk penerima jika perlu --}}
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 mt-1">( .............................. )</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Print (Diambil dari contoh pertama Anda) --}}
    {{-- Hapus @push('scripts') yang lama dan ganti dengan ini --}}
    <script>
        // Simpan judul asli halaman
        const originalTitle = document.title;

        function printInvoice() {
            // Buat judul baru untuk nama file PDF, sesuaikan dengan ID distribusi
            const newTitle = `Bukti Distribusi - DIST-{{ $distribusi->id }}`;
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