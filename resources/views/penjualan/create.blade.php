<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Transaksi Penjualan Baru
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <strong class="font-bold">Terjadi Kesalahan:</strong>
                        </div>
                        <ul class="list-disc pl-10 mt-2 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('penjualan.store') }}" method="POST" x-data="salesForm({{ json_encode($spareparts) }})">
                    @csrf

                    {{-- Tanggal dan Pembeli --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
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
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Detail Barang</h3>
                    <div class="rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Sparepart</th>
                                    <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider w-24">Qty</th>
                                    <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider w-48">Harga Satuan</th>
                                    <th class="py-3 px-4 text-right uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider w-48">Subtotal</th>
                                    <th class="py-3 px-4 w-12"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="py-2 px-4 relative">
                                            {{-- Hidden input untuk menyimpan nilai yang dipilih --}}
                                            <input type="hidden" :name="`details[${index}][sparepart_id]`" x-model="item.sparepart_id">
                                            
                                            {{-- Input pencarian --}}
                                            <input 
                                                type="text" 
                                                :value="getSelectedSparepartText(item.sparepart_id)"
                                                @input="searchSparepart(index, $event.target.value)"
                                                @focus="showDropdown(index)"
                                                @blur="hideDropdown(index)"
                                                placeholder="Ketik untuk mencari atau klik untuk melihat semua..."
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm"
                                                required>
                                            
                                            {{-- Dropdown hasil pencarian --}}
                                            <div x-show="item.showDropdown" 
                                                 x-transition
                                                 class="absolute z-[9999] w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-xl max-h-60 overflow-y-auto">
                                                
                                                {{-- Opsi kosong --}}
                                                <div @mousedown.prevent="selectSparepart(index, '')"
                                                     class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 italic">
                                                    -- Pilih Sparepart --
                                                </div>
                                                
                                                {{-- Hasil pencarian --}}
                                                <template x-for="sparepart in getFilteredSpareparts(index)" :key="sparepart.id">
                                                    <div @mousedown.prevent="selectSparepart(index, sparepart.id)"
                                                         class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                                         :class="{ 'opacity-50 cursor-not-allowed': isSparepartSelected(sparepart.id) && item.sparepart_id != sparepart.id }">
                                                        <span x-text="`${sparepart.nama_part} (Stok: ${sparepart.pivot.stok})`"></span>
                                                        <span x-show="isSparepartSelected(sparepart.id) && item.sparepart_id != sparepart.id" 
                                                              class="text-red-500 text-xs ml-2">(Sudah dipilih)</span>
                                                    </div>
                                                </template>
                                                
                                                {{-- Pesan jika tidak ada hasil --}}
                                                <div x-show="getFilteredSpareparts(index).length === 0" 
                                                     class="px-3 py-2 text-gray-500 italic">
                                                    Tidak ada sparepart yang ditemukan
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4 text-center">
                                            <input type="number" :name="`details[${index}][qty]`" x-model.number="item.qty"
                                                class="w-20 text-center border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" min="1" required>
                                        </td>
                                        <td class="py-2 px-4 text-right">
                                            <input type="text" :value="formatCurrency(item.harga_jual)"
                                                class="w-full bg-gray-100 dark:bg-gray-900/50 text-right border-gray-300 dark:border-gray-700 rounded-md shadow-sm cursor-not-allowed" readonly>
                                        </td>
                                        <td class="py-2 px-4 text-right">
                                            <input type="text" :value="formatCurrency(item.qty * item.harga_jual)"
                                                class="w-full bg-gray-100 dark:bg-gray-900/50 text-right border-gray-300 dark:border-gray-700 rounded-md shadow-sm cursor-not-allowed" readonly>
                                        </td>
                                        <td class="py-2 px-4 text-center">
                                            <template x-if="items.length > 1">
                                                <button type="button" @click="removeItem(index)" class="inline-flex items-center justify-center p-2 rounded-full bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 transition" title="Hapus Baris">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                                                    </table>
                    </div>
                    <div class="mt-4">
                        <button type="button" @click="addItem()"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Tambah Barang
                        </button>
                    </div>

                    {{-- Total & Tombol Aksi --}}
                    <div class="flex justify-between items-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="w-full md:w-2/5 space-y-2 text-sm">
                            <div class="flex justify-between py-1 text-gray-600 dark:text-gray-300">
                                <span>Subtotal</span>
                                <span x-text="formatCurrency(total)"></span>
                            </div>
                            <div class="flex justify-between py-1 text-gray-600 dark:text-gray-300">
                                <label for="ppn_dikenakan" class="inline-flex items-center">
                                    <input type="checkbox" id="ppn_dikenakan" name="ppn_dikenakan" value="1" x-model="ppnDikenakan" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="ml-2">PPN 11%</span>
                                </label>
                                <span x-text="formatCurrency(ppnAmount)"></span>
                            </div>
                            <div class="flex justify-between py-2 mt-2 border-t-2 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100">
                                <span class="font-bold text-lg">Grand Total</span>
                                <span class="font-bold text-lg" x-text="formatCurrency(grandTotal)"></span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <a href="{{ route('penjualan.index') }}" class="inline-flex items-center justify-center px-6 py-2 border border-transparent font-medium text-red-600 dark:text-red-500 hover:underline inline-block mx-1">Batal</a>
                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 text-base font-semibold rounded-lg shadow-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                Simpan Penjualan
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Alpine.js Logic dengan fitur pencarian --}}
    <script>
        function salesForm(spareparts) {
            return {
                spareparts: spareparts,
                items: [{ sparepart_id: '', qty: 1, harga_jual: 0, searchQuery: '', showDropdown: false }],
                ppnDikenakan: false,
                total: 0,
                ppnAmount: 0,
                grandTotal: 0,

                init() {
                    this.$watch('items', () => this.calculateTotals(), { deep: true });
                    this.$watch('ppnDikenakan', () => this.calculateTotals());
                },

                addItem() {
                    this.items.push({ sparepart_id: '', qty: 1, harga_jual: 0, searchQuery: '', showDropdown: false });
                },
                
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                updateItemData(index) {
                    const selectedId = this.items[index].sparepart_id;
                    
                    if (!selectedId) {
                        this.items[index].harga_jual = 0;
                        this.calculateTotals();
                        return;
                    }

                    const selectedSparepart = this.spareparts.find(s => s.id == selectedId);
                    this.items[index].harga_jual = selectedSparepart ? selectedSparepart.harga_jual : 0;
                    this.calculateTotals();
                },

                // Fungsi untuk menampilkan teks sparepart yang dipilih
                getSelectedSparepartText(sparepartId) {
                    if (!sparepartId) return '';
                    const sparepart = this.spareparts.find(s => s.id == sparepartId);
                    return sparepart ? `${sparepart.nama_part} (Stok: ${sparepart.pivot.stok})` : '';
                },

                // Fungsi untuk pencarian sparepart
                searchSparepart(index, query) {
                    this.items[index].searchQuery = query;
                    this.items[index].showDropdown = true;
                    
                    // Jika input kosong, reset pilihan
                    if (!query.trim()) {
                        this.items[index].sparepart_id = '';
                        this.items[index].harga_jual = 0;
                        this.calculateTotals();
                    }
                },

                // Fungsi untuk mendapatkan sparepart yang difilter berdasarkan pencarian
                getFilteredSpareparts(index) {
                    const query = this.items[index].searchQuery || '';
                    if (!query.trim()) {
                        return this.spareparts;
                    }
                    
                    return this.spareparts.filter(sparepart => {
                        const searchText = `${sparepart.nama_part}`.toLowerCase();
                        return searchText.includes(query.toLowerCase());
                    });
                },

                // Fungsi untuk menampilkan dropdown
                showDropdown(index) {
                    this.items[index].showDropdown = true;
                    // Reset query pencarian untuk menampilkan semua item
                    if (!this.items[index].sparepart_id) {
                        this.items[index].searchQuery = '';
                    }
                },

                // Fungsi untuk menyembunyikan dropdown
                hideDropdown(index) {
                    // Delay untuk memungkinkan klik pada item dropdown
                    setTimeout(() => {
                        this.items[index].showDropdown = false;
                    }, 200);
                },

                // Fungsi untuk memilih sparepart
                selectSparepart(index, sparepartId) {
                    // Cek apakah sparepart sudah dipilih di baris lain
                    if (sparepartId && this.isSparepartSelected(sparepartId) && this.items[index].sparepart_id != sparepartId) {
                        return; // Tidak bisa memilih sparepart yang sudah dipilih
                    }
                    
                    this.items[index].sparepart_id = sparepartId;
                    this.items[index].searchQuery = '';
                    this.items[index].showDropdown = false;
                    
                    // Update data item setelah memilih
                    this.updateItemData(index);
                },
                
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