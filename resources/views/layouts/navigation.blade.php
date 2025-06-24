<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Menu hanya untuk Admin Induk --}}
                    @if(auth()->user()->role === 'admin_induk')
                        <x-nav-link :href="route('spareparts.index')" :active="request()->routeIs('spareparts.*')">
                            {{ __('Master Sparepart') }}
                        </x-nav-link>
                        <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                            {{ __('Master Supplier') }}
                        </x-nav-link>
                        <x-nav-link :href="route('stok-masuk.index')" :active="request()->routeIs('stok-masuk.*')">
                            {{ __('Stok Masuk') }}
                        </x-nav-link>
                        <x-nav-link :href="route('distribusi.index')" :active="request()->routeIs('distribusi.*')">
                            {{ __('Distribusi') }}
                        </x-nav-link>
                        <x-nav-link :href="route('laporan.induk.index')" :active="request()->routeIs('laporan.induk.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Manajemen User') }}
                        </x-nav-link>
                        <x-nav-link :href="route('cabangs.index')" :active="request()->routeIs('cabangs.*')">
                            {{ __('Manajemen Cabang') }}
                        </x-nav-link>
                        <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.index')">
                            {{ __('Log Aktivitas') }}
                        </x-nav-link>
                    @endif

                    {{-- Menu hanya untuk Admin Cabang --}}
                    @if(auth()->user()->role === 'admin_cabang')
                        <x-nav-link :href="route('cabang.stok.index')" :active="request()->routeIs('cabang.stok.index')">
                            {{ __('Stok Saya') }}
                        </x-nav-link>
                        <x-nav-link :href="route('cabang.penerimaan.index')" :active="request()->routeIs('cabang.penerimaan.index')">
                            {{ __('Penerimaan Barang') }}
                        </x-nav-link>
                        <x-nav-link :href="route('penjualan.index')" :active="request()->routeIs('penjualan.*')">
                            {{ __('Penjualan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('laporan.cabang.index')" :active="request()->routeIs('laporan.cabang.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="ms-3 relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-2 rounded-full text-gray-400 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 block h-2 w-2 transform -translate-y-1/2 translate-x-1/2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        @endif
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden z-20" style="display: none;">
                        <div class="py-2">
                            @forelse($notifications as $notification)
                                <a href="{{-- Rute untuk menandai dibaca --}}" class="flex items-center px-4 py-3 border-b hover:bg-gray-100 dark:hover:bg-gray-700 -mx-2">
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
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                             <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
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
            </div>
            
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- Menu Responsif hanya untuk Admin Induk --}}
            @if(auth()->user()->role === 'admin_induk')
                <x-responsive-nav-link :href="route('spareparts.index')" :active="request()->routeIs('spareparts.*')">
                    {{ __('Master Sparepart') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                    {{ __('Master Supplier') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('stok-masuk.index')" :active="request()->routeIs('stok-masuk.*')">
                    {{ __('Stok Masuk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('distribusi.index')" :active="request()->routeIs('distribusi.*')">
                    {{ __('Distribusi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('laporan.induk.index')" :active="request()->routeIs('laporan.induk.*')">
                    {{ __('Laporan Induk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Manajemen User') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cabangs.index')" :active="request()->routeIs('cabangs.*')">
                    {{ __('Manajemen Cabang') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.index')">
                    {{ __('Log Aktivitas') }}
                </x-responsive-nav-link>
            @endif

            {{-- Menu Responsif hanya untuk Admin Cabang --}}
            @if(auth()->user()->role === 'admin_cabang')
                <x-responsive-nav-link :href="route('cabang.stok.index')" :active="request()->routeIs('cabang.stok.index')">
                    {{ __('Stok Saya') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cabang.penerimaan.index')" :active="request()->routeIs('cabang.penerimaan.index')">
                    {{ __('Penerimaan Barang') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('penjualan.index')" :active="request()->routeIs('penjualan.*')">
                    {{ __('Penjualan') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('laporan.cabang.index')" :active="request()->routeIs('laporan.cabang.*')">
                    {{ __('Laporan Cabang') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
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
