<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if(config('app.env') === 'production' && !file_exists(public_path('build/manifest.json')))
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    darkMode: 'class',
                    theme: { extend: { colors: { purple: tailwind.colors.purple, green: tailwind.colors.green, orange: tailwind.colors.orange } } }
                }
            </script>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <style>
            .btn{@apply inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-sm transition;}
            .btn-primary{@apply btn text-white bg-purple-600 hover:bg-purple-700;}
            .btn-secondary{@apply btn text-white bg-green-600 hover:bg-green-700;}
            .btn-accent{@apply btn text-white bg-orange-600 hover:bg-orange-700;}
            .btn-outline{@apply btn border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700;}
            .card{@apply bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg;}
            .card-body{@apply p-6 text-gray-900 dark:text-gray-100;}
            .badge{@apply inline-flex items-center px-2 py-0.5 text-xs font-medium rounded;}
            .status-todo{@apply badge bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100;}
            .status-in_progress{@apply badge bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-100;}
            .status-review{@apply badge bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-100;}
            .status-done{@apply badge bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100;}
            .link-action{@apply text-purple-600 dark:text-purple-400 hover:underline;}
            .form-input, .form-select, .form-textarea{@apply rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm;}
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer class="mt-12 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                    <p>© {{ date('Y') }} Multi‑Tenant PM. All rights reserved.</p>
                    <div class="flex items-center gap-3 mt-3 sm:mt-0">
                        <a href="{{ route('organizations.index') }}" class="link-action">Organizations</a>
                        <a href="{{ route('organizations.create') }}" class="link-action">Create</a>
                        <a href="/api/documentation" class="link-action">API Docs</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
