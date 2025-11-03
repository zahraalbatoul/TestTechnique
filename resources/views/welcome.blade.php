<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Multi-Tenant PM') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

            @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="py-4 px-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Multi-Tenant PM</h1>
                @if (Route::has('login'))
                    <nav class="flex gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600">Dashboard</a>
        @else
                            <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Register</a>
                            @endif
                        @endauth
                    </nav>
        @endif
            </div>
        </header>

        <!-- Hero -->
        <main class="flex-1 flex items-center justify-center px-6 py-12">
            <div class="max-w-4xl w-full text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Multi‑Tenant Project Management
                </h1>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 mb-8">
                    Organize projects, assign tasks, and switch between organizations seamlessly.
                </p>
                <div class="flex items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('organizations.index') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold">
                            Your Organizations
                        </a>
                        <a href="{{ route('organizations.create') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-md hover:bg-purple-700 font-semibold">
                            Create Organization
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-md hover:bg-purple-700 font-semibold">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-800 font-semibold">
                            Sign In
                        </a>
                    @endauth
                </div>

                <!-- Features -->
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Isolated Workspaces</h3>
                        <p class="text-gray-600 dark:text-gray-400">Data is isolated per organization using robust multi‑tenancy.</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Projects & Tasks</h3>
                        <p class="text-gray-600 dark:text-gray-400">Track status, priorities, and assignments with ease.</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">API & Docs</h3>
                        <p class="text-gray-600 dark:text-gray-400">Explore the REST API with built‑in Swagger UI.</p>
                    </div>
                </div>
                </div>
            </main>

        <!-- Footer -->
        <footer class="py-6 px-6 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                <p>© {{ date('Y') }} Multi‑Tenant PM. All rights reserved.</p>
                <div class="flex items-center gap-4 mt-3 sm:mt-0">
                    <a href="{{ route('organizations.index') }}" class="text-purple-600 dark:text-purple-400 hover:underline">Organizations</a>
                    <a href="/api/documentation" class="text-purple-600 dark:text-purple-400 hover:underline">API Docs</a>
                </div>
            </div>
        </footer>
        </div>
    </body>
</html>
