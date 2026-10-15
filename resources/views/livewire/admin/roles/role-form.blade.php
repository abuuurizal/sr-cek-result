<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $roleId ? __('Edit Role') : __('Create Role') }}
        </h1>
        <a href="{{ route('admin.roles.index') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium rounded-xl transition-colors shadow-sm">
            <x-lucide-arrow-left class="w-4 h-4" />
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="space-y-6">
        {{-- Role Name --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('Role Information') }}</h2>

            <div class="max-w-md">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    {{ __('Role Name') }}
                </label>
                <input type="text" id="name" wire:model="name"
                    class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                    placeholder="{{ __('e.g. editor, moderator') }}" />
                @error('name')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Permissions --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('Permissions') }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($permissions as $permission)
                    <label
                        class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 cursor-pointer transition-colors has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300 dark:has-[:checked]:bg-blue-900/20 dark:has-[:checked]:border-blue-700">
                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                            class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:ring-2" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>

            @if ($permissions->isEmpty())
                <p class="text-gray-400 dark:text-gray-500 text-sm italic">{{ __('No permissions available.') }}</p>
            @endif
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <x-lucide-save class="w-4 h-4" />
                <span wire:loading.remove
                    wire:target="save">{{ $roleId ? __('Update Role') : __('Create Role') }}</span>
                <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
            </button>
            <a href="{{ route('admin.roles.index') }}" wire:navigate
                class="px-6 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white text-sm font-medium transition-colors">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>