<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Join Organization') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-6 text-gray-600 dark:text-gray-400">
                        Enter the organization slug to join an existing organization.
                    </p>

                    <form method="POST" action="{{ route('organizations.join.store') }}">
                        @csrf

                        <!-- Slug -->
                        <div class="mb-4">
                            <x-input-label for="slug" :value="__('Organization Slug')" />
                            <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug" :value="old('slug')" required autofocus placeholder="e.g., acme-corp" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                The unique identifier for the organization (usually lowercase with hyphens)
                            </p>
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('organizations.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Join Organization') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Available Organizations (for demo) -->
                    @if(auth()->user()->organizations->count() < \App\Models\Organization::count())
                        <div class="mt-8 pt-6 border-t border-gray-300 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Available Organizations:</p>
                            <div class="space-y-2">
                                @foreach(\App\Models\Organization::whereNotIn('id', auth()->user()->organizations->pluck('id'))->get() as $org)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $org->name }}</span>
                                        <code class="text-xs bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">{{ $org->slug }}</code>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

