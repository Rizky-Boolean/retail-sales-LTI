<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Transaksi Penjualan Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <strong class="font-bold">Terjadi Kesalahan:</strong>
                        <ul class="list-disc pl-5 mt-2 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('penjualan.store') }}" method="POST" x-data="salesForm({{ json_encode($spareparts) }})">
                    @csrf

                    {{-- Tanggal dan Pembeli --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label for="tanggal_penjualan" :value="__('Tanggal Penjualan')" />
                            <x-text-input 
                                id="tanggal_penjualan" 
                                name="tanggal_penjualan" 
                                type="date" 
                                class="mt-1 block w-full" 
                                :value="old('tanggal_penjualan', date('Y-m-d'))" 
                                max="{{ date('Y-m-d') }}" 
                                required />
                            <x-input-error class="mt-2" :messages="$errors->get('tanggal_penjualan')" />
                        </div>
                        <div>
                            <x-input-label for="nama_pembeli" :value="__('Nama Pembeli (Opsional)')" />
                            <x-text-input id="nama_pembeli" name="nama_pembeli" type="text" class="mt-1 block w-full"
                                :value="old('nama_pembeli', 'Customer Retail')" />
                        </div>
                    </div>

                    {{-- Tabel Barang Penjualan --}}
                    <h3 class="text-lg font-semibold mb-3">Detail Barang</h3>
                    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase font-bold">
                                <tr>
                                    <th class="py-3 px-4 text-left">Sparepart</th>
                                    <th class="py-3 px-4 text-center w-24">Qty</th>
                                    <th class="py-3 px-4 text-right w-48">Harga Satuan</th>
                                    <th class="py-3 px-4 text-right w-48">Subtotal</th>
                                    <th class="py-3 px-4 w-12"></th> {{-- Kolom untuk tombol hapus --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="py-2 px-4">
                                            <select :name="`details[${index}][sparepart_id]`" x-model="item.sparepart_id" @change="updateItemData(index)"
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                                <option value="">-- Pilih Sparepart --</option>
                                                <template x-for="sparepart in spareparts" :key="sparepart.id">
                                                    {{-- [UBAH] Tambahkan :disabled untuk mencegah duplikasi --}}
                                                    <option 
                                                        :value="sparepart.id" 
                                                        x-text="`${sparepart.nama_part} (Stok: ${sparepart.pivot.stok})`"
                                                        :disabled="isSparepartSelected(sparepart.id) && item.sparepart_id != sparepart.id">
                                                    </option>
                                                </template>
                                            </select>
                                        </td>
                                        <td class="py-2 px-4 text-center">
                                            <input type="number" :name="`details[${index}][qty]`" x-model.number="item.qty"
                                                class="w-20 text-center border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" min="1" required>
                                        </td>
                                        <td class="py-2 px-4 text-right">
                                            <input type="text" :value="formatCurrency(item.harga_jual)"
                                                class="w-full bg-gray-100 dark:bg-gray-800 text-right border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                        </td>
                                        <td class="py-2 px-4 text-right">
                                            <input type="text" :value="formatCurrency(item.qty * item.harga_jual)"
                                                class="w-full bg-gray-100 dark:bg-gray-800 text-right border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                        </td>
                                        {{-- [UBAH] Tambahkan tombol hapus per baris --}}
                                        <td class="py-2 px-4 text-center">
                                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                {{-- ... (kode tfoot Anda sudah benar) ... --}}
                            </tfoot>
                        </table>
                    </div>
                    <div class="mt-4">
                        <button type="button" @click="addItem()"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow-sm transition">
                            + Tambah Barang
                        </button>
                    </div>

                    {{-- Total & Tombol Aksi --}}
                    <div class="flex justify-between items-end mt-6">
                        {{-- Bagian Total Keseluruhan --}}
                        <div class="w-1/3 text-sm">
                            <div class="flex justify-between py-1">
                                <span class="font-semibold">Total</span>
                                <span x-text="formatCurrency(total)"></span>
                            </div>
                            <div class="flex justify-between py-1">
                                <label for="ppn_dikenakan" class="inline-flex items-center">
                                    <input type="checkbox" id="ppn_dikenakan" name="ppn_dikenakan" value="1" x-model="ppnDikenakan" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="ml-2">PPN 11%</span>
                                </label>
                                <span x-text="formatCurrency(ppnAmount)"></span>
                            </div>
                            <div class="flex justify-between py-2 mt-2 border-t-2 border-gray-300 dark:border-gray-600">
                                <span class="font-bold text-lg">Grand Total</span>
                                <span class="font-bold text-lg" x-text="formatCurrency(grandTotal)"></span>
                            </div>
                        </div>
                        {{-- Tombol Aksi --}}
                        <div>
                            <a href="{{ route('penjualan.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-base rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">Simpan Penjualan</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Alpine.js Logic --}}
    <script>
        function salesForm(spareparts) {
            return {
                spareparts: spareparts,
                items: [{ sparepart_id: '', qty: 1, harga_jual: 0 }],
                ppnDikenakan: false,
                total: 0,
                ppnAmount: 0,
                grandTotal: 0,

                init() {
                    this.$watch('items', () => this.calculateTotals(), { deep: true });
                    this.$watch('ppnDikenakan', () => this.calculateTotals());
                },

                addItem() {
                    this.items.push({ sparepart_id: '', qty: 1, harga_jual: 0 });
                },
                
                // [UBAH] Tambahkan logika hapus baris
                removeItem(index) {
                    // Hanya hapus jika ada lebih dari satu baris
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                updateItemData(index) {
                    const selectedId = this.items[index].sparepart_id;
                    
                    // [UBAH] Logika untuk membatalkan pilihan
                    if (!selectedId) {
                        this.items[index].harga_jual = 0;
                        this.calculateTotals();
                        return;
                    }

                    const selectedSparepart = this.spareparts.find(s => s.id == selectedId);
                    this.items[index].harga_jual = selectedSparepart ? selectedSparepart.harga_jual : 0;
                    this.calculateTotals();
                },
                
                // [BARU] Fungsi untuk memeriksa apakah sparepart sudah dipilih
                isSparepartSelected(sparepartId) {
                    return this.items.some(item => item.sparepart_id == sparepartId);
                },

                calculateTotals() {
                    this.total = this.items.reduce((acc, item) => acc + (item.qty * item.harga_jual), 0);
                    this.ppnAmount = this.ppnDikenakan ? this.total * 0.11 : 0;
                    this.grandTotal = this.total + this.ppnAmount;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                }
            }
        }
    </script>
</x-app-layout>
