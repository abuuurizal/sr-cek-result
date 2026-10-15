<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Roles & Permissions') }}</h1>
        <a href="{{ route('admin.roles.create') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <x-lucide-plus class="w-4 h-4" />
            {{ __('Add Role') }}
        </a>
    </div>

    {{-- Flash Messages --}}
    <x-flash-messages />

    {{-- Roles Table --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th
                        class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                        #</th>
                    <th
                        class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                        {{ __('Role') }}
                    </th>
                    <th
                        class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                        {{ __('Permissions') }}
                    </th>
                    <th
                        class="text-right px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($roles as $index => $role)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $role->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse ($role->permissions as $permission)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span
                                        class="text-gray-400 dark:text-gray-500 text-xs italic">{{ __('All permissions (super-admin)') }}</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.roles.edit', $role->id) }}" wire:navigate
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                    <x-lucide-pencil class="w-4 h-4" />
                                    {{ __('Edit') }}
                                </a>
                                @if ($role->name !== 'super-admin')
                                    <button wire:click="deleteRole({{ $role->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this role?') }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                        {{ __('Delete') }}
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <x-lucide-shield-off class="w-12 h-12 mx-auto mb-3 opacity-30" />
                            <p>{{ __('No roles found.') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>