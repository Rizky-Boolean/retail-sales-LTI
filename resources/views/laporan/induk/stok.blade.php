<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Stok Gudang Induk') }}
            </h2>
            <div>
                {{-- Tombol Cetak Bawaan Browser --}}
                <button onclick="window.print()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                    Cetak Langsung
                </button>
                {{-- Tombol Baru untuk Export PDF --}}
                <button id="export-pdf-btn" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                    Export ke PDF
                </button>
            </div>
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
                    
                    <div class="overflow-x-auto">
                        {{-- Tambahkan 'id' pada tabel agar mudah diseleksi oleh JavaScript --}}
                        <table id="laporan-stok-table" class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kode Part</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Part</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Stok Saat Ini</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Harga Modal Terakhir</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse($spareparts as $sparepart)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="text-left py-3 px-4">{{ $sparepart->kode_part }}</td>
                                        <td class="text-left py-3 px-4">{{ $sparepart->nama_part }}</td>
                                        <td class="text-center py-3 px-4 font-bold">{{ $sparepart->stok_induk }}</td>
                                        <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($sparepart->harga_modal_terakhir, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Tidak ada data sparepart.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Fungsi Export PDF --}}
    <script>
        document.getElementById('export-pdf-btn').addEventListener('click', function () {
            // Inisialisasi jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Tambahkan Judul Laporan
            doc.setFontSize(18);
            doc.text('Laporan Stok Gudang Induk', 14, 22);
            doc.setFontSize(11);
            doc.setTextColor(100);
            doc.text(`Dicetak pada: ${new Date().toLocaleDateString('id-ID')}`, 14, 30);

            // Gunakan autoTable untuk membuat tabel dari HTML
            doc.autoTable({
                html: '#laporan-stok-table',
                startY: 35, // Posisi awal tabel (setelah judul)
                theme: 'grid', // Tema tabel: 'striped', 'grid', 'plain'
                headStyles: { fillColor: [41, 128, 185] }, // Warna header biru
            });

            // Simpan PDF
            doc.save('laporan-stok-induk.pdf');
        });
    </script>
</x-app-layout>
