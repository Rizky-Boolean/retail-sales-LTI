<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Arus Kas (Cashflow)') }}
            </h2>
            <div>
                <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">Cetak Langsung</button>
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
                    
                    <form action="{{ route('laporan.cabang.cashflow') }}" method="GET" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-end space-x-4">
                        <div>
                            <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <div>
                            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 text-center">
                        <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-green-800 dark:text-green-200 uppercase">Total Pemasukan</h4>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-300">{{ 'Rp ' . number_format($totalPemasukan, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-800 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-red-800 dark:text-red-200 uppercase">Total Pengeluaran</h4>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-300">{{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 uppercase">Arus Kas Bersih</h4>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">{{ 'Rp ' . number_format($arusKasBersih, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-bold mb-2">Rincian Pemasukan (Penjualan)</h4>
                            <table id="laporan-pemasukan-table" class="min-w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-700"><tr><th class="p-2 text-left">Tanggal</th><th class="p-2 text-left">Nota</th><th class="p-2 text-right">Jumlah</th></tr></thead>
                                <tbody>
                                @forelse($pemasukan as $trx)
                                    <tr class="border-b dark:border-gray-700"><td class="p-2">{{ \Carbon\Carbon::parse($trx->tanggal_penjualan)->format('d/m/Y') }}</td><td class="p-2">{{ $trx->nomor_nota }}</td><td class="p-2 text-right">{{ number_format($trx->total_final, 0, ',', '.') }}</td></tr>
                                @empty
                                    <tr><td colspan="3" class="text-center p-4">Tidak ada pemasukan.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold mb-2">Rincian Pengeluaran (Penerimaan Barang)</h4>
                             <table id="laporan-pengeluaran-table" class="min-w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-700"><tr><th class="p-2 text-left">Tgl Diterima</th><th class="p-2 text-left">ID Kiriman</th><th class="p-2 text-right">Jumlah</th></tr></thead>
                                <tbody>
                                @forelse($pengeluaran as $trx)
                                    <tr class="border-b dark:border-gray-700"><td class="p-2">{{ \Carbon\Carbon::parse($trx->updated_at)->format('d/m/Y') }}</td><td class="p-2">DIST-{{ $trx->id }}</td><td class="p-2 text-right">{{ number_format($trx->total_harga_kirim, 0, ',', '.') }}</td></tr>
                                @empty
                                    <tr><td colspan="3" class="text-center p-4">Tidak ada pengeluaran.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
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
            const totalPemasukan = "{{ 'Rp ' . number_format($totalPemasukan, 0, ',', '.') }}";
            const totalPengeluaran = "{{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') }}";
            const arusKasBersih = "{{ 'Rp ' . number_format($arusKasBersih, 0, ',', '.') }}";

            // Judul
            doc.setFontSize(18);
            doc.text('Laporan Arus Kas (Cashflow)', 14, 22);
            doc.setFontSize(11);
            doc.setTextColor(100);
            doc.text(`Cabang: ${namaCabang}`, 14, 30);
            doc.text(`Periode: ${tanggalAwal} s/d ${tanggalAkhir}`, 14, 36);

            // Rangkuman
            doc.setFontSize(12);
            doc.setFont(undefined, 'bold');
            doc.text('Rangkuman Periode', 14, 50);
            doc.setFont(undefined, 'normal');
            doc.text(`Total Pemasukan: ${totalPemasukan}`, 14, 57);
            doc.text(`Total Pengeluaran: ${totalPengeluaran}`, 14, 64);
            doc.setFont(undefined, 'bold');
            doc.text(`Arus Kas Bersih: ${arusKasBersih}`, 14, 71);
            
            // Tabel Pemasukan
            doc.setFontSize(12);
            doc.text('Rincian Pemasukan (Penjualan)', 14, 86);
            doc.autoTable({
                html: '#laporan-pemasukan-table',
                startY: 90,
                theme: 'grid',
                headStyles: { fillColor: [39, 174, 96] }, // Warna hijau
            });

            // Tabel Pengeluaran
            let finalY = doc.lastAutoTable.finalY || 90; // Ambil posisi Y setelah tabel pertama
            doc.setFontSize(12);
            doc.text('Rincian Pengeluaran (Penerimaan Barang)', 14, finalY + 15);
            doc.autoTable({
                html: '#laporan-pengeluaran-table',
                startY: finalY + 19,
                theme: 'grid',
                headStyles: { fillColor: [192, 57, 43] }, // Warna merah
            });

            doc.save(`laporan-cashflow-${namaCabang.replace(' ', '-')}-${tanggalAwal}.pdf`);
        });
    </script>
</x-app-layout>
