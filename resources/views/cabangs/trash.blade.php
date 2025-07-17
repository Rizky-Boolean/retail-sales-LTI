<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Cabang Terhapus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('cabangs.index') }}" class="text-blue-500 hover:text-blue-700">
                            &larr; Kembali ke Manajemen Cabang
                        </a>
                    </div>
                    @include('partials.alert-messages')
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">Nama Cabang</th>
                                    <th class="py-3 px-4 text-left">Tanggal Dihapus</th>
                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cabangs as $cabang)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">{{ $cabang->nama_cabang }}</td>
                                        <td class="py-3 px-4">{{ $cabang->deleted_at->format('d M Y, H:i') }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <form action="{{ route('cabangs.restore', $cabang->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-500 hover:text-green-700 font-bold py-1 px-3 rounded">Restore</button>
                                            </form>
                                            <form action="{{ route('cabangs.forceDelete', $cabang->id) }}" method="POST" class="inline-block ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold py-1 px-3 rounded" onclick="return confirm('PERINGATAN: Aksi ini tidak dapat dibatalkan. Anda yakin ingin menghapus data ini secara permanen?')">Hapus Permanen</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4">Tidak ada data di dalam trash.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $cabangs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
