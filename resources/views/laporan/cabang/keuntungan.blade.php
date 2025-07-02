<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Laporan Keuntungan Kotor
            </h2>
            <div>
                <button id="export-pdf-btn"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150 ease-in-out shadow">
                    Export PDF
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    {{-- Link Kembali --}}
                    <div class="mb-6">
                        <a href="{{ route('laporan.cabang.index') }}"
                           class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                            &larr; Kembali ke Pusat Laporan
                        </a>
                    </div>

                    {{-- Filter Form --}}
                    <form action="{{ route('laporan.cabang.keuntungan') }}" method="GET"
                          class="mb-8 bg-gray-50 dark:bg-gray-700 p-4 md:p-6 rounded-lg shadow-sm flex flex-col md:flex-row gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <label for="tanggal_awal"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}"
                                   class="block w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                        <div class="w-full md:w-1/3">
                            <label for="tanggal_akhir"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}"
                                   class="block w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                        <button type="submit"
                                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-150 ease-in-out shadow">
                            Filter
                        </button>
                    </form>

                    {{-- Tabel --}}
                    <div class="overflow-x-auto">
                        <table id="laporan-keuntungan-table"
                               class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase font-semibold">
                                <tr>
                                    <th class="py-3 px-4 text-left">No. Nota</th>
                                    <th class="py-3 px-4 text-left">Tanggal</th>
                                    <th class="py-3 px-4 text-right">Total Penjualan</th>
                                    <th class="py-3 px-4 text-right">Total HPP</th>
                                    <th class="py-3 px-4 text-right">Keuntungan Kotor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @php $grandTotalHpp = 0; @endphp
                                @forelse($penjualans as $penjualan)
                                    @php
                                        $totalHppPerNota = $penjualan->details->sum(function($detail) {
                                            return $detail->hpp_satuan * $detail->qty;
                                        });
                                        $grandTotalHpp += $totalHppPerNota;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        <td class="py-3 px-4">{{ $penjualan->nomor_nota }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d M Y') }}</td>
                                        <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($penjualan->total_penjualan, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($totalHppPerNota, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right font-bold">{{ 'Rp ' . number_format($penjualan->total_penjualan - $totalHppPerNota, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                            Tidak ada data penjualan dalam periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-100 dark:bg-gray-700 font-bold">
                                <tr>
                                    <td colspan="2" class="py-3 px-4 text-right">Total Periode Ini:</td>
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
    </div>

    {{-- Script Export PDF --}}
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
