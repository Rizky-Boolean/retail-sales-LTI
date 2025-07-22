@props(['active'])

@php
// [UBAH] Logika kelas CSS diperbarui untuk tema biru navy
$classes = ($active ?? false)
            // Kelas untuk link yang sedang aktif
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-white text-white font-semibold text-sm leading-5 focus:outline-none transition duration-150 ease-in-out'
            // Kelas untuk link yang tidak aktif
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-blue-200 hover:text-white hover:border-blue-300 font-medium text-sm leading-5 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>