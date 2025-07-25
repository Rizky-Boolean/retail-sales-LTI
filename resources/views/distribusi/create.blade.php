<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Distribusi Baru ke Cabang') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Error dari Controller --}}
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Error Validasi --}}
                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong class="font-bold">Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('distribusi.store') }}" method="POST"
                      x-data="distributionForm({{ json_encode($spareparts) }})">
                    @csrf

                    {{-- Header Form --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <x-input-label for="tanggal_distribusi" :value="__('Tanggal Distribusi')" />
                            {{-- [MODIFIKASI] Tambahkan atribut max untuk validasi tanggal --}}
                            <x-text-input id="tanggal_distribusi" name="tanggal_distribusi" type="date" class="mt-1 block w-full" 
                                          :value="old('tanggal_distribusi', date('Y-m-d'))" max="{{ date('Y-m-d') }}" required />
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

                    {{-- Detail Barang --}}
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-3">Detail Barang Kiriman</h3>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs uppercase font-bold">
                                <tr>
                                    <th class="py-3 px-4 text-left w-1/3">Sparepart</th>
                                    <th class="py-3 px-4 text-left w-28">Stok Induk</th>
                                    <th class="py-3 px-4 text-left w-28">Qty Kirim</th>
                                    <th class="py-3 px-4 text-right w-32">Harga Kirim</th>
                                    <th class="py-3 px-4 text-right w-32">Subtotal</th>
                                    <th class="py-3 px-4 w-12"></th> {{-- Kolom untuk tombol hapus --}}
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="py-3 px-4">
                                            <select :name="`details[${index}][sparepart_id]`" x-model="item.sparepart_id" @change="updateItemData(index)" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                                <option value="">-- Pilih Sparepart --</option>
                                                <template x-for="sparepart in spareparts" :key="sparepart.id">
                                                    {{-- [MODIFIKASI] Tampilkan kode part sebelum nama sparepart --}}
                                                    <option 
                                                        :value="sparepart.id" 
                                                        x-text="`${sparepart.kode_part} - ${sparepart.nama_part}`"
                                                        :disabled="isSparepartSelected(sparepart.id) && item.sparepart_id != sparepart.id">
                                                    </option>
                                                </template>
                                            </select>
                                        </td>
                                        <td class="py-3 px-4">
                                            <input type="text" :value="item.stok_induk" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" readonly>
                                        </td>
                                        <td class="py-3 px-4">
                                            <input type="number" :name="`details[${index}][qty]`" x-model.number="item.qty" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" min="1" required>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <input type="text" :value="formatCurrency(item.harga_kirim)" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-right" readonly>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <input type="text" :value="formatCurrency(item.qty * item.harga_kirim)" class="w-full bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-right" readonly>
                                        </td>
                                        {{-- [MODIFIKASI] Tambahkan tombol hapus --}}
                                        <td class="py-3 px-4 text-center">
                                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="py-3 px-4">
                                        <button type="button" @click="addItem()" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            + Tambah Item
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                         <<div class="flex items-center justify-end mt-4">
                            <a href="{{ route('distribusi.index') }}" class="inline-flex items-center justify-center px-6 py-2 border border-transparent font-medium text-red-600 dark:text-red-500 hover:underline inline-block mx-1">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                {{ __('Simpan') }}
                            </button>
                        </div>

                </form>

            </div>
        </div>
    </div>

    {{-- [MODIFIKASI] Logika Alpine.js diperbarui --}}
    <script>
        function distributionForm(spareparts) {
            return {
                spareparts: spareparts,
                items: [{ sparepart_id: '', qty: 1, stok_induk: 0, harga_kirim: 0 }],

                addItem() {
                    this.items.push({ sparepart_id: '', qty: 1, stok_induk: 0, harga_kirim: 0 });
                },
                
                removeItem(index) {
                    // Hanya hapus jika ada lebih dari satu baris
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                
                updateItemData(index) {
                    const selectedId = this.items[index].sparepart_id;
                    const selectedSparepart = this.spareparts.find(s => s.id == selectedId);

                    if (selectedSparepart) {
                        this.items[index].stok_induk = selectedSparepart.stok_induk;
                        // [UBAH] Harga kirim sekarang sama dengan harga modal, tanpa dikali 1.11
                        this.items[index].harga_kirim = selectedSparepart.harga_modal_terakhir;
                    } else {
                        this.items[index].stok_induk = 0;
                        this.items[index].harga_kirim = 0;
                    }
                },

                isSparepartSelected(sparepartId) {
                    return this.items.some(item => item.sparepart_id == sparepartId);
                },

                formatCurrency(value) {
                    if (isNaN(value)) return 'Rp 0';
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);
                },
            }
        }
    </script>
</x-app-layout>
