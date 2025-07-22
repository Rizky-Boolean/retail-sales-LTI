<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Stok Gudang Induk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kode Part</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Part</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Stok Tersedia</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse($spareparts as $sparepart)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="text-left py-3 px-4">{{ $sparepart->kode_part }}</td>
                                        <td class="text-left py-3 px-4">{{ $sparepart->nama_part }}</td>
                                        <td class="text-center py-3 px-4 font-bold">{{ $sparepart->stok_induk }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">Tidak ada data sparepart di gudang induk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginasi -->
                    <div class="mt-4">
                        {{ $spareparts->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
