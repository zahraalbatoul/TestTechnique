<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $organization->name ?? 'Workspace' }} - Workspace
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Managing projects and tasks for this organization
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tenant.projects.index', $organization->slug) }}" class="hidden sm:inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm rounded text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                    View Projects
                </a>
                <a href="{{ route('tenant.projects.create', $organization->slug) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded">
                    New Project
                </a>
                <a href="{{ route('organizations.index') }}" class="inline-flex items-center px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                    ← Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            @php
                try {
                    $totalProjects = \App\Models\Project::count();
                    $totalTasks = \App\Models\Task::count();
                    $inProgressTasks = \App\Models\Task::where('status', 'in_progress')->count();
                    $completedTasks = \App\Models\Task::where('status', 'done')->count();
                    $inProgressProjects = \App\Models\Project::where('status', 'in_progress')->count();
                    $completedProjects = \App\Models\Project::where('status', 'completed')->count();
                } catch (\Exception $e) {
                    // If database doesn't exist yet, set defaults
                    $totalProjects = 0;
                    $totalTasks = 0;
                    $inProgressTasks = 0;
                    $completedTasks = 0;
                    $inProgressProjects = 0;
                    $completedProjects = 0;
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Projects</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalProjects }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $inProgressProjects }} in progress</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tasks</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalTasks }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $completedTasks }} completed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">In Progress</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $inProgressTasks }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">tasks active</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $completedProjects }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">projects done</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Projects</h3>
                        <a href="{{ route('tenant.projects.create', $organization->slug) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Create Project
                        </a>
                    </div>

                    @php
                        $projects = \App\Models\Project::withCount('tasks')->latest()->take(5)->get();
                    @endphp

                    @if($projects->count() > 0)
                        <div class="space-y-4">
                            @foreach($projects as $project)
                                <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                {{ $project->name }}
                                            </h4>
                                            @if($project->description)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $project->description }}</p>
                                            @endif
                                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="status-{{ $project->status }}">{{ str_replace('_', ' ', ucfirst($project->status)) }}</span>
                                                <span>{{ $project->tasks_count }} tasks</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('projects.show', [$organization->slug, $project]) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                            View →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <a href="{{ route('tenant.projects.index', $organization->slug) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                View all projects →
                            </a>
                            <a href="{{ route('tenant.projects.create', $organization->slug) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Create Project
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400 mb-4">No projects yet. Create your first project!</p>
                            <a href="{{ route('tenant.projects.create', $organization->slug) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                                Create Project
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- API Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">API Access</h3>
                        <p class="text-sm text-blue-800 dark:text-blue-200 mb-2">
                            Use slug: <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">{{ $organization->slug }}</code>
                        </p>
                        <p class="text-xs text-blue-700 dark:text-blue-300">
                            Base URL: <code>/api/t/{{ $organization->slug }}/</code>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('projects.index', $organization->slug) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                            View Projects
                        </a>
                        <a href="{{ route('tenant.projects.create', $organization->slug) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                            New Project
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

