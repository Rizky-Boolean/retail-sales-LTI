<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Rekap Pengeluaran') }}
            </h2>
            {{-- [UBAH] Ganti tombol Cetak menjadi Export PDF --}}
            <button id="export-pdf-btn" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Export ke PDF
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-4">
                        <a href="{{ route('laporan.induk.index') }}" class="text-blue-500 hover:text-blue-700">
                            &larr; Kembali ke Pusat Laporan
                        </a>
                    </div>
                    
                    <form action="{{ route('laporan.induk.pengeluaran') }}" method="GET" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-end space-x-4">
                        {{-- ... (kode form filter tetap sama) ... --}}
                    </form>

                    <div class="overflow-x-auto">
                        {{-- Tambahkan ID pada tabel --}}
                        <table id="laporan-pengeluaran-table" class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">ID Transaksi</th>
                                    <th class="py-3 px-4 text-left">Tanggal</th>
                                    <th class="py-3 px-4 text-left">Supplier</th>
                                    <th class="py-3 px-4 text-right">Total Pembelian</th>
                                    <th class="py-3 px-4 text-right">PPN</th>
                                    <th class="py-3 px-4 text-right">Total Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengeluarans as $trx)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">TR-{{ $trx->id }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($trx->tanggal_masuk)->format('d M Y') }}</td>
                                        <td class="py-3 px-4">{{ $trx->supplier->nama_supplier }}</td>
                                        <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($trx->total_pembelian, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($trx->total_ppn_supplier, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right font-semibold">{{ 'Rp ' . number_format($trx->total_final, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">Tidak ada data pembelian pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="font-bold bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <td colspan="5" class="py-3 px-4 text-right text-lg">Total Pengeluaran Periode Ini:</td>
                                    <td class="py-3 px-4 text-right text-lg text-red-600 dark:text-red-400">{{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- [TAMBAHKAN] Script untuk Export PDF --}}
    <script>
        document.getElementById('export-pdf-btn').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'pt', 'a4'); // 'p' for portrait, 'pt' for points, 'a4' for size

            const tanggalAwal = "{{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}";
            const tanggalAkhir = "{{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}";

            doc.setFontSize(18);
            doc.text('Laporan Rekap Pengeluaran', 40, 50);
            doc.setFontSize(11);
            doc.setTextColor(100);
            doc.text(`Periode: ${tanggalAwal} s/d ${tanggalAkhir}`, 40, 70);

            doc.autoTable({
                html: '#laporan-pengeluaran-table',
                startY: 80,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
            });

            doc.save(`laporan-pengeluaran-${tanggalAwal}.pdf`);
        });
    </script>
</x-app-layout>

