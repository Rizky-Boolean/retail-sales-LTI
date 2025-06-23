<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pencatatan Stok Masuk dari Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Form -->
                    {{-- 1. Berikan data 'old' ke Alpine.js saat inisialisasi --}}
                    <form action="{{ route('stok-masuk.store') }}" method="POST"
                          x-data="itemDetails({{ json_encode(old('details', [['sparepart_id' => '', 'qty' => 1, 'harga_beli_satuan' => 0]])) }})"
                          x-init="init()">
                        @csrf
                        <!-- Bagian Header Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" />
                                <x-text-input id="tanggal_masuk" name="tanggal_masuk" type="date" class="mt-1 block w-full" :value="old('tanggal_masuk', date('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('tanggal_masuk')" />
                            </div>
                            <div>
                                <x-input-label for="supplier_id" :value="__('Supplier')" />
                                <select id="supplier_id" name="supplier_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama_supplier }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('supplier_id')" />
                            </div>
                        </div>

                        <!-- Bagian Detail Item -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">Detail Sparepart</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-900">
                                    <thead class="bg-gray-200 dark:bg-gray-700">
                                        <tr>
                                            <th class="text-left py-2 px-3">Sparepart</th>
                                            <th class="text-left py-2 px-3 w-24">Qty</th>
                                            <th class="text-left py-2 px-3 w-48">Harga Beli Satuan</th>
                                            <th class="text-left py-2 px-3 w-48">Subtotal</th>
                                            <th class="py-2 px-3 w-12"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(detail, index) in details" :key="index">
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <td>
                                                    {{-- 2. Gunakan x-model untuk memilih option secara otomatis --}}
                                                    <select :name="`details[${index}][sparepart_id]`" x-model="detail.sparepart_id" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                                        <option value="">-- Pilih Sparepart --</option>
                                                        @foreach($spareparts as $sparepart)
                                                            <option value="{{ $sparepart->id }}">{{ $sparepart->kode_part }} - {{ $sparepart->nama_part }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" :name="`details[${index}][qty]`" x-model.number="detail.qty" @input="calculateTotals()" class="w-full" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" :name="`details[${index}][harga_beli_satuan]`" x-model.number="detail.harga_beli_satuan" @input="calculateTotals()" class="w-full" min="0" required>
                                                </td>
                                                <td>
                                                    <input type="text" :value="formatCurrency(detail.qty * detail.harga_beli_satuan)" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" @click="removeDetail(index)" x-show="details.length > 1" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <button type="button" @click="addDetail()" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    + Tambah Item
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right font-bold py-2 px-3">Total Pembelian</td>
                                            <td class="py-2 px-3" colspan="2"><span x-text="formatCurrency(totalPembelian)"></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right font-bold py-2 px-3">PPN 11% (jika > 100rb)</td>
                                            <td class="py-2 px-3" colspan="2"><span x-text="formatCurrency(ppn)"></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right font-bold py-2 px-3 text-lg">Total Final</td>
                                            <td class="py-2 px-3 text-lg font-bold" colspan="2"><span x-text="formatCurrency(totalFinal)"></span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('details')" />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="catatan" :value="__('Catatan (Opsional)')" />
                            <textarea id="catatan" name="catatan" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('stok-masuk.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Transaksi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Definisikan fungsi Alpine.js di dalam tag <script> --}}
    <script>
        function itemDetails(initialDetails) {
            return {
                details: initialDetails,
                totalPembelian: 0,
                ppn: 0,
                totalFinal: 0,

                addDetail() {
                    this.details.push({ sparepart_id: '', qty: 1, harga_beli_satuan: 0 });
                },
                removeDetail(index) {
                    this.details.splice(index, 1);
                    this.calculateTotals();
                },
                calculateTotals() {
                    this.totalPembelian = this.details.reduce((acc, detail) => {
                        const qty = parseFloat(detail.qty) || 0;
                        const harga = parseFloat(detail.harga_beli_satuan) || 0;
                        return acc + (qty * harga);
                    }, 0);

                    if (this.totalPembelian > 100000) {
                        this.ppn = this.totalPembelian * 0.11;
                    } else {
                        this.ppn = 0;
                    }

                    this.totalFinal = this.totalPembelian + this.ppn;
                },
                formatCurrency(value) {
                    if(isNaN(value)) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },
                init() {
                    this.calculateTotals();
                    this.$watch('details', () => this.calculateTotals(), { deep: true });
                }
            }
        }
    </script>
</x-app-layout>
