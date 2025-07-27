<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Cabang Nonaktif') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('cabangs.index') }}" class="text-blue-500 hover:text-blue-700">
                            &larr; Kembali ke Daftar Cabang
                        </a>
                    </div>
                    @include('partials.alert-messages')
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-3 px-4 text-left">Nama Cabang</th>
                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cabangs as $cabang)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-3 px-4">{{ $cabang->nama_cabang }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <form action="{{ route('cabangs.toggleStatus', $cabang) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-500 hover:text-green-700 font-bold py-1 px-3 rounded">Aktifkan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4">Tidak ada data cabang nonaktif.</td></tr>
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