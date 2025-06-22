<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Transaksi Penjualan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('penjualan.store') }}" method="POST"
                          x-data="salesForm({{ json_encode($spareparts) }})">
                        @csrf
                        <!-- Header Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="tanggal_penjualan" :value="__('Tanggal Penjualan')" />
                                <x-text-input id="tanggal_penjualan" name="tanggal_penjualan" type="date" class="mt-1 block w-full" :value="old('tanggal_penjualan', date('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="nama_pembeli" :value="__('Nama Pembeli (Opsional)')" />
                                <x-text-input id="nama_pembeli" name="nama_pembeli" type="text" class="mt-1 block w-full" :value="old('nama_pembeli', 'Customer Retail')" />
                            </div>
                        </div>

                        <!-- Detail Item Penjualan -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">Barang Penjualan</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-900">
                                    <thead class="bg-gray-200 dark:bg-gray-700">
                                        <tr>
                                            <th class="py-2 px-3">Sparepart</th>
                                            <th class="py-2 px-3 w-24">Qty</th>
                                            <th class="py-2 px-3 w-48">Harga Jual Satuan</th>
                                            <th class="py-2 px-3 w-48">Subtotal</th>
                                            <th class="py-2 px-3 w-12"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <td>
                                                    <select :name="`details[${index}][sparepart_id]`" x-model="item.sparepart_id" @change="updateItemData(index)" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                                        <option value="">-- Pilih Sparepart --</option>
                                                        <template x-for="sparepart in spareparts" :key="sparepart.id">
                                                            <option :value="sparepart.id" x-text="`${sparepart.nama_part} (Stok: ${sparepart.pivot.stok})`"></option>
                                                        </template>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" :name="`details[${index}][qty]`" x-model.number="item.qty" class="w-full" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="text" :value="formatCurrency(item.harga_jual)" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" :value="formatCurrency(item.qty * item.harga_jual)" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5"><button type="button" @click="addItem()" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Tambah Barang</button></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right font-bold py-2 px-3">Total</td>
                                            <td class="py-2 px-3 font-semibold" colspan="2"><span x-text="formatCurrency(total)"></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right font-bold py-2 px-3">
                                                <label for="ppn_dikenakan" class="flex items-center justify-end">
                                                    <input type="checkbox" id="ppn_dikenakan" name="ppn_dikenakan" x-model="ppnDikenakan" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                    <span class="ms-2">PPN 11%</span>
                                                </label>
                                            </td>
                                            <td class="py-2 px-3 font-semibold" colspan="2"><span x-text="formatCurrency(ppnAmount)"></span></td>
                                        </tr>
                                        <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                            <td colspan="3" class="text-right font-bold py-2 px-3 text-lg">Grand Total</td>
                                            <td class="py-2 px-3 text-lg font-bold" colspan="2"><span x-text="formatCurrency(grandTotal)"></span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('penjualan.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Batal</a>
                            <x-primary-button>{{ __('Simpan Penjualan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function salesForm(spareparts) {
            return {
                spareparts: spareparts,
                items: [{ sparepart_id: '', qty: 1, harga_jual: 0 }],
                ppnDikenakan: false,
                total: 0,
                ppnAmount: 0,
                grandTotal: 0,

                init() { this.$watch('items', () => this.calculateTotals(), { deep: true }); this.$watch('ppnDikenakan', () => this.calculateTotals()); },
                addItem() { this.items.push({ sparepart_id: '', qty: 1, harga_jual: 0 }); },
                removeItem(index) { this.items.splice(index, 1); },
                updateItemData(index) {
                    const selectedId = this.items[index].sparepart_id;
                    const selectedSparepart = this.spareparts.find(s => s.id == selectedId);
                    this.items[index].harga_jual = selectedSparepart ? selectedSparepart.harga_jual : 0;
                },
                calculateTotals() {
                    this.total = this.items.reduce((acc, item) => acc + (item.qty * item.harga_jual), 0);
                    this.ppnAmount = this.ppnDikenakan ? this.total * 0.11 : 0;
                    this.grandTotal = this.total + this.ppnAmount;
                },
                formatCurrency(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value); },
            }
        }
    </script>
</x-app-layout>
