<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $organization->name }} - Projects
            </h2>
            <a href="{{ route('tenant.projects.create', $organization->slug) }}" class="btn-primary">
                Create Project
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="card mb-4">
                    <div class="card-body text-green-700 dark:text-green-300">{{ session('success') }}</div>
                </div>
            @endif
            @if($projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $project->name }}
                                    </h3>
                                    <span class="status-{{ $project->status }}">
                                        {{ str_replace('_', ' ', ucfirst($project->status)) }}
                                    </span>
                                </div>
                                
                                @if($project->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                        {{ $project->description }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    <span>{{ $project->tasks->count() }} tasks</span>
                                    <a href="{{ route('tenant.tasks.create', [$organization->slug, $project]) }}" class="btn-accent px-3 py-1 text-xs">+ Task</a>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('projects.show', [$organization->slug, $project]) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex-1 text-center py-2 border border-blue-600 dark:border-blue-400 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                                        View
                                    </a>
                                    <a href="{{ route('projects.edit', [$organization->slug, $project]) }}" class="text-gray-600 dark:text-gray-400 hover:underline text-sm flex-1 text-center py-2 border border-gray-600 dark:border-gray-400 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No projects</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new project.</p>
                        <div class="mt-6">
                            <a href="{{ route('tenant.projects.create', $organization->slug) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Create Project
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

