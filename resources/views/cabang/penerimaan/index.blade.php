<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Penerimaan Barang dari Gudang Induk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <h3 class="text-lg font-semibold mb-4">Daftar Kiriman Masuk</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Sukses!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">ID Kiriman</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal Kirim</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Dikirim Oleh</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Total Nilai Barang</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse($kirimanMasuk as $kiriman)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="text-left py-3 px-4">DIST-{{ $kiriman->id }}</td>
                                        <td class="text-left py-3 px-4">{{ \Carbon\Carbon::parse($kiriman->tanggal_distribusi)->format('d M Y') }}</td>
                                        <td class="text-left py-3 px-4">{{ $kiriman->user->name ?? 'N/A' }}</td>
                                        <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($kiriman->total_harga_kirim, 0, ',', '.') }}</td>
                                        <td class="text-center py-3 px-4">
                                            <a href="{{ route('distribusi.show', $kiriman->id) }}" target="_blank" class="text-blue-500 hover:text-blue-700 font-bold py-1 px-3 rounded">Detail</a>
                                            <form action="{{ route('cabang.penerimaan.terima', $kiriman->id) }}" method="POST" class="inline-block ml-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded" onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi penerimaan barang ini?')">Terima Barang</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Tidak ada kiriman barang yang menunggu untuk diterima.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $kirimanMasuk->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
