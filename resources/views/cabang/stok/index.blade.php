<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Stok Sparepart Gudang Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Nama Cabang --}}
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    Gudang: {{ auth()->user()->cabang->nama_cabang }}
                </h3>

                {{-- Alert jika ada --}}
                @include('partials.alert-messages')

                {{-- Tabel Stok --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase font-bold">
                            <tr>
                                <th class="py-3 px-4 text-left">Kode Part</th>
                                <th class="py-3 px-4 text-left">Nama Part</th>
                                <th class="py-3 px-4 text-left">Harga Jual</th>
                                <th class="py-3 px-4 text-center">Jumlah Stok</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @forelse($spareparts as $sparepart)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="py-3 px-4 whitespace-nowrap">{{ $sparepart->kode_part }}</td>
                                    <td class="py-3 px-4">{{ $sparepart->nama_part }}</td>
                                    <td class="py-3 px-4">{{ 'Rp ' . number_format($sparepart->harga_jual, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center font-semibold">{{ $sparepart->pivot->stok }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Belum ada stok barang di gudang Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $spareparts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
    