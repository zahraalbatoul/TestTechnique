<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 md:gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Organizations') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Switch between your organizations or create a new one.</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('organizations.join') }}" class="btn-secondary">
                    Join Organization
                </a>
                <a href="{{ route('organizations.create') }}" class="btn-primary">
                    Create Organization
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($organizations->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($organizations as $organization)
                        <div class="card">
                            <div class="card-body">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $organization->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Slug: <code class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">{{ $organization->slug }}</code>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                                    Created: {{ $organization->created_at->format('M d, Y') }}
                                </p>
                                <div class="flex gap-3 flex-wrap">
                                    <a href="{{ route('tenant.dashboard', $organization->slug) }}" class="btn-secondary">
                                        {{ __('Open Workspace') }} →
                                    </a>
                                    <a href="{{ route('organizations.show', $organization) }}" class="btn-outline">
                                        {{ __('Details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">No organizations found.</p>
                        <a href="{{ route('organizations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                            Create Your First Organization
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

