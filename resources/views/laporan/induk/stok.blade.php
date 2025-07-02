<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Stok Gudang Induk') }}
            </h2>
        </div>
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

                <div class="flex justify-end mb-4">
                    <button id="export-pdf-btn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                        Export ke PDF
                    </button>
                </div>

                {{-- Tabel Laporan Stok --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table id="laporan-stok-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Kode Part</th>
                                <th class="py-3 px-4 text-left uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Nama Part</th>
                                <th class="py-3 px-4 text-center uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Stok Saat Ini</th>
                                <th class="py-3 px-4 text-right uppercase font-semibold text-sm text-gray-700 dark:text-gray-300">Harga Modal Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $sparepart->kode_part }}</td>
                                    <td class="py-3 px-4">{{ $sparepart->nama_part }}</td>
                                    <td class="py-3 px-4 text-center font-semibold">{{ $sparepart->stok_induk }}</td>
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

    {{-- Script Export PDF --}}
    <script>
        document.getElementById('export-pdf-btn').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'pt', 'a4');

            doc.setFontSize(18);
            doc.text('Laporan Stok Gudang Induk', 40, 50);
            doc.setFontSize(11);
            doc.setTextColor(100);
            doc.text(`Dicetak pada: ${new Date().toLocaleDateString('id-ID')}`, 40, 70);

            doc.autoTable({
                html: '#laporan-stok-table',
                startY: 90,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
            });
            doc.save('laporan-stok-induk.pdf');
        });
    </script>
</x-app-layout>