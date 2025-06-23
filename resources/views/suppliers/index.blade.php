<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Master Data Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Tombol Tambah Supplier -->
                    <div class="mb-4">
                        <a href="{{ route('suppliers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Supplier
                        </a>
                    </div>

                    <!-- Menampilkan Pesan Alert -->
                    @include('partials.alert-messages')

                    <!-- Tabel Data Supplier -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Supplier</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Alamat</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kontak</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse($suppliers as $supplier)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="text-left py-3 px-4">{{ $supplier->nama_supplier }}</td>
                                        <td class="text-left py-3 px-4">{{ $supplier->alamat }}</td>
                                        <td class="text-left py-3 px-4">{{ $supplier->kontak }}</td>
                                        <td class="text-center py-3 px-4">
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-yellow-500 hover:text-yellow-700 font-bold py-1 px-3 rounded">Edit</a>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold py-1 px-3 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus data supplier ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Tidak ada data supplier.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginasi -->
                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
