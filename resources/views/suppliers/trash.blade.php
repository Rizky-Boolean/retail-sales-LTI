<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Supplier Terhapus') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Tombol Kembali --}}
                    <div class="mb-6">
                        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-semibold rounded-lg transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            {{ __('Kembali ke Master Supplier') }}
                        </a>
                    </div>

                    {{-- Partial untuk pesan notifikasi --}}
                    @include('partials.alert-messages')

                    {{-- Tabel Data --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800">
                                <thead class="bg-gray-200 dark:bg-gray-700">
                                    <tr>
                                        <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Nama Supplier</th>
                                        <th class="py-3 px-4 text-left uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Tanggal Dihapus</th>
                                        <th class="py-3 px-4 text-center uppercase font-semibold text-xs text-gray-800 dark:text-gray-500 tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suppliers as $supplier)
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="py-3 px-4">{{ $supplier->nama_supplier }}</td>
                                            <td class="py-3 px-4">{{ $supplier->deleted_at->format('d M Y, H:i') }}</td>
                                            <td class="py-3 px-4 text-center">
                                                {{-- Tombol Aksi dengan Ikon (Desain Baru) --}}
                                                <div class="flex justify-center items-center gap-4">
                                                    <form action="{{ route('suppliers.restore', $supplier->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="flex items-center gap-1 text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300" title="Pulihkan">
                                                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                                            <span>Pulihkan</span>
                                                        </button>
                                                    </form>
    
                                                    <button type="button" onclick="showDeleteModal('{{ route('suppliers.forceDelete', $supplier->id) }}', '{{ addslashes($supplier->nama_supplier) }}')" class="flex items-center gap-1 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Hapus Permanen">
                                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                                        <span>Hapus Permanen</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">Tidak ada data di dalam trash.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    {{-- Paginasi --}}
                    <div class="mt-6">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-auto">
            <div class="text-center">
                <svg class="mx-auto mb-4 h-12 w-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <h3 id="deleteModalTitle" class="mb-5 text-lg font-normal text-gray-800 dark:text-gray-300">Apakah Anda yakin?</h3>
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="hideDeleteModal()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteModalTitle = document.getElementById('deleteModalTitle');

        function showDeleteModal(actionUrl, itemName) {
            deleteForm.action = actionUrl;
            deleteModalTitle.innerHTML = `Anda yakin ingin menghapus <strong>${itemName}</strong> secara permanen? Aksi ini tidak dapat dibatalkan.`;
            deleteModal.classList.remove('hidden');
        }

        function hideDeleteModal() {
            deleteModal.classList.add('hidden');
        }

        // Opsional: Menutup modal jika user mengklik area luar modal
        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                hideDeleteModal();
            }
        });
    </script>
</x-app-layout>