<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Data Sparepart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4">
                        <a href="{{ route('spareparts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Sparepart
                        </a>
                    </div>
                    
                    {{-- Menampilkan pesan alert --}}
                    @include('partials.alert-messages')

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kode Part</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Part</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Satuan</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Harga Jual</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse($spareparts as $sparepart)
                                    <tr class="border-b">
                                        <td class="text-left py-3 px-4">{{ $sparepart->kode_part }}</td>
                                        <td class="text-left py-3 px-4">{{ $sparepart->nama_part }}</td>
                                        <td class="text-left py-3 px-4">{{ $sparepart->satuan }}</td>
                                        <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($sparepart->harga_jual, 0, ',', '.') }}</td>
                                        <td class="text-center py-3 px-4">
                                            <a href="{{ route('spareparts.edit', $sparepart->id) }}" class="text-yellow-500 hover:text-yellow-700 font-bold py-1 px-3 rounded">Edit</a>
                                            <form action="{{ route('spareparts.destroy', $sparepart->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold py-1 px-3 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Tidak ada data sparepart.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $spareparts->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>