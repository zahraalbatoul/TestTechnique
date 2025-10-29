<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $project->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('tenant.projects.edit', [$organization->slug, $project]) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('tenant.tasks.create', [$organization->slug, $project]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Add Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="card">
                    <div class="card-body text-green-700 dark:text-green-300">{{ session('success') }}</div>
                </div>
            @endif
            <!-- Project Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $project->name }}</h3>
                            <span class="status-{{ $project->status }} text-sm">
                                {{ str_replace('_', ' ', ucfirst($project->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($project->description)
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $project->description }}</p>
                    @endif

                    <div class="flex gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <span>Created: {{ $project->created_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>{{ $project->tasks->count() }} tasks</span>
                    </div>
                </div>
            </div>

            <!-- Tasks Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tasks</h3>
                        <a href="{{ route('tenant.tasks.create', [$organization->slug, $project]) }}" class="text-sm bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add Task
                        </a>
                    </div>

                    @if($project->tasks->count() > 0)
                        <div class="space-y-3">
                            @foreach($project->tasks as $task)
                                <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $task->title }}</h4>
                                                <span class="status-{{ $task->status }}">
                                                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                                </span>
                                                <span class="px-2 py-1 text-xs rounded {{ 
                                                    $task->priority === 'urgent' ? 'bg-red-200 text-red-800' :
                                                    ($task->priority === 'high' ? 'bg-orange-200 text-orange-800' :
                                                    ($task->priority === 'low' ? 'bg-gray-200 text-gray-800' :
                                                    'bg-yellow-200 text-yellow-800'))
                                                }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </div>
                                            @if($task->description)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $task->description }}</p>
                                            @endif
                                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                                @if($task->assigned_to && $task->assignedUser())
                                                    <span>Assigned to: {{ $task->assignedUser()->name }}</span>
                                                @else
                                                    <span class="text-gray-400">Unassigned</span>
                                                @endif
                                                @if($task->due_date)
                                                    <span>Due: {{ $task->due_date->format('M d, Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            <a href="{{ route('tenant.tasks.show', [$organization->slug, $task]) }}" class="link-action text-sm">
                                                View →
                                            </a>
                                            @if($task->status !== 'done')
                                                <form method="POST" action="{{ route('tenant.tasks.complete', [$organization->slug, $task]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-secondary px-3 py-1 text-xs">Mark Done</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400 mb-4">No tasks yet. Create your first task!</p>
                            <a href="{{ route('tasks.create', [$organization->slug, $project]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                                Add Task
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <form method="POST" action="{{ route('tenant.projects.destroy', [$organization->slug, $project]) }}" onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Project
                    </button>
                </form>
                <a href="{{ route('tenant.projects.index', $organization->slug) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Projects
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

