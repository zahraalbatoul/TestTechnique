@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-2 pt-1 border-b-2 border-purple-500 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-purple-600 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-2 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:border-purple-300 focus:outline-none focus:text-gray-900 dark:focus:text-white focus:border-purple-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
