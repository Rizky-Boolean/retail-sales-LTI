<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pencatatan Stok Masuk dari Supplier') }}
        </h2>
    </x-slot>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('stok-masuk.store') }}" method="POST"
                          x-data="itemDetails({{ json_encode($spareparts) }}, {{ json_encode($suppliers) }}, {{ json_encode(old('details', [['sparepart_id' => '', 'qty' => 1, 'harga_beli_satuan' => 0]])) }})"
                          x-init="init()">
                        @csrf
                        {{-- Header Form --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                            <div>
                                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" />
                                <x-text-input id="tanggal_masuk" name="tanggal_masuk" type="date" class="mt-1 block w-full"
                                    :value="old('tanggal_masuk', date('Y-m-d'))" max="{{ date('Y-m-d') }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('tanggal_masuk')" />
                            </div>
                            <div class="relative">
                                <x-input-label for="supplier_id" :value="__('Supplier')" />
                                <input type="hidden" name="supplier_id" x-model="selectedSupplierId">
                                
                                {{-- Input pencarian --}}
                                <input 
                                    type="text" 
                                    :value="getSelectedSupplierText()"
                                    @input="searchSupplier($event.target.value)"
                                    @focus="showSupplierDropdown = true"
                                    @blur="hideSupplierDropdown()"
                                    placeholder="Ketik untuk mencari supplier atau klik untuk melihat semua..."
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                
                                {{-- Dropdown hasil pencarian supplier --}}
                                <div x-show="showSupplierDropdown" 
                                     x-transition
                                     class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                    
                                    {{-- Opsi kosong --}}
                                    <div @mousedown.prevent="selectSupplier('')"
                                         class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 italic">
                                        -- Pilih Supplier --
                                    </div>
                                    
                                    {{-- Hasil pencarian --}}
                                    <template x-for="supplier in getFilteredSuppliers()" :key="supplier.id">
                                        <div @mousedown.prevent="selectSupplier(supplier.id)"
                                             class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <span x-text="supplier.nama_supplier"></span>
                                        </div>
                                    </template>
                                    
                                    {{-- Pesan jika tidak ada hasil --}}
                                    <div x-show="getFilteredSuppliers().length === 0" 
                                         class="px-3 py-2 text-gray-500 italic">
                                        Tidak ada supplier yang ditemukan
                                    </div>
                                </div>
                                
                                <x-input-error class="mt-2" :messages="$errors->get('supplier_id')" />
                            </div>
                        </div>

                        {{-- Detail Sparepart --}}
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Detail Sparepart</h3>
                        <div class="overflow-x-auto rounded-md border border-gray-300 dark:border-gray-700 mb-8">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-200 dark:bg-gray-700 text-sm uppercase">
                                    <tr>
                                        <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Sparepart</th>
                                        <th class="py-3 px-4 text-center w-24 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Qty</th>
                                        <th class="py-3 px-4 text-right w-48 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Harga Beli Satuan</th>
                                        <th class="py-3 px-4 text-right w-48 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Subtotal</th>
                                        <th class="py-3 px-4 w-12"></th> {{-- Kolom untuk tombol hapus --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(detail, index) in details" :key="index">
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="px-4 py-2 relative">
                                                {{-- Hidden input untuk menyimpan nilai yang dipilih --}}
                                                <input type="hidden" :name="`details[${index}][sparepart_id]`" x-model="detail.sparepart_id">

                                                {{-- Input pencarian --}}
                                                <input 
                                                    type="text" 
                                                    :value="getSelectedSparepartText(detail.sparepart_id)"
                                                    @input="searchSparepart(index, $event.target.value)"
                                                    @focus="showDropdown(index)"
                                                    @blur="hideDropdown(index)"
                                                    placeholder="Ketik untuk mencari atau klik untuk melihat semua..."
                                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    required>

                                                {{-- Dropdown hasil pencarian --}}
                                                <div x-show="detail.showDropdown" 
                                                     x-transition
                                                     class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto">

                                                    {{-- Opsi kosong --}}
                                                    <div @mousedown.prevent="selectSparepart(index, '')"
                                                         class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 italic">
                                                        -- Pilih Sparepart --
                                                    </div>

                                                    {{-- Hasil pencarian --}}
                                                    <template x-for="sparepart in getFilteredSpareparts(index)" :key="sparepart.id">
                                                        <div @mousedown.prevent="selectSparepart(index, sparepart.id)"
                                                             class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                                             :class="{ 'opacity-50 cursor-not-allowed': isSparepartSelected(sparepart.id) && detail.sparepart_id != sparepart.id }">
                                                            <span x-text="`${sparepart.kode_part} - ${sparepart.nama_part}`"></span>
                                                            <span x-show="isSparepartSelected(sparepart.id) && detail.sparepart_id != sparepart.id" 
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
                                            <td class="px-4 py-2">
                                                <input type="number" :name="`details[${index}][qty]`" x-model.number="detail.qty"
                                                    @input="calculateTotals()"
                                                    class="w-full text-center border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                                    min="1" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="`details[${index}][harga_beli_satuan]`" x-model.number="detail.harga_beli_satuan"
                                                    @input="calculateTotals()"
                                                    @blur="validateHarga(index, detail.harga_beli_satuan)"
                                                    class="w-full text-right border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                                    min="1000" step="500" placeholder="Min. 1000" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="text" :value="formatCurrency(detail.qty * detail.harga_beli_satuan)"
                                                    class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-right text-gray-700 dark:text-gray-200"
                                                    readonly>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <button type="button" 
                                                        @click="removeDetail(index)" 
                                                        class="text-red-500 hover:text-red-700 transition"
                                                        x-show="details.length > 1">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="font-semibold">
                                    <tr>
                                        <td colspan="5" class="py-3 px-4"> {{-- [UBAH] Colspan menjadi 5 --}}
                                            <button type="button" @click="addDetail()"
                                                class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                                                + Tambah Item
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-bold py-2 px-4">Total Pembelian</td>
                                        <td class="py-2 px-4 font-medium text-right" colspan="2">
                                            <span x-text="formatCurrency(totalPembelian)"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-bold py-2 px-4">
                                            <label for="ppn_dikenakan" class="inline-flex items-center justify-end">
                                                <input type="checkbox" id="ppn_dikenakan" name="ppn_dikenakan" value="1" x-model="ppnDikenakan"
                                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                                <span class="ml-2">Kenakan PPN 11%</span>
                                            </label>
                                        </td>
                                        <td class="py-2 px-4 text-right" colspan="2">
                                            <span x-text="formatCurrency(ppn)"></span>
                                        </td>
                                    </tr>
                                    <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                        <td colspan="3" class="text-right text-lg font-bold py-2 px-4">Total Final</td>
                                        <td class="py-2 px-4 text-lg font-bold text-right" colspan="2">
                                            <span x-text="formatCurrency(totalFinal)"></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('details')" />

                        {{-- Catatan --}}
                        <div class="mb-8">
                            <x-input-label for="catatan" :value="__('Catatan (Opsional)')" />
                            <textarea id="catatan" name="catatan" rows="3"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">{{ old('catatan') }}</textarea>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('stok-masuk.index') }}"
                                class="inline-flex items-center justify-center px-5 py-2 border border-transparent font-medium text-red-600 dark:text-red-500 hover:underline inline-block mx-1">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- [MODIFIKASI] Logika Alpine.js diperbarui dengan fitur pencarian supplier --}}
    <script>
        function itemDetails(spareparts, suppliers, initialDetails) {
            return {
                spareparts: spareparts,
                suppliers: suppliers,
                details: initialDetails.map(detail => ({
                    ...detail,
                    searchQuery: '',
                    showDropdown: false
                })),
                // Data untuk supplier dropdown
                selectedSupplierId: '{{ old("supplier_id") }}',
                supplierSearchQuery: '',
                showSupplierDropdown: false,
                ppnDikenakan: false,
                totalPembelian: 0,
                ppn: 0,
                totalFinal: 0,
                
                addDetail() {
                    this.details.push({ 
                        sparepart_id: '', 
                        qty: 1, 
                        harga_beli_satuan: 1000,
                        searchQuery: '',
                        showDropdown: false 
                    });
                },
                removeDetail(index) {
                    // Hanya hapus jika ada lebih dari satu baris
                    if (this.details.length > 1) {
                        this.details.splice(index, 1);
                        this.calculateTotals();
                    }
                },
                updateItemData(index) {
                    const selectedId = this.details[index].sparepart_id;
                    // Fitur "Batal Pilih"
                    if (!selectedId) {
                        this.details[index].harga_beli_satuan = 0;
                        this.calculateTotals();
                        return;
                    }
                },
                isSparepartSelected(sparepartId) {
                    return this.details.some(detail => detail.sparepart_id == sparepartId);
                },
                
                // ===== FUNGSI UNTUK SUPPLIER =====
                // Fungsi untuk menampilkan teks supplier yang dipilih
                getSelectedSupplierText() {
                    if (!this.selectedSupplierId) return '';
                    const supplier = this.suppliers.find(s => s.id == this.selectedSupplierId);
                    return supplier ? supplier.nama_supplier : '';
                },
                
                // Fungsi untuk pencarian supplier
                searchSupplier(query) {
                    this.supplierSearchQuery = query;
                    this.showSupplierDropdown = true;
                    // Jika input kosong, reset pilihan
                    if (!query.trim()) {
                        this.selectedSupplierId = '';
                    }
                },
                
                // Fungsi untuk mendapatkan supplier yang difilter berdasarkan pencarian
                getFilteredSuppliers() {
                    const query = this.supplierSearchQuery || '';
                    if (!query.trim()) {
                        return this.suppliers;
                    }
                    return this.suppliers.filter(supplier => {
                        return supplier.nama_supplier.toLowerCase().includes(query.toLowerCase());
                    });
                },
                
                // Fungsi untuk menyembunyikan dropdown supplier
                hideSupplierDropdown() {
                    // Delay untuk memungkinkan klik pada item dropdown
                    setTimeout(() => {
                        this.showSupplierDropdown = false;
                    }, 200);
                },
                
                // Fungsi untuk memilih supplier
                selectSupplier(supplierId) {
                    this.selectedSupplierId = supplierId;
                    this.supplierSearchQuery = '';
                    this.showSupplierDropdown = false;
                },
                
                // ===== FUNGSI UNTUK SPAREPART =====
                // Fungsi untuk menampilkan teks sparepart yang dipilih
                getSelectedSparepartText(sparepartId) {
                    if (!sparepartId) return '';
                    const sparepart = this.spareparts.find(s => s.id == sparepartId);
                    return sparepart ? `${sparepart.kode_part} - ${sparepart.nama_part}` : '';
                },
                // Fungsi untuk pencarian sparepart
                searchSparepart(index, query) {
                    this.details[index].searchQuery = query;
                    this.details[index].showDropdown = true;
                    // Jika input kosong, reset pilihan
                    if (!query.trim()) {
                        this.details[index].sparepart_id = '';
                        this.calculateTotals();
                    }
                },
                // Fungsi untuk mendapatkan sparepart yang difilter berdasarkan pencarian
                getFilteredSpareparts(index) {
                    const query = this.details[index].searchQuery || '';
                    if (!query.trim()) {
                        return this.spareparts;
                    }
                    return this.spareparts.filter(sparepart => {
                        const searchText = `${sparepart.kode_part} ${sparepart.nama_part}`.toLowerCase();
                        return searchText.includes(query.toLowerCase());
                    });
                },
                // Fungsi untuk menampilkan dropdown
                showDropdown(index) {
                    this.details[index].showDropdown = true;
                    // Reset query pencarian untuk menampilkan semua item
                    if (!this.details[index].sparepart_id) {
                        this.details[index].searchQuery = '';
                    }
                },
                // Fungsi untuk menyembunyikan dropdown
                hideDropdown(index) {
                    // Delay untuk memungkinkan klik pada item dropdown
                    setTimeout(() => {
                        this.details[index].showDropdown = false;
                    }, 200);
                },
                // Fungsi untuk memilih sparepart
                selectSparepart(index, sparepartId) {
                    // Cek apakah sparepart sudah dipilih di baris lain
                    if (sparepartId && this.isSparepartSelected(sparepartId) && this.details[index].sparepart_id != sparepartId) {
                        return; // Tidak bisa memilih sparepart yang sudah dipilih
                    }
                    this.details[index].sparepart_id = sparepartId;
                    this.details[index].searchQuery = '';
                    this.details[index].showDropdown = false;
                    // Reset harga jika tidak ada yang dipilih
                    if (!sparepartId) {
                        this.details[index].harga_beli_satuan = 1000;
                    }
                    this.calculateTotals();
                },
                
                // ===== FUNGSI UMUM =====
                calculateTotals() {
                    this.totalPembelian = this.details.reduce((acc, detail) => {
                        return acc + ((parseFloat(detail.qty) || 0) * (parseFloat(detail.harga_beli_satuan) || 0));
                    }, 0);
                    // PPN sekarang dihitung berdasarkan status ceklis
                    this.ppn = this.ppnDikenakan ? this.totalPembelian * 0.11 : 0;
                    this.totalFinal = this.totalPembelian + this.ppn;
                },
                formatCurrency(value) {
                    if (isNaN(value)) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },
                init() {
                    this.calculateTotals();
                    // Juga pantau perubahan pada ceklis
                    this.$watch('details', () => this.calculateTotals(), { deep: true });
                    this.$watch('ppnDikenakan', () => this.calculateTotals());
                },
                validateHarga(index, value) {
                    if (value < 1000) {
                        this.details[index].harga_beli_satuan = 1000;
                        alert('Harga beli satuan minimal Rp 1.000');
                    }
                    this.calculateTotals();
                },
            }
        }
    </script>
</x-app-layout>