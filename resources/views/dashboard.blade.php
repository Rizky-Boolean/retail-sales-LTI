<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ================================================================= --}}
            {{-- TAMPILAN UNTUK ADMIN GUDANG INDUK --}}
            {{-- ================================================================= --}}
            @if(auth()->user()->role === 'admin_induk')
            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"><h3 class="text-gray-500">Total Jenis Sparepart</h3><p class="text-3xl font-bold">{{ $totalSparepart ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"><h3 class="text-gray-500">Total Supplier</h3><p class="text-3xl font-bold">{{ $totalSupplier ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"><h3 class="text-gray-500">Total Cabang</h3><p class="text-3xl font-bold">{{ $totalCabang ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md"><h3 class="text-gray-500">Total Stok Seluruh Gudang</h3><p class="text-3xl font-bold">{{ number_format($totalStokSeluruhGudang ?? 0) }}</p></div>
            </div>

            {{-- Grafik --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="font-semibold mb-4">Pengeluaran Pembelian (6 Bulan Terakhir)</h3>
                    <canvas id="pengeluaranChart"></canvas>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="font-semibold mb-4">Keuntungan Penjualan Cabang (6 Bulan Terakhir)</h3>
                    <canvas id="keuntunganChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Daftar Stok Hampir Habis --}}
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="font-semibold mb-4">Stok Gudang Induk Hampir Habis (Stok < 10)</h3>
                    <ul class="space-y-2">
                        @forelse($stokHampirHabis as $item)
                        <li class="flex justify-between items-center text-sm"><span>{{ $item->nama_part }}</span><span class="font-bold text-red-500">{{ $item->stok_induk }}</span></li>
                        @empty
                        <li class="text-sm text-gray-500">Tidak ada stok yang hampir habis.</li>
                        @endforelse
                    </ul>
                </div>
                {{-- Grafik Distribusi Stok --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="font-semibold mb-4">Distribusi Stok per Cabang</h3>
                    {{-- [UBAH] Tambahkan div pembungkus untuk mengontrol tinggi --}}
                    <div class="relative h-80">
                        <canvas id="distribusiStokChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            {{-- ================================================================= --}}
            {{-- TAMPILAN UNTUK ADMIN GUDANG CABANG --}}
            {{-- ================================================================= --}}
            @if(auth()->user()->role === 'admin_cabang')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Stat Card Penjualan Hari Ini -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Penjualan Hari Ini</h3>
                            <p class="text-3xl font-bold text-green-500">{{ 'Rp ' . number_format($penjualanHariIni ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                     <!-- Stat Card Keuntungan Bulan Ini -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Keuntungan Bulan Ini</h3>
                            <p class="text-3xl font-bold text-blue-500">{{ 'Rp ' . number_format($keuntunganBulanIni ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                     <!-- Stat Card Kiriman Menunggu -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Kiriman Menunggu</h3>
                            <p class="text-3xl font-bold text-yellow-500">{{ $kirimanMenunggu ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                 <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Daftar Stok Hampir Habis -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="font-semibold mb-4">Stok Cabang Hampir Habis (Stok < 10)</h3>
                            <ul class="space-y-2">
                                @forelse($stokHampirHabis as $item)
                                <li class="flex justify-between items-center text-sm">
                                    <span>{{ $item->nama_part }}</span>
                                    <span class="font-bold text-red-500">{{ $item->pivot->stok }}</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500">Tidak ada stok yang hampir habis.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <!-- Daftar Penjualan Terbaru -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="font-semibold mb-4">5 Penjualan Terakhir</h3>
                            <ul class="space-y-2">
                                @forelse($penjualanTerbaru as $penjualan)
                                <li class="flex justify-between items-center text-sm">
                                    <span><a href="{{ route('penjualan.show', $penjualan) }}" class="text-blue-500 hover:underline">{{ $penjualan->nomor_nota }}</a></span>
                                    <span class="text-gray-500">{{ $penjualan->created_at->diffForHumans() }}</span>
                                </li>
                                @empty
                                <li class="text-sm text-gray-500">Belum ada transaksi penjualan.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(auth()->user()->role === 'admin_induk')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data dari Controller
            const pengeluaranData = @json($chartPengeluaran);
            const keuntunganData = @json($chartKeuntungan);
            const distribusiStokData = @json($chartDistribusiStok);

            // Fungsi untuk membuat label bulan
            const last6Months = Array.from({length: 6}, (v, i) => {
                const d = new Date();
                d.setMonth(d.getMonth() - i);
                return d.toLocaleString('id-ID', { month: 'short' });
            }).reverse();

            // Memproses data agar sesuai dengan label
            const processData = (data, labels) => {
                const dataMap = new Map(data.map(item => [item.bulan, item.total || item.total_profit]));
                return labels.map(label => dataMap.get(label) || 0);
            };

            // 1. Grafik Pengeluaran
            new Chart(document.getElementById('pengeluaranChart'), {
                type: 'bar',
                data: {
                    labels: last6Months,
                    datasets: [{
                        label: 'Total Pengeluaran (Rp)',
                        data: processData(pengeluaranData, last6Months),
                        backgroundColor: 'rgba(239, 68, 68, 0.5)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }]
                },
                options: { scales: { y: { beginAtZero: true } } }
            });

            // 2. Grafik Keuntungan
            new Chart(document.getElementById('keuntunganChart'), {
                type: 'line',
                data: {
                    labels: last6Months,
                    datasets: [{
                        label: 'Total Keuntungan (Rp)',
                        data: processData(keuntunganData, last6Months),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: { scales: { y: { beginAtZero: true } } }
            });

            // 3. Grafik Distribusi Stok
            new Chart(document.getElementById('distribusiStokChart'), {
                type: 'doughnut',
                data: {
                    labels: distribusiStokData.map(item => item.nama_cabang),
                    datasets: [{
                        label: 'Total Stok',
                        data: distribusiStokData.map(item => item.total_stok),
                        backgroundColor: ['rgba(236, 72, 153, 0.7)', 'rgba(168, 85, 247, 0.7)', 'rgba(34, 197, 94, 0.7)', 'rgba(245, 158, 11, 0.7)'],
                    }]
                },
                // [UBAH] Tambahkan opsi ini untuk menyesuaikan ukuran
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        });
    </script>
    @endif
</x-app-layout>