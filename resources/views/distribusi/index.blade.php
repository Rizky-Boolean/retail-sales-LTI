<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Distribusi ke Cabang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Tombol Tambah -->
                    <div class="mb-4">
                        <a href="{{ route('distribusi.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Buat Distribusi Baru
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
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Cabang Tujuan</th>
                                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">Total Kirim</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-200">
                                @forelse($distribusis as $distribusi)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="text-left py-3 px-4">DIST-{{ $distribusi->id }}</td>
                                        <td class="text-left py-3 px-4">{{ \Carbon\Carbon::parse($distribusi->tanggal_distribusi)->format('d M Y') }}</td>
                                        <td class="text-left py-3 px-4">{{ $distribusi->cabangTujuan->nama_cabang ?? 'N/A' }}</td>
                                        <td class="text-right py-3 px-4">{{ 'Rp ' . number_format($distribusi->total_harga_kirim, 0, ',', '.') }}</td>
                                        <td class="text-center py-3 px-4">
                                            <span class="capitalize px-2 py-1 text-xs font-bold rounded {{ $distribusi->status == 'dikirim' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }}">
                                                {{ $distribusi->status }}
                                            </span>
                                        </td>
                                        <td class="text-center py-3 px-4">
                                            <a href="{{ route('distribusi.show', $distribusi->id) }}" class="text-blue-500 hover:text-blue-700 font-bold py-1 px-3 rounded">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Belum ada data distribusi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginasi -->
                    <div class="mt-4">
                        {{ $distribusis->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
