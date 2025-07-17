<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Penjualan Semua Cabang') }}
            </h2>
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
                    
                    <!-- Form Filter -->
                    <form action="{{ route('laporan.induk.penjualan') }}" method="GET" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-end space-x-4">
                        <div>
                            <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <div>
                            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600">
                        </div>
                        <div>
                            <label for="cabang_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cabang</label>
                            <select name="cabang_id" id="cabang_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-600">
                                <option value="">Semua Cabang</option>
                                @foreach($cabangs as $cabang)
                                    <option value="{{ $cabang->id }}" {{ $cabangId == $cabang->id ? 'selected' : '' }}>
                                        {{ $cabang->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                    </form>

                    <!-- Tabel Laporan -->
                    <div class="overflow-x-auto">
                        <table id="laporan-penjualan-table" class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">No. Nota</th>
                                    <th class="py-3 px-4 text-left">Tanggal</th>
                                    <th class="py-3 px-4 text-left">Cabang</th>
                                    <th class="py-3 px-4 text-left">Kasir</th>
                                    <th class="py-3 px-4 text-right">Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualans as $penjualan)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">{{ $penjualan->nomor_nota }}</td>
                                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d M Y') }}</td>
                                        <td class="py-3 px-4">{{ $penjualan->cabang->nama_cabang }}</td>
                                        <td class="py-3 px-4">{{ $penjualan->user->name }}</td>
                                        <td class="py-3 px-4 text-right font-semibold">{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">Tidak ada data penjualan pada periode ini.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="font-bold bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <td colspan="4" class="py-3 px-4 text-right text-lg">Total Penjualan Periode Ini:</td>
                                    <td class="py-3 px-4 text-right text-lg text-green-600 dark:text-green-400">{{ 'Rp ' . number_format($totalPenjualan, 0, ',', '.') }}</td>
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
            const doc = new jsPDF('p', 'pt', 'a4');

            const tanggalAwal = "{{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}";
            const tanggalAkhir = "{{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}";
            const cabangFilter = document.getElementById('cabang_id').options[document.getElementById('cabang_id').selectedIndex].text;

            doc.setFontSize(18);
            doc.text('Laporan Penjualan Semua Cabang', 40, 50);
            doc.setFontSize(11);
            doc.setTextColor(100);
            doc.text(`Periode: ${tanggalAwal} s/d ${tanggalAkhir}`, 40, 70);
            doc.text(`Filter Cabang: ${cabangFilter}`, 40, 85);

            doc.autoTable({
                html: '#laporan-penjualan-table',
                startY: 100,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
            });

            doc.save(`laporan-penjualan-cabang.pdf`);
        });
    </script>
</x-app-layout>
