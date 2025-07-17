<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Distribusi #DIST-') . $distribusi->id }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Tombol Kembali --}}
                <div class="mb-6">
                    @if(auth()->user()->role === 'admin_induk')
                        <a href="{{ route('distribusi.index') }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                            &larr; Kembali ke Histori Distribusi
                        </a>
                    @elseif(auth()->user()->role === 'admin_cabang')
                        <button onclick="window.close()" class="text-blue-500 hover:text-blue-700 font-semibold">
                            &larr; Kembali ke Halaman Penerimaan (Tutup Tab)
                        </button>
                    @endif
                </div>

                {{-- Tombol Ekspor PDF --}}
                <div class="flex justify-end mb-4">
                    <button onclick="generatePDF()" 
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Export ke PDF
                    </button>

                </div>
                <div id="printArea">
                {{-- Informasi Header Transaksi --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Tanggal Kirim</h3>
                        <p>{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Cabang Tujuan</h3>
                        <p>{{ $distribusi->cabangTujuan->nama_cabang ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">Dikirim Oleh</h3>
                        <p>{{ $distribusi->user->name ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- Detail Item --}}
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2">Rincian Barang Terkirim</h3>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs uppercase font-bold">
                            <tr>
                                <th class="py-3 px-4 text-left">Nama Part</th>
                                <th class="py-3 px-4 text-center">Qty</th>
                                <th class="py-3 px-4 text-right">Harga Modal</th>
                                <th class="py-3 px-4 text-right">Harga Kirim</th>
                                <th class="py-3 px-4 text-right">Subtotal Kirim</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                            @foreach($distribusi->details as $detail)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="py-3 px-4">{{ $detail->sparepart->nama_part ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-center">{{ $detail->qty }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($detail->harga_modal_satuan, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($detail->harga_kirim_satuan, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right font-semibold">{{ 'Rp ' . number_format($detail->harga_kirim_satuan * $detail->qty, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Rangkuman Total --}}
                <div class="flex justify-end">
                    <div class="w-full md:w-1/3">
                        {{-- [HAPUS] Baris untuk PPN dihapus --}}
                        <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 py-2">
                            <span class="font-semibold">Total Harga Modal</span>
                            <span>{{ 'Rp ' . number_format($distribusi->total_harga_modal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-2 text-lg font-bold">
                            <span>Total Harga Kirim</span>
                            <span>{{ 'Rp ' . number_format($distribusi->total_harga_kirim, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- Script untuk Ekspor PDF --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    async function generatePDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'pt', 'a4');
        const source = document.getElementById('printArea');

        await html2canvas(source, { scale: 2 }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const imgProps = doc.getImageProperties(imgData);
            const pdfWidth = doc.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            doc.save("laporan-distribusi-{{ $distribusi->id }}.pdf");
        });
    }
</script>

</x-app-layout>
