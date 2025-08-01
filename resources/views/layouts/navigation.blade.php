<nav x-data="{ open: false }" class="bg-blue-900 border-b border-blue-800 dark:bg-gray-950 dark:border-gray-700 shadow-md"> {{-- Navbar berwarna navy, border sedikit lebih gelap, shadow --}}
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="p-2 bg-white rounded-full shadow-sm flex items-center justify-center"> {{-- Menambahkan background putih lingkaran untuk logo --}}
                        <img src="{{ asset('images/logo lautan teduh.png') }}" alt="PT Lautan Teduh Interniaga Logo" class="block h-8 w-auto object-contain"> {{-- Ukuran gambar disesuaikan agar pas dengan padding --}}
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Menu untuk Super Admin --}}
                    @if(auth()->user()->role === 'super_admin')
                        <x-nav-link :href="route('spareparts.index')" :active="request()->routeIs('spareparts.*')">Master Sparepart</x-nav-link>
                        <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">Master Supplier</x-nav-link>
                        <x-nav-link :href="route('stok-masuk.index')" :active="request()->routeIs('stok-masuk.*')">Pembelian</x-nav-link>
                        <x-nav-link :href="route('laporan.induk.index')" :active="request()->routeIs('laporan.induk.*')">Laporan</x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Manajemen User</x-nav-link>
                        <x-nav-link :href="route('cabangs.index')" :active="request()->routeIs('cabangs.*')">Manajemen Cabang</x-nav-link>
                        <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.index')">Log Aktivitas</x-nav-link>
                    @endif

                    {{-- Menu untuk Pengelola Gudang Induk (Super Admin & Admin Gudang Induk) --}}
                    @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang_induk']))
                        <x-nav-link :href="route('distribusi.index')" :active="request()->routeIs(['distribusi.index', 'distribusi.create', 'distribusi.show'])">
                            Distribusi
                        </x-nav-link>
                    @endif
                    
                    {{-- Menu Eksklusif Admin Gudang Induk --}}
                    @if(auth()->user()->role === 'admin_gudang_induk')
                        <x-nav-link :href="route('distribusi.stok.induk')" :active="request()->routeIs('distribusi.stok.induk')">
                            Stok Gudang Induk
                        </x-nav-link>
                    @endif

                    {{-- Menu untuk Admin Gudang Cabang --}}
                    @if(auth()->user()->role === 'admin_gudang_cabang')
                        <x-nav-link :href="route('cabang.stok.index')" :active="request()->routeIs('cabang.stok.index')">Stok Saya</x-nav-link>
                        <x-nav-link :href="route('cabang.penerimaan.index')" :active="request()->routeIs('cabang.penerimaan.index')">Kiriman Masuk</x-nav-link>
                        <x-nav-link :href="route('penjualan.index')" :active="request()->routeIs('penjualan.*')">Penjualan</x-nav-link>
                        <x-nav-link :href="route('laporan.cabang.index')" :active="request()->routeIs('laporan.cabang.*')">Laporan</x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- Bagian Notifikasi --}}
                @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang_induk', 'admin_gudang_cabang']))
                <div class="ms-3 me-3 relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-2 rounded-full text-blue-200 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="absolute top-0 right-0 block h-2 w-2 transform -translate-y-1/2 translate-x-1/2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        @endif
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden z-20" style="display: none;">
                        <div class="py-2">
                            @forelse($notifications as $notification)
                                <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="flex items-center px-4 py-3 border-b hover:bg-gray-100 dark:hover:bg-gray-700 -mx-2">
                                    <p class="text-gray-600 dark:text-gray-200 text-sm mx-2">
                                        {{ $notification->data['message'] }}
                                    </p>
                                </a>
                            @empty
                                <div class="px-4 py-3 text-center text-gray-500">Tidak ada notifikasi baru.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif

                <!-- Settings Dropdown (Profil Pengguna) -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-200 bg-blue-900 hover:text-white focus:outline-none transition ease-in-out duration-150"> {{-- Warna teks trigger menjadi terang, background navy --}}
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-200 hover:text-white hover:bg-blue-800 focus:outline-none focus:bg-blue-800 focus:text-white transition duration-150 ease-in-out"> {{-- Warna hamburger menjadi terang --}}
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-blue-800 dark:bg-gray-900"> {{-- Background menu responsif menjadi navy yang sedikit lebih terang --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- [UBAH] Logika menu responsif disesuaikan dan diberi styling --}}
            {{-- Menu untuk Super Admin --}}
            @if(auth()->user()->role === 'super_admin')
                <x-responsive-nav-link :href="route('spareparts.index')" :active="request()->routeIs('spareparts.*')">Master Sparepart</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">Master Supplier</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('stok-masuk.index')" :active="request()->routeIs('stok-masuk.*')">Pembelian</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('laporan.induk.index')" :active="request()->routeIs('laporan.induk.*')">Laporan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Manajemen User</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cabangs.index')" :active="request()->routeIs('cabangs.*')">Manajemen Cabang</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.index')">Log Aktivitas</x-responsive-nav-link>
            @endif
            
            {{-- Menu untuk Pengelola Gudang Induk (Super Admin & Admin Gudang Induk) --}}
            @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang_induk']))
                <x-responsive-nav-link :href="route('distribusi.index')" :active="request()->routeIs(['distribusi.index', 'distribusi.create', 'distribusi.show'])">Distribusi</x-responsive-nav-link>
            @endif
            
            {{-- Menu Eksklusif Admin Gudang Induk --}}
            @if(auth()->user()->role === 'admin_gudang_induk')
                <x-responsive-nav-link :href="route('distribusi.stok.induk')" :active="request()->routeIs('distribusi.stok.induk')">Stok Gudang Induk</x-responsive-nav-link>
            @endif

            {{-- Menu untuk Admin Gudang Cabang --}}
            @if(auth()->user()->role === 'admin_gudang_cabang')
                <x-responsive-nav-link :href="route('cabang.stok.index')" :active="request()->routeIs('cabang.stok.index')">Stok Saya</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cabang.penerimaan.index')" :active="request()->routeIs('cabang.penerimaan.index')">Kiriman Masuk</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjualan.index')" :active="request()->routeIs('penjualan.*')">Penjualan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('laporan.cabang.index')" :active="request()->routeIs('laporan.cabang.*')">Laporan</x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-700 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-white dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-blue-300 dark:text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
