<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Stok Gudang Induk') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-2xl p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Bar Aksi --}}
                <div class="flex justify-between items-center mb-6 print-hide">
                    <a href="{{ route('laporan.induk.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ __('Kembali ke Pusat Laporan') }}
                    </a>
                    
                    <button id="export-pdf" class="inline-flex items-center justify-center px-5 py-2.5 text-base font-medium text-white bg-gray-600 rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out">
                        <span>Export ke PDF</span>
                    </button>
                </div>

                {{-- Tabel Laporan Stok --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="laporan-stok-table" class="w-full min-w-full">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kode Part</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Stok Saat Ini</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Modal Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $sparepart->kode_part }}</td>
                                    <td class="py-3 px-4">{{ $sparepart->nama_part }}</td>
                                    <td class="py-3 px-4 text-center">{{ $sparepart->stok_induk }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($sparepart->harga_modal_terakhir, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data sparepart.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Script untuk Export PDF --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exportButton = document.getElementById('export-pdf');
            if (exportButton) {
                exportButton.addEventListener('click', function () {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('p', 'pt', 'a4');
                    const pageHeight = doc.internal.pageSize.height;

                    doc.setProperties({
                        title: 'Laporan Stok Gudang Induk - {{ date("d-m-Y") }}',
                        author: 'YamahaPartsLog',
                    });

                    // --- HEADER ---
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(16);
                    doc.text('PT. LAUTAN TEDUH INTERNIAGA', 40, 50);
                    
                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(10);
                    doc.text('Laporan Stok Gudang Induk', 40, 65);
                    doc.text(`Dicetak pada: ${new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}`, 40, 80);

                    // --- TABEL ---
                    doc.autoTable({
                        html: '#laporan-stok-table',
                        startY: 100,
                        theme: 'striped',
                        headStyles: { 
                            fillColor: [30, 58, 138],
                            textColor: 255,
                            halign: 'left'
                        },
                        styles: { 
                            fontSize: 9 
                        },
                        columnStyles: {
                            0: { halign: 'left' },
                            1: { halign: 'left' },
                            2: { halign: 'left' },
                            3: { halign: 'left' }
                        },
                        didDrawPage: function (data) {
                            let str = "Halaman " + doc.internal.getNumberOfPages();
                            doc.setFontSize(8);
                            doc.text(str, data.settings.margin.left, pageHeight - 10);
                        }
                    });

                    // --- RINGKASAN ---
                    let finalY = doc.autoTable.previous.finalY;
                    const sparepartsData = @json($spareparts);
                    const totalItems = sparepartsData.length;
                    const totalAsset = sparepartsData.reduce((sum, item) => {
                        return sum + (parseFloat(item.stok_induk) * parseFloat(item.harga_modal_terakhir));
                    }, 0);

                    doc.setFontSize(10);
                    doc.autoTable({
                        startY: finalY + 15,
                        theme: 'plain',
                        body: [
                            ['Total Jenis Item', ':', `${totalItems} item`],
                        ],
                        styles: { fontSize: 10, cellPadding: {top: 2, right: 5, bottom: 2, left: 0} },
                        columnStyles: {
                            0: { fontStyle: 'bold' },
                            1: { halign: 'center' },
                        }
                    });

                    // Simpan file
                    doc.save('laporan-stok-induk-{{ date("d-m-Y") }}.pdf');
                });
            }
        });
    </script>
</x-app-layout>
