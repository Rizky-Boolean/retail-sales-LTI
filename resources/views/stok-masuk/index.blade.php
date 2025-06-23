<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Stok Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Tombol Tambah -->
                    <div class="mb-4">
                        <a href="{{ route('stok-masuk.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Catat Stok Masuk Baru
                        </a>
                    </div>

                    <!-- Menampilkan Pesan Alert -->
                    @include('partials.alert-messages')

                    <!-- Tabel Histori -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">#ID</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Supplier</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Total Final</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse($stokMasuks as $stokMasuk)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="text-left py-3 px-4">TR-{{ $stokMasuk->id }}</td>
                                        <td class="text-left py-3 px-4">{{ \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->format('d M Y') }}</td>
                                        <td class="text-left py-3 px-4">{{ $stokMasuk->supplier->nama_supplier ?? 'N/A' }}</td>
                                        <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($stokMasuk->total_final, 0, ',', '.') }}</td>
                                        <td class="text-center py-3 px-4">
                                            <a href="{{ route('stok-masuk.show', $stokMasuk->id) }}" class="text-blue-500 hover:text-blue-700 font-bold py-1 px-3 rounded">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Belum ada data stok masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginasi -->
                    <div class="mt-4">
                        {{ $stokMasuks->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
