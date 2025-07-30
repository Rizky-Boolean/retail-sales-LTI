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
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 print-hide space-y-4 sm:space-y-0">
                    <a href="{{ route('laporan.induk.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ __('Kembali ke Pusat Laporan') }}
                    </a>
                    
                    <button id="export-pdf" class="inline-flex items-center justify-center px-5 py-2.5 text-base font-medium text-white bg-gray-600 rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Export ke PDF</span>
                    </button>
                </div>

                {{-- Search Bar --}}
                <div class="mb-6 print-hide">
                    <form method="GET" action="{{ route('laporan.induk.stok') }}" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari berdasarkan kode part atau nama part..."
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-500 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        {{-- Filter Stok --}}
                        <div>
                            <select name="stok_filter" 
                                    class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-100">
                                <option value="">Semua Stok</option>
                                <option value="tersedia" {{ request('stok_filter') == 'tersedia' ? 'selected' : '' }}>Stok Tersedia (> 0)</option>
                                <option value="habis" {{ request('stok_filter') == 'habis' ? 'selected' : '' }}>Stok Habis (= 0)</option>
                                <option value="rendah" {{ request('stok_filter') == 'rendah' ? 'selected' : '' }}>Stok Rendah (≤ 5)</option>
                            </select>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari
                            </button>
                            @if(request('search') || request('stok_filter'))
                                <a href="{{ route('laporan.induk.stok') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Info Hasil Pencarian --}}
                @if(request('search'))
                    <div class="mb-4 print-hide">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-blue-700 dark:text-blue-300 text-sm">
                                    Menampilkan {{ $spareparts->total() }} hasil untuk pencarian: <strong>"{{ request('search') }}"</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Statistik Ringkas --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 print-hide">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <div>
                                <p class="text-blue-100 text-sm">Total Item</p>
                                <p class="text-2xl font-bold">{{ $spareparts->total() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <div>
                                <p class="text-green-100 text-sm">Total Stok</p>
                                <p class="text-2xl font-bold">{{ number_format($totalStok) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <div>
                                <p class="text-purple-100 text-sm">Nilai Asset</p>
                                <p class="text-xl font-bold">{{ 'Rp ' . number_format($totalAsset, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Laporan Stok --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="laporan-stok-table" class="w-full min-w-full">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">No</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Kode Part</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Part</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Stok Saat Ini</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Modal Terakhir</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nilai Stok</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $index => $sparepart)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4 text-sm">{{ $spareparts->firstItem() + $index }}</td>
                                    <td class="py-3 px-4 font-medium">{{ $sparepart->kode_part }}</td>
                                    <td class="py-3 px-4">{{ $sparepart->nama_part }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($sparepart->stok_induk <= 0) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 
                                            @elseif($sparepart->stok_induk <= 5) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                            @if($sparepart->stok_induk <= 0)
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                HABIS
                                            @else
                                                {{ number_format($sparepart->stok_induk) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-medium">{{ 'Rp ' . number_format($sparepart->harga_modal_terakhir, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-semibold text-blue-600 dark:text-blue-400">
                                        {{ 'Rp ' . number_format($sparepart->stok_induk * $sparepart->harga_modal_terakhir, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">
                                                @if(request('search'))
                                                    Tidak ada data yang sesuai dengan pencarian "{{ request('search') }}"
                                                @else
                                                    Tidak ada data sparepart
                                                @endif
                                            </p>
                                            @if(request('search'))
                                                <a href="{{ route('laporan.induk.stok') }}" class="mt-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                                    Lihat semua data
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($spareparts->hasPages())
                    <div class="mt-6 print-hide">
                        <div class="flex flex-col sm:flex-row justify-between items-center">
                            <div class="text-sm text-gray-700 dark:text-gray-300 mb-4 sm:mb-0">
                                Menampilkan {{ $spareparts->firstItem() }} sampai {{ $spareparts->lastItem() }} dari {{ $spareparts->total() }} hasil
                            </div>
                            {{ $spareparts->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif

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

                    @if(request('search'))
                    doc.text('Filter: "{{ request('search') }}"', 40, 95);
                    @endif

                    // --- TABEL ---
                    const tableData = [];
                    const rows = document.querySelectorAll('#laporan-stok-table tbody tr');
                    
                    rows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        if (cells.length > 0 && !cells[0].getAttribute('colspan')) {
                            tableData.push([
                                cells[0].textContent.trim(), // No
                                cells[1].textContent.trim(), // Kode Part
                                cells[2].textContent.trim(), // Nama Part
                                cells[3].textContent.trim(), // Stok
                                cells[4].textContent.trim(), // Harga Modal
                                cells[5].textContent.trim()  // Nilai Stok
                            ]);
                        }
                    });

                    doc.autoTable({
                        head: [['No', 'Kode Part', 'Nama Part', 'Stok', 'Harga Modal', 'Nilai Stok']],
                        body: tableData,
                        startY: @if(request('search')) 110 @else 100 @endif,
                        theme: 'striped',
                        headStyles: { 
                            fillColor: [30, 58, 138],
                            textColor: 255,
                            halign: 'center'
                        },
                        styles: { 
                            fontSize: 8,
                            cellPadding: 3
                        },
                        columnStyles: {
                            0: { halign: 'center', cellWidth: 30 },
                            1: { halign: 'left', cellWidth: 80 },
                            2: { halign: 'left', cellWidth: 150 },
                            3: { halign: 'center', cellWidth: 50 },
                            4: { halign: 'right', cellWidth: 80 },
                            5: { halign: 'right', cellWidth: 90 }
                        },
                        didDrawPage: function (data) {
                            let str = "Halaman " + doc.internal.getNumberOfPages();
                            doc.setFontSize(8);
                            doc.text(str, data.settings.margin.left, pageHeight - 10);
                        }
                    });

                    // --- RINGKASAN ---
                    let finalY = doc.autoTable.previous.finalY;
                    const totalItems = {{ $spareparts->total() }};
                    const totalAsset = {{ $totalAsset }};

                    doc.setFontSize(10);
                    doc.autoTable({
                        startY: finalY + 15,
                        theme: 'plain',
                        body: [
                            ['Total Jenis Item', ':', `${totalItems.toLocaleString('id-ID')} item`],
                            ['Total Nilai Asset', ':', `Rp ${totalAsset.toLocaleString('id-ID')}`],
                        ],
                        styles: { fontSize: 10, cellPadding: {top: 2, right: 5, bottom: 2, left: 0} },
                        columnStyles: {
                            0: { fontStyle: 'bold', cellWidth: 120 },
                            1: { halign: 'center', cellWidth: 20 },
                            2: { cellWidth: 150 }
                        }
                    });

                    // Simpan file
                    const filename = @if(request('search')) 
                        'laporan-stok-induk-{{ date("d-m-Y") }}-{{ Str::slug(request('search')) }}.pdf'
                    @else 
                        'laporan-stok-induk-{{ date("d-m-Y") }}.pdf'
                    @endif;
                    doc.save(filename);
                });
            }
        });
    </script>

    {{-- Print Styles --}}
    <style>
        @media print {
            .print-hide {
                display: none !important;
            }
        }
    </style>
</x-app-layout>