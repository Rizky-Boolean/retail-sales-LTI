<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Penerimaan Barang dari Gudang Induk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8 border border-gray-200 dark:border-gray-700">

                {{-- Heading --}}
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    Daftar Kiriman Masuk
                </h3>

                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                        <strong class="font-bold">Sukses!</strong>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Tabel Kiriman --}}
                <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase font-bold">
                            <tr>
                                <th class="py-3 px-4 text-left">ID Kiriman</th>
                                <th class="py-3 px-4 text-left">Tanggal Kirim</th>
                                <th class="py-3 px-4 text-left">Dikirim Oleh</th>
                                <th class="py-3 px-4 text-right">Total Nilai Barang</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @forelse($kirimanMasuk as $kiriman)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="py-3 px-4">DIST-{{ $kiriman->id }}</td>
                                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($kiriman->tanggal_distribusi)->format('d M Y') }}</td>
                                    <td class="py-3 px-4">{{ $kiriman->user->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-right">{{ 'Rp ' . number_format($kiriman->total_harga_kirim, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center space-x-2">

                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('distribusi.show', $kiriman->id) }}" target="_blank"
                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-150 ease-in-out">
                                            Detail
                                        </a>

                                        {{-- Tombol Terima Barang + Modal --}}
                                        <div x-data="{ open: false }" class="inline-block">
                                            <button @click="open = true"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition duration-150 ease-in-out">
                                                Terima Barang
                                            </button>

                                            {{-- Modal Konfirmasi --}}
                                            <template x-if="open">
                                                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                                                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-sm w-full">
                                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 text-center mb-3">Konfirmasi Penerimaan</h2>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 text-center mb-5">
                                                            Apakah Anda yakin ingin mengonfirmasi penerimaan barang ini?
                                                        </p>
                                                        <div class="flex justify-center space-x-3">
                                                            <button @click="open = false"
                                                                class="px-4 py-2 text-sm bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-600">
                                                                Batal
                                                            </button>
                                                            <form method="POST" action="{{ route('cabang.penerimaan.terima', $kiriman->id) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="px-4 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                                                    Ya, Terima
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        Tidak ada kiriman barang yang menunggu untuk diterima.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $kirimanMasuk->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
