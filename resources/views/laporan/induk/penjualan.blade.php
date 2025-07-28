<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Penjualan Semua Cabang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- [PERBAIKAN] Bar Aksi Baru --}}
                <div class="flex justify-between items-center mb-6 print-hide">
                    <a href="{{ route('laporan.induk.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ __('Kembali ke Pusat Laporan') }}
                    </a>
                    <button id="export-pdf-btn" class="inline-flex items-center justify-center px-5 py-2.5 text-base font-medium text-white bg-gray-600 rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out">
                        <span>Export ke PDF</span>
                    </button>
                </div>
                
                {{-- [PERBAIKAN] Form Filter Baru --}}
                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border dark:border-gray-700 mb-8">
                    <form action="{{ route('laporan.induk.penjualan') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg shadow-sm">
                            </div>
                            <div>
                                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg shadow-sm">
                            </div>
                            <div>
                                <label for="cabang_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cabang</label>
                                <select name="cabang_id" id="cabang_id" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg shadow-sm">
                                    <option value="">Semua Cabang</option>
                                    @foreach($cabangs as $cabang)
                                        <option value="{{ $cabang->id }}" {{ $cabangId == $cabang->id ? 'selected' : '' }}>
                                            {{ $cabang->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 text-base font-medium text-white bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 transition">
                                <i data-lucide="filter" class="w-5 h-5 mr-2"></i>
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabel Laporan --}}
                <div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <table id="laporan-penjualan-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700/50">
                            <tr>
                                {{-- [PERBAIKAN] Gaya Header Tabel Baru --}}
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">No. Nota</th>
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Tanggal</th>
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Cabang</th>
                                <th class="text-left py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kasir</th>
                                <th class="text-right py-3.5 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
                            @forelse($penjualans as $penjualan)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $penjualan->nomor_nota }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->isoFormat('D MMM YYYY') }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $penjualan->cabang->nama_cabang }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $penjualan->user->name }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap text-right font-semibold">{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="search-x" class="w-12 h-12 mb-2"></i>
                                            <span class="text-lg">Tidak ada data penjualan pada periode ini.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($penjualans->isNotEmpty())
                        <tfoot class="font-bold bg-gray-100 dark:bg-gray-900/50">
                            <tr>
                                <td colspan="4" class="py-3 px-4 text-right text-lg text-gray-800 dark:text-gray-200">Total Penjualan Periode Ini:</td>
                                <td class="py-3 px-4 text-right text-lg text-green-600 dark:text-green-400">{{ 'Rp ' . number_format($totalPenjualan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    {{-- Script Lucide Icons untuk tombol filter --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      lucide.createIcons();
    </script>

   {{-- Script Export PDF --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportButton = document.getElementById('export-pdf-btn');
            if (exportButton) {
                exportButton.addEventListener('click', function () {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('p', 'pt', 'a4');
                    const pageHeight = doc.internal.pageSize.height;

                    const tanggalAwal = "{{ \Carbon\Carbon::parse($tanggalAwal)->isoFormat('D MMMM YYYY') }}";
                    const tanggalAkhir = "{{ \Carbon\Carbon::parse($tanggalAkhir)->isoFormat('D MMMM YYYY') }}";
                    const cabangSelect = document.getElementById('cabang_id');
                    const cabangFilter = cabangSelect.options[cabangSelect.selectedIndex].text;

                    doc.setProperties({
                        title: `Laporan Penjualan - ${tanggalAwal} - ${tanggalAkhir}`,
                    });

                    // --- FUNGSI UNTUK HEADER & FOOTER ---
                    const addHeaderFooter = () => {
                        // Header
                        doc.setFont('helvetica', 'bold');
                        doc.setFontSize(16);
                        doc.text('PT. LAUTAN TEDUH INTERNIAGA', 40, 50);
                        
                        doc.setFont('helvetica', 'normal');
                        doc.setFontSize(10);
                        doc.text('Laporan Rekapitulasi Penjualan', 40, 65);
                        doc.text(`Periode: ${tanggalAwal} s/d ${tanggalAkhir}`, 40, 80);
                        doc.text(`Cabang: ${cabangFilter}`, 40, 95);

                        // Footer
                        let pageCount = doc.internal.getNumberOfPages();
                        doc.setFontSize(8);
                        for(var i = 1; i <= pageCount; i++) {
                            doc.setPage(i);
                            doc.text('Halaman ' + String(i) + ' dari ' + String(pageCount), doc.internal.pageSize.width - 100, pageHeight - 10);
                        }
                    };

                    // --- TABEL ---
                    doc.autoTable({
                        html: '#laporan-penjualan-table',
                        startY: 110,
                        theme: 'striped',
                        headStyles: { 
                            fillColor: [30, 58, 138], // Warna biru tua
                            textColor: 255,
                            halign: 'center'
                        },
                        footStyles: {
                            fillColor: [221, 221, 221], // Warna abu-abu muda
                            textColor: [0, 0, 0],
                            fontStyle: 'bold',
                        },
                        styles: { fontSize: 8 },
                        columnStyles: {
                            0: { halign: 'left' },
                            1: { halign: 'left' },
                            2: { halign: 'left' },
                            3: { halign: 'left' },
                            4: { halign: 'right' }
                        },
                        didDrawPage: function (data) {
                            // Menambahkan header dan footer setelah halaman digambar
                            addHeaderFooter();
                        }
                    });
                    
                    doc.save(`laporan-penjualan-${cabangFilter}-${tanggalAwal}.pdf`);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
