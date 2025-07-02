<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Laporan Arus Kas (Cashflow)
            </h2>
            <button id="export-pdf-btn"
                    class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow transition">
                Export ke PDF
            </button>
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
                    <form action="{{ route('laporan.cabang.cashflow') }}" method="GET"
                          class="mb-8 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="tanggal_awal"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                        <div>
                            <label for="tanggal_akhir"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition">
                                Filter
                            </button>
                        </div>
                    </form>

                    {{-- Ringkasan --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 text-center">
                        <div class="bg-green-100 dark:bg-green-800 p-6 rounded-xl shadow">
                            <h4 class="text-sm font-semibold text-green-800 dark:text-green-200 uppercase mb-1">Total Pemasukan</h4>
                            <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ 'Rp ' . number_format($totalPemasukan, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-800 p-6 rounded-xl shadow">
                            <h4 class="text-sm font-semibold text-red-800 dark:text-red-200 uppercase mb-1">Total Pengeluaran</h4>
                            <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-800 p-6 rounded-xl shadow">
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 uppercase mb-1">Arus Kas Bersih</h4>
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ 'Rp ' . number_format($arusKasBersih, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Detail Pemasukan dan Pengeluaran --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Pemasukan --}}
                        <div>
                            <h4 class="text-lg font-bold mb-3">Rincian Pemasukan (Penjualan)</h4>
                            <div class="overflow-x-auto">
                                <table id="laporan-pemasukan-table"
                                       class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase">
                                        <tr>
                                            <th class="p-2 text-left">Tanggal</th>
                                            <th class="p-2 text-left">Nota</th>
                                            <th class="p-2 text-right">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pemasukan as $trx)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="p-2">{{ \Carbon\Carbon::parse($trx->tanggal_penjualan)->format('d/m/Y') }}</td>
                                                <td class="p-2">{{ $trx->nomor_nota }}</td>
                                                <td class="p-2 text-right">{{ number_format($trx->total_final, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center p-4 text-gray-500 dark:text-gray-400">Tidak ada pemasukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Pengeluaran --}}
                        <div>
                            <h4 class="text-lg font-bold mb-3">Rincian Pengeluaran (Penerimaan Barang)</h4>
                            <div class="overflow-x-auto">
                                <table id="laporan-pengeluaran-table"
                                       class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase">
                                        <tr>
                                            <th class="p-2 text-left">Tgl Diterima</th>
                                            <th class="p-2 text-left">ID Kiriman</th>
                                            <th class="p-2 text-right">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pengeluaran as $trx)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="p-2">{{ \Carbon\Carbon::parse($trx->updated_at)->format('d/m/Y') }}</td>
                                                <td class="p-2">DIST-{{ $trx->id }}</td>
                                                <td class="p-2 text-right">{{ number_format($trx->total_harga_kirim, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center p-4 text-gray-500 dark:text-gray-400">Tidak ada pengeluaran.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Export PDF --}}
    <script>
        document.getElementById('export-pdf-btn').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const tanggalAwal = "{{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}";
            const tanggalAkhir = "{{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}";
            const namaCabang = "{{ auth()->user()->cabang->nama_cabang ?? 'Gudang Cabang' }}";
            const totalPemasukan = "{{ 'Rp ' . number_format($totalPemasukan, 0, ',', '.') }}";
            const totalPengeluaran = "{{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') }}";
            const arusKasBersih = "{{ 'Rp ' . number_format($arusKasBersih, 0, ',', '.') }}";

            doc.setFontSize(18);
            doc.text('Laporan Arus Kas (Cashflow)', 14, 22);
            doc.setFontSize(11);
            doc.text(`Cabang: ${namaCabang}`, 14, 30);
            doc.text(`Periode: ${tanggalAwal} s/d ${tanggalAkhir}`, 14, 36);

            doc.setFontSize(12);
            doc.setFont(undefined, 'bold');
            doc.text('Ringkasan Periode', 14, 50);
            doc.setFont(undefined, 'normal');
            doc.text(`Total Pemasukan: ${totalPemasukan}`, 14, 57);
            doc.text(`Total Pengeluaran: ${totalPengeluaran}`, 14, 64);
            doc.setFont(undefined, 'bold');
            doc.text(`Arus Kas Bersih: ${arusKasBersih}`, 14, 71);

            doc.setFontSize(12);
            doc.text('Rincian Pemasukan (Penjualan)', 14, 85);
            doc.autoTable({
                html: '#laporan-pemasukan-table',
                startY: 89,
                theme: 'grid',
                headStyles: { fillColor: [39, 174, 96] },
            });

            let finalY = doc.lastAutoTable.finalY || 90;
            doc.text('Rincian Pengeluaran (Penerimaan Barang)', 14, finalY + 15);
            doc.autoTable({
                html: '#laporan-pengeluaran-table',
                startY: finalY + 19,
                theme: 'grid',
                headStyles: { fillColor: [192, 57, 43] },
            });

            doc.save(`laporan-cashflow-${namaCabang.replace(' ', '-')}-${tanggalAwal}.pdf`);
        });
    </script>
</x-app-layout>
