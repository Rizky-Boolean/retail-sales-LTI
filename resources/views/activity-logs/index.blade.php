<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Log Aktivitas Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 text-sm">
                            <thead class="bg-gray-200 dark:bg-gray-700">
                                <tr>
                                    <th class="py-2 px-3 text-left">Waktu</th>
                                    <th class="py-2 px-3 text-left">Pengguna</th>
                                    <th class="py-2 px-3 text-left">Deskripsi Aktivitas</th>
                                    <th class="py-2 px-3 text-left">Alamat IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-3 whitespace-nowrap">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                        <td class="py-2 px-3">{{ $log->user->name ?? 'Sistem' }}</td>
                                        <td class="py-2 px-3">{{ $log->description }}</td>
                                        <td class="py-2 px-3">{{ $log->ip_address }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4">Tidak ada aktivitas yang tercatat.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $logs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
