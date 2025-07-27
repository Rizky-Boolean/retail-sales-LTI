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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  {{-- Vite Build --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans bg-gradient-to-br from-blue-900 to-indigo-900 min-h-screen flex items-center justify-center relative">

  <div class="bg-white rounded-xl p-8 shadow-lg flex overflow-hidden w-[850px] max-w-full mx-auto">
    
    <!-- Left Panel -->
    <div class="w-1/2 bg-white p-8 text-blue-800 flex flex-col justify-center items-center">
      <img src="{{ asset('images/logo lautan teduh.png') }}" alt="Logo" class="w-28 h-28 object-contain p-2 bg-white rounded-full shadow-lg">
      <h1 class="text-3xl font-bold text-gray-800 mt-2">YamahaPartsLog</h1>
      <p class="text-sm text-gray-800 mt-2 text-center px-4">
        Sistem Internal Pencatatan Distribusi & Penjualan Sparepart Yamaha
      </p>
    </div>

    <div class="w-px bg-gray-200"></div>

    <!-- Right Panel -->
    <div class="w-1/2 p-10 flex flex-col justify-center">
      <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Log In</h2>
      
      <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ show: false }">
        @csrf
        <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input 
            type="email"
            id="email"
            name="email"
            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none"
            placeholder="Masukkan email anda"
            required
        >
        </div>

        <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <div class="relative">
            <input 
            :type="show ? 'text' : 'password'"
            id="password"
            name="password"
            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none pr-10"
            placeholder="Masukkan password anda"
            required
            >
            <button type="button" @click="show = !show" tabindex="-1"
                class="absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                    <line x-show="!show" x-cloak x-transition
                        x1="4" y1="4" x2="20" y2="20"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>
        </div>

        <button 
        type="submit" 
        class="w-full py-2 rounded-lg bg-blue-900 hover:bg-blue-800 text-white font-semibold transition duration-300">
        Log In
        </button>
    </form>
    </div>
  </div>

  {{-- Footer --}}
  <footer class="text-center text-xs text-gray-300 mt-6 absolute bottom-4 w-full">
    © 2025 PT Lautan Teduh Interniaga. All rights reserved.
  </footer>
</body>
</html>
