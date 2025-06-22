<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Distribusi Baru ke Cabang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Menampilkan Error Umum dari Controller --}}
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    {{-- Menampilkan Error Validasi (misal: stok tidak cukup) --}}
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

                    <form action="{{ route('distribusi.store') }}" method="POST"
                          x-data="distributionForm({{ json_encode($spareparts) }})">
                        @csrf
                        <!-- Bagian Header Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="tanggal_distribusi" :value="__('Tanggal Distribusi')" />
                                <x-text-input id="tanggal_distribusi" name="tanggal_distribusi" type="date" class="mt-1 block w-full" :value="old('tanggal_distribusi', date('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="cabang_id_tujuan" :value="__('Kirim ke Cabang')" />
                                <select id="cabang_id_tujuan" name="cabang_id_tujuan" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Cabang Tujuan --</option>
                                    @foreach($cabangs as $cabang)
                                        <option value="{{ $cabang->id }}" {{ old('cabang_id_tujuan') == $cabang->id ? 'selected' : '' }}>{{ $cabang->nama_cabang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Bagian Detail Item (Dynamic Form dengan Alpine.js) -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200 mb-2">Detail Barang Kiriman</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-900">
                                    <thead class="bg-gray-200 dark:bg-gray-700">
                                        <tr>
                                            <th class="text-left py-2 px-3">Sparepart</th>
                                            <th class="text-left py-2 px-3 w-40">Stok Induk</th>
                                            <th class="text-left py-2 px-3 w-24">Qty Kirim</th>
                                            <th class="text-left py-2 px-3 w-48">Harga Kirim Satuan</th>
                                            <th class="text-left py-2 px-3 w-48">Subtotal</th>
                                            <th class="py-2 px-3 w-12"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                                <td>
                                                    <select :name="`details[${index}][sparepart_id]`" x-model="item.sparepart_id" @change="updateItemData(index)" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                                        <option value="">-- Pilih Sparepart --</option>
                                                        <template x-for="sparepart in spareparts" :key="sparepart.id">
                                                            <option :value="sparepart.id" x-text="sparepart.nama_part"></option>
                                                        </template>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" :value="item.stok_induk" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" :name="`details[${index}][qty]`" x-model.number="item.qty" class="w-full" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="text" :value="formatCurrency(item.harga_kirim)" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" :value="formatCurrency(item.qty * item.harga_kirim)" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <button type="button" @click="addItem()" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    + Tambah Item
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('distribusi.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Kirim Barang') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function distributionForm(spareparts) {
            return {
                spareparts: spareparts,
                items: [{ sparepart_id: '', qty: 1, stok_induk: 0, harga_kirim: 0 }],
                
                addItem() {
                    this.items.push({ sparepart_id: '', qty: 1, stok_induk: 0, harga_kirim: 0 });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                updateItemData(index) {
                    const selectedId = this.items[index].sparepart_id;
                    const selectedSparepart = this.spareparts.find(s => s.id == selectedId);
                    
                    if (selectedSparepart) {
                        this.items[index].stok_induk = selectedSparepart.stok_induk;
                        this.items[index].harga_kirim = selectedSparepart.harga_modal_terakhir * 1.11;
                    } else {
                        this.items[index].stok_induk = 0;
                        this.items[index].harga_kirim = 0;
                    }
                },
                formatCurrency(value) {
                    if(isNaN(value)) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },
            }
        }
    </script>
</x-app-layout>
