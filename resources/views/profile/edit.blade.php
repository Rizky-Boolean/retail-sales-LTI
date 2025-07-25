<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-5" x-data="{ tab: 'profile' }">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-8 space-y-6">

            {{-- Navigasi Tab --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <button @click="tab = 'profile'"
                            :class="{
                                'border-blue-500 dark:border-blue-400 text-blue-600 dark:text-blue-300': tab === 'profile',
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600': tab !== 'profile'
                            }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none">
                        Informasi Profil
                    </button>
                    <button @click="tab = 'password'"
                            :class="{
                                'border-blue-500 dark:border-blue-400 text-blue-600 dark:text-blue-300': tab === 'password',
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600': tab !== 'password'
                            }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none">
                        Ubah Password
                    </button>
                    <button @click="tab = 'delete'"
                            :class="{
                                'border-red-500 dark:border-red-400 text-red-600 dark:text-red-400': tab === 'delete',
                                'border-transparent text-gray-500 dark:text-gray-400 hover:text-red-700 dark:hover:text-red-500 hover:border-red-300 dark:hover:border-red-600': tab !== 'delete'
                            }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none">
                        Hapus Akun
                    </button>
                </nav>
            </div>

            {{-- Konten Tab --}}
            <div>
                {{-- Tab Informasi Profil --}}
                <div x-show="tab === 'profile'" x-cloak>
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                {{-- Tab Ubah Password --}}
                <div x-show="tab === 'password'" x-cloak>
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                {{-- Tab Hapus Akun --}}
                <div x-show="tab === 'delete'" x-cloak>
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>