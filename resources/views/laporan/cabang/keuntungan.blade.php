<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Keuntungan Kotor
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Tombol Aksi Atas --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <a href="{{ route('laporan.cabang.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium mb-4 md:mb-0">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Pusat Laporan
                    </a>
                    <button id="export-pdf-btn"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-base font-medium text-white bg-red-600 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Export PDF
                    </button>
                </div>

                {{-- Filter Form --}}
                <form action="{{ route('laporan.cabang.keuntungan') }}" method="GET"
                      class="mb-8 bg-gray-50 dark:bg-gray-900/50 p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-1/3">
                        <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}"
                               class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>
                    <div class="w-full md:w-1/3">
                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}"
                               class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>
                    <button type="submit"
                            class="w-full md:w-auto inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition duration-150 ease-in-out">
                        Filter
                    </button>
                </form>

                {{-- Tabel Laporan --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="laporan-keuntungan-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">No. Nota</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Tanggal</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Total Penjualan</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Total HPP</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Keuntungan Kotor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @php $grandTotalHpp = 0; @endphp
                            @forelse($penjualans as $penjualan)
                                @php
                                    $totalHppPerNota = $penjualan->details->sum(function($detail) {
                                        return $detail->hpp_satuan * $detail->qty;
                                    });
                                    $grandTotalHpp += $totalHppPerNota;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $penjualan->nomor_nota }}</td>
                                    <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d M Y') }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">{{ 'Rp ' . number_format($penjualan->total_penjualan, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">{{ 'Rp ' . number_format($totalHppPerNota, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap font-bold">{{ 'Rp ' . number_format($penjualan->total_penjualan - $totalHppPerNota, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Tidak ada data penjualan dalam periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100 dark:bg-gray-900/50 font-bold text-gray-800 dark:text-gray-200">
                            <tr>
                                <td colspan="2" class="py-3 px-4 text-right uppercase text-xs">Total Periode Ini:</td>
                                <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($penjualans->sum('total_penjualan'), 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($grandTotalHpp, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right text-lg text-green-600 dark:text-green-400">
                                    {{ 'Rp ' . number_format($totalKeuntungan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- [DIUBAH] Script Export PDF yang Dipercantik --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script>
        document.getElementById('export-pdf-btn').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'pt', 'a4');

            const tanggalAwal = "{{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}";
            const tanggalAkhir = "{{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}";
            const namaCabang = "{{ auth()->user()->cabang->nama_cabang ?? 'Gudang Cabang' }}";
            const totalPenjualan = "{{ 'Rp ' . number_format($penjualans->sum('total_penjualan'), 0, ',', '.') }}";
            const grandTotalHpp = "{{ 'Rp ' . number_format($grandTotalHpp, 0, ',', '.') }}";
            const totalKeuntungan = "{{ 'Rp ' . number_format($totalKeuntungan, 0, ',', '.') }}";
            const isKeuntunganNegatif = {{ $totalKeuntungan < 0 ? 'true' : 'false' }};

            // Fungsi untuk Header dan Footer
            const addHeaderFooter = (data) => {
                const pageCount = doc.internal.getNumberOfPages();
                const pageWidth = doc.internal.pageSize.width;
                const pageHeight = doc.internal.pageSize.height;

                // Header
                doc.setFontSize(16);
                doc.setFont(undefined, 'bold');
                doc.text('Laporan Keuntungan Kotor', pageWidth / 2, 40, { align: 'center' });
                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                doc.text(`Cabang: ${namaCabang}`, pageWidth / 2, 55, { align: 'center' });
                doc.text(`Periode: ${tanggalAwal} s/d ${tanggalAkhir}`, pageWidth / 2, 68, { align: 'center' });

                // Footer
                doc.setFontSize(8);
                doc.text(`Dicetak pada: ${new Date().toLocaleString('id-ID')}`, 40, pageHeight - 20);
                doc.text(`Halaman ${data.pageNumber} dari ${pageCount}`, pageWidth - 40, pageHeight - 20, { align: 'right' });
            };

            // Tabel Ringkasan
            doc.autoTable({
                startY: 80,
                head: [['Ringkasan Periode', '']],
                body: [
                    ['Total Penjualan', totalPenjualan],
                    ['Total HPP', grandTotalHpp],
                    ['Total Keuntungan Kotor', { 
                        content: totalKeuntungan, 
                        styles: { textColor: isKeuntunganNegatif ? [192, 57, 43] : [22, 163, 74] } 
                    }],
                ],
                theme: 'grid',
                headStyles: { fillColor: [52, 73, 94], fontSize: 12 },
                bodyStyles: { fontStyle: 'bold' },
                columnStyles: { 1: { halign: 'right' } },
                didDrawPage: addHeaderFooter
            });

            // Tabel Rincian
            doc.autoTable({
                html: '#laporan-keuntungan-table',
                startY: doc.lastAutoTable.finalY + 20,
                showFoot: 'lastPage',
                theme: 'striped',
                headStyles: { fillColor: [41, 128, 185] }, // Warna biru
                footStyles: { fillColor: [230, 230, 230], textColor: [0, 0, 0], fontStyle: 'bold' },
                didDrawPage: addHeaderFooter
            });

            doc.save(`Laporan-Keuntungan-${namaCabang.replace(/\s+/g, '-')}-${tanggalAwal}.pdf`);
        });
    </script>
</x-app-layout>
