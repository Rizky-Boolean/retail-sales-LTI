<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Supplier Nonaktif') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- [UBAH] Tombol Kembali disesuaikan dengan gaya baru --}}
                    <div class="mb-6">
                        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-semibold rounded-lg transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            {{ __('Kembali ke Master Supplier') }}
                        </a>
                    </div>

                    @include('partials.alert-messages')

                    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Supplier</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">{{ $supplier->nama_supplier }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <form action="{{ route('suppliers.toggleStatus', $supplier) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-500 hover:text-green-700 py-1 px-3 rounded">Aktifkan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4">Tidak ada data supplier nonaktif.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $suppliers->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
