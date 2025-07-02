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
                          x-data="itemDetails({{ json_encode(old('details', [['sparepart_id' => '', 'qty' => 1, 'harga_beli_satuan' => 0]])) }})"
                          x-init="init()">
                        @csrf

                        {{-- Header Form --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                            <div>
                                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" />
                                <x-text-input id="tanggal_masuk" name="tanggal_masuk" type="date" class="mt-1 block w-full"
                                    :value="old('tanggal_masuk', date('Y-m-d'))" required />
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
                                        <th class="py-3 px-4 text-left w-24">Qty</th>
                                        <th class="py-3 px-4 text-left w-48">Harga Beli Satuan</th>
                                        <th class="py-3 px-4 text-left w-48">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(detail, index) in details" :key="index">
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="px-4 py-2">
                                                <select :name="`details[${index}][sparepart_id]`" x-model="detail.sparepart_id"
                                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    required>
                                                    <option value="">-- Pilih Sparepart --</option>
                                                    @foreach($spareparts as $sparepart)
                                                        <option value="{{ $sparepart->id }}">{{ $sparepart->kode_part }} - {{ $sparepart->nama_part }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="`details[${index}][qty]`" x-model.number="detail.qty"
                                                    @input="calculateTotals()"
                                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                                    min="1" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="`details[${index}][harga_beli_satuan]`" x-model.number="detail.harga_beli_satuan"
                                                    @input="calculateTotals()"
                                                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500"
                                                    min="0" required>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="text" :value="formatCurrency(detail.qty * detail.harga_beli_satuan)"
                                                    class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-gray-700 dark:text-gray-200"
                                                    readonly>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="py-3 px-4">
                                            <button type="button" @click="addDetail()"
                                                class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                                                + Tambah Item
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-bold py-2 px-4">Total Pembelian</td>
                                        <td class="py-2 px-4 font-medium">
                                            <span x-text="formatCurrency(totalPembelian)"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right font-bold py-2 px-4">PPN 11% (jika > 100rb)</td>
                                        <td class="py-2 px-4">
                                            <span x-text="formatCurrency(ppn)"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right text-lg font-bold py-2 px-4">Total Final</td>
                                        <td class="py-2 px-4 text-lg font-bold">
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
        function itemDetails(initialDetails) {
            return {
                details: initialDetails,
                totalPembelian: 0,
                ppn: 0,
                totalFinal: 0,

                addDetail() {
                    this.details.push({ sparepart_id: '', qty: 1, harga_beli_satuan: 0 });
                },
                calculateTotals() {
                    this.totalPembelian = this.details.reduce((acc, detail) => {
                        const qty = parseFloat(detail.qty) || 0;
                        const harga = parseFloat(detail.harga_beli_satuan) || 0;
                        return acc + (qty * harga);
                    }, 0);

                    this.ppn = this.totalPembelian > 100000 ? this.totalPembelian * 0.11 : 0;
                    this.totalFinal = this.totalPembelian + this.ppn;
                },
                formatCurrency(value) {
                    if (isNaN(value)) return 'Rp 0';
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
