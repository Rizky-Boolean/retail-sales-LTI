<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Keuntungan Kotor') }}
            </h2>
            <div>
                <button id="export-pdf-btn" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">Export ke PDF</button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-4">
                        <a href="{{ route('laporan.cabang.index') }}" class="text-blue-500 hover:text-blue-700">
                            &larr; Kembali ke Pusat Laporan
                        </a>
                    </div>
                    
                    <form action="{{ route('laporan.cabang.keuntungan') }}" method="GET" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-end space-x-4">
                        <div>
                            <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <div>
                            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                    </form>

                    <div class="overflow-x-auto">
                        <table id="laporan-keuntungan-table" class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">No. Nota</th>
                                    <th class="py-3 px-4 text-left">Tanggal</th>
                                    <th class="py-3 px-4 text-right">Total Penjualan</th>
                                    <th class="py-3 px-4 text-right">Total HPP</th>
                                    <th class="py-3 px-4 text-right">Keuntungan Kotor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotalHpp = 0; @endphp
                                @forelse($penjualans as $penjualan)
                                    @php
                                        $totalHppPerNota = $penjualan->details->sum(function($detail) {
                                            return $detail->hpp_satuan * $detail->qty;
                                        });
                                        $grandTotalHpp += $totalHppPerNota;
                                    @endphp
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">{{ $penjualan->nomor_nota }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d M Y') }}</td>
                                        <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($penjualan->total_penjualan, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($totalHppPerNota, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right font-semibold">{{ 'Rp ' . number_format($penjualan->total_penjualan - $totalHppPerNota, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">Tidak ada data penjualan pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="font-bold bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <td colspan="2" class="py-3 px-4 text-right">Total Periode Ini:</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($penjualans->sum('total_penjualan'), 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($grandTotalHpp, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right text-lg text-green-600 dark:text-green-400">{{ 'Rp ' . number_format($totalKeuntungan, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('export-pdf-btn').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const tanggalAwal = "{{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}";
            const tanggalAkhir = "{{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}";
            const namaCabang = "{{ auth()->user()->cabang->nama_cabang ?? 'Gudang Cabang' }}";

            doc.setFontSize(18);
            doc.text('Laporan Keuntungan Kotor', 14, 22);
            doc.setFontSize(11);
            doc.setTextColor(100);
            doc.text(`Cabang: ${namaCabang}`, 14, 30);
            doc.text(`Periode: ${tanggalAwal} s/d ${tanggalAkhir}`, 14, 36);

            doc.autoTable({
                html: '#laporan-keuntungan-table',
                startY: 42,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
            });

            doc.save(`laporan-keuntungan-${namaCabang.replace(' ', '-')}-${tanggalAwal}.pdf`);
        });
    </script>
</x-app-layout>
