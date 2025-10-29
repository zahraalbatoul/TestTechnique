<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 md:gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $organization->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage members, projects and tasks within this organization.</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('tenant.dashboard', $organization->slug) }}" class="btn-secondary">
                    Open Workspace →
                </a>
                <a href="{{ route('tenant.projects.create', $organization->slug) }}" class="btn-primary">
                    New Project
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Organization Info -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Organization Details</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $organization->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <code class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">{{ $organization->slug }}</code>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $organization->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $organization->created_at->format('F d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Members Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Members</h3>
                    @if($organization->users->count() > 0)
                        <div class="space-y-2">
                            @foreach($organization->users as $user)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No members found.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('tenant.dashboard', $organization->slug) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Open Workspace
                        </a>
                        <a href="{{ route('organizations.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Organizations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

