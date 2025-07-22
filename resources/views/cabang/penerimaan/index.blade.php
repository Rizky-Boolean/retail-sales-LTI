<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Kiriman Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ showModal: false, actionUrl: '' }">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        
                        <h3 class="text-lg font-semibold mb-4">Daftar Semua Kiriman dari Gudang Induk</h3>
                        @include('partials.alert-messages')

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800">
                                <thead class="bg-gray-200 dark:bg-gray-700">
                                    <tr>
                                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">ID Kiriman</th>
                                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tanggal Kirim</th>
                                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Dikirim Oleh</th>
                                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                        <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 dark:text-gray-200">
                                    @forelse($kirimanMasuk as $kiriman)
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="py-3 px-4">DIST-{{ $kiriman->id }}</td>
                                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($kiriman->tanggal_distribusi)->format('d M Y') }}</td>
                                            <td class="py-3 px-4">{{ $kiriman->user->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-4 text-center">
                                                {{-- [START] Kode Status Badge yang Diperbaiki --}}
                                                @php
                                                    $statusClass = '';
                                                    if ($kiriman->status == 'dikirim') $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-200';
                                                    elseif ($kiriman->status == 'diterima') $statusClass = 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200';
                                                    elseif ($kiriman->status == 'ditolak') $statusClass = 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200';
                                                @endphp
                                                <span class="capitalize px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                                    {{ $kiriman->status }}
                                                </span>
                                                {{-- [END] Kode Status Badge yang Diperbaiki --}}
                                            </td>
                                            <td class="text-center py-3 px-4">
                                                {{-- Tombol Aksi Dinamis --}}
                                                @if($kiriman->status == 'dikirim')
                                                    <form action="{{ route('cabang.penerimaan.terima', $kiriman->id) }}" method="POST" class="inline-block">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-green-500 hover:text-green-700 font-bold py-1 px-2 rounded" onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi penerimaan barang ini?')">Terima</button>
                                                    </form>
                                                    <button @click="showModal = true; actionUrl = '{{ route('cabang.penerimaan.tolak', $kiriman->id) }}'" 
                                                            class="text-red-500 hover:text-red-700 font-bold py-1 px-2 rounded ml-2">
                                                        Tolak
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                                @endif
                                                <a href="{{ route('distribusi.show', $kiriman->id) }}" target="_blank" class="text-blue-500 hover:text-blue-700 font-bold py-1 px-2 rounded ml-2">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-4">Tidak ada riwayat kiriman.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $kirimanMasuk->links() }}</div>
                    </div>
                </div>

                {{-- Modal untuk Alasan Penolakan --}}
                <div x-show="showModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                    <div @click.away="showModal = false" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-md">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Alasan Penolakan</h3>
                        <form :action="actionUrl" method="POST">
                            @csrf @method('PATCH')
                            <textarea name="alasan_penolakan" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md text-gray-900 dark:text-gray-100" required placeholder="Contoh: Jumlah barang tidak sesuai"></textarea>
                            <div class="mt-4 flex justify-end space-x-2">
                                <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded text-gray-800 dark:text-gray-200">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Tolak Kiriman</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
