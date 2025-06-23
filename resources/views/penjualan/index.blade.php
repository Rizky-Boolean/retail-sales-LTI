<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <a href="{{ route('penjualan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Buat Transaksi Baru
                        </a>
                    </div>

                    <!-- Menampilkan Pesan Alert -->
                    @include('partials.alert-messages')

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">No. Nota</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Pembeli</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Total</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse($penjualans as $penjualan)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="text-left py-3 px-4">{{ $penjualan->nomor_nota }}</td>
                                        <td class="text-left py-3 px-4">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d M Y') }}</td>
                                        <td class="text-left py-3 px-4">{{ $penjualan->nama_pembeli }}</td>
                                        <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($penjualan->total_final, 0, ',', '.') }}</td>
                                        <td class="text-center py-3 px-4">
                                            <a href="{{ route('penjualan.show', $penjualan->id) }}" class="text-blue-500 hover:text-blue-700 font-bold py-1 px-3 rounded">Nota</a>
                                            <form action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" class="inline-block ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold py-1 px-3 rounded" onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok akan dikembalikan.')">Batal</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Belum ada transaksi penjualan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $penjualans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
