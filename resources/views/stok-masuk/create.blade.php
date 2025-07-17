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
                          x-data="itemDetails({{ json_encode($spareparts) }}, {{ json_encode(old('details', [['sparepart_id' => '', 'qty' => 1, 'harga_beli_satuan' => 0]])) }})"
                          x-init="init()">
                        @csrf

                        {{-- Header Form --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                            <div>
                                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" />
                                {{-- [MODIFIKASI] Tambahkan atribut max untuk validasi tanggal --}}
                                <x-text-input id="tanggal_masuk" name="tanggal_masuk" type="date" class="mt-1 block w-full"
                                    :value="old('tanggal_masuk', date('Y-m-d'))" max="{{ date('Y-m-d') }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('tanggal_masuk')" />
                            </div>
                            <div>
                                <x-input-label for="supplier_id" :value="__('Supplier')" />
                                <select id="supplier_id" name="supplier_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('supplier_id')" />
                            </div>
                        </div>

                        {{-- Detail Sparepart --}}
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Detail Sparepart</h3>
                        <div class="overflow-x-auto rounded-md border border-gray-300 dark:border-gray-700 mb-8">
                            <table class="min-w-full bg-white dark:bg-gray-900">
                                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm uppercase">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Sparepart</th>
                                        <th class="py-3 px-4 text-center w-24">Qty</th>
                                        <th class="py-3 px-4 text-right w-48">Harga Beli Satuan</th>
                                        <th class="py-3 px-4 text-right w-48">Subtotal</th>
                                        <th class="py-3 px-4 w-12"></th> {{-- Kolom untuk tombol hapus --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(detail, index) in details" :key="index">
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="px-4 py-2">
                                                <select :name="`details[${index}][sparepart_id]`" x-model="detail.sparepart_id" @change="updateItemData(index)"
                                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    required>
                                                    <option value="">-- Pilih Sparepart --</option>
                                                    <template x-for="sparepart in spareparts" :key="sparepart.id">
                                                        <option 
                                                            :value="sparepart.id" 
                                                            x-text="`${sparepart.kode_part} - ${sparepart.nama_part}`"
                                                            :disabled="isSparepartSelected(sparepart.id) && detail.sparepart_id != sparepart.id">
                                                        </option>
                                                    </template>
                                                </select>
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
                                                    class="w-full text-right border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                                    min="0" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="text" :value="formatCurrency(detail.qty * detail.harga_beli_satuan)"
                                                    class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-right text-gray-700 dark:text-gray-200"
                                                    readonly>
                                            </td>
                                            {{-- [MODIFIKASI] Tambahkan tombol hapus --}}
                                            <td class="px-4 py-2 text-center">
                                                <button type="button" @click="removeDetail(index)" class="text-red-500 hover:text-red-700 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="font-semibold">
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
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                {{ __('Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script>
        function itemDetails(spareparts, initialDetails) {
            return {
                spareparts: spareparts,
                details: initialDetails,
                ppnDikenakan: false,
                totalPembelian: 0,
                ppn: 0,
                totalFinal: 0,

                addDetail() {
                    this.details.push({ sparepart_id: '', qty: 1, harga_beli_satuan: 0 });
                },
                
                removeDetail(index) {
                    // Hanya hapus jika ada lebih dari satu baris
                    if (this.details.length > 1) {
                        this.details.splice(index, 1);
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
                }
            }
        }
    </script>
</x-app-layout>
