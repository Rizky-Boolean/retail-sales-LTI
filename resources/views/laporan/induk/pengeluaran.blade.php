<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Rekap Pengeluaran') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Tombol Kembali --}}
                <div class="mb-4">
                    <a href="{{ route('laporan.induk.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-semibold">
                        &larr; Kembali ke Pusat Laporan
                    </a>
                </div>

                {{-- Tombol Export PDF dipindah ke atas tabel --}}
                <div class="flex justify-end mb-4">
                    <button id="export-pdf-btn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Export ke PDF
                    </button>
                </div>

                {{-- Tabel Rekap Pengeluaran --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="laporan-pengeluaran-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">ID Transaksi</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Tanggal</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Supplier</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Total Pembelian</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">PPN</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Total Final</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($pengeluarans as $trx)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">TR-{{ $trx->id }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($trx->tanggal_masuk)->format('d M Y') }}</td>
                                    <td class="py-3 px-4">{{ $trx->supplier->nama_supplier }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($trx->total_pembelian, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($trx->total_ppn_supplier, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-semibold">{{ 'Rp ' . number_format($trx->total_final, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada data pembelian pada periode ini.</td>
                                </tr>
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

    {{-- Script Export PDF --}}
    <script>
        document.getElementById('export-pdf-btn').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'pt', 'a4');

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
