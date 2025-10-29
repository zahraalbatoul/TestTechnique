<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('organizations.join') }}" class="btn-secondary">
                    {{ __('Join Organization') }}
                </a>
                <a href="{{ route('organizations.create') }}" class="btn-primary">
                    {{ __('Create Organization') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        You are a member of {{ Auth::user()->organizations->count() }} organization(s).
                    </p>
                </div>
            </div>

            <!-- Organizations Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Your Organizations</h3>
                        <a href="{{ route('organizations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Organization
                        </a>
                    </div>

                    @if(Auth::user()->organizations->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach(Auth::user()->organizations as $organization)
                                <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                        {{ $organization->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Slug: <code class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">{{ $organization->slug }}</code>
                                    </p>
                                <div class="flex gap-2">
                                    <a href="{{ route('organizations.show', $organization) }}" class="btn-outline">
                                        View Details
                                    </a>
                                    <a href="{{ route('tenant.dashboard', $organization->slug) }}" class="btn-secondary">
                                        Open Workspace
                                    </a>
                                </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400 mb-4">You don't belong to any organizations yet.</p>
                            <a href="{{ route('organizations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                                Create Your First Organization
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
