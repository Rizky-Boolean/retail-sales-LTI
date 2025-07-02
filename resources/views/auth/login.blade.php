<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'YamahaPartsLog') }} - Login</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite Build --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-blue-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden flex flex-col lg:flex-row border border-blue-700 dark:border-gray-700">

        {{-- Kiri: Logo --}}
        <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center items-center bg-white dark:bg-gray-800">
            <a href="/">
                <img src="{{ asset('images/logo lautan teduh.png') }}" alt="Logo" class="w-28 h-28 object-contain p-2 bg-blue-50 rounded-full shadow-inner">
            </a>
            <h1 class="text-3xl font-bold text-blue-900 dark:text-gray-200 mt-4">YamahaPartsLog</h1>
            <p class="text-blue-800 text-base mt-2 text-center">Sistem Internal Pencatatan Distribusi & Penjualan Sparepart Yamaha</p>
        </div>

        {{-- Kanan: Form --}}
        <div class="lg:w-1/2 p-8 lg:p-12 bg-white dark:bg-gray-800">

            <div x-data="{ show: false }">
                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-5">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                            class="block w-full p-3 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div class="mb-5">
                        <x-input-label for="password" :value="__('Password')" />

                        <div class="relative mt-1">
                            <input :type="show ? 'text' : 'password'" id="password" name="password" required
                                class="block w-full p-3 pe-10 text-base rounded-lg border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />

                            <button type="button" @click="show = !show" tabindex="-1"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-300">
                                {{-- Eye Open --}}
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                </svg>

                                {{-- Eye Off --}}
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.965 9.965 0 013.28-4.663" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center mt-4">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-700">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    {{-- Login Button --}}
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full px-8 py-3 text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Log In') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
