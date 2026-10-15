<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('Users') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Manage user accounts and roles') }}</p>
        </div>
        <a href="{{ route('admin.users.create') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <x-lucide-plus class="w-4 h-4" />
            {{ __('Add User') }}
        </a>
    </div>

    {{-- Flash Messages --}}
    <x-flash-messages />

    {{-- Toolbar: Search + Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
        <div class="flex flex-col lg:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <x-lucide-search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('Search by name or email...') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400" />
            </div>

            {{-- Role Filter --}}
            <select wire:model.live="filterRole"
                class="px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all min-w-[160px]">
                <option value="">{{ __('All Roles') }}</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>

            {{-- Verified Filter --}}
            <select wire:model.live="filterVerified"
                class="px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all min-w-[160px]">
                <option value="">{{ __('All Status') }}</option>
                <option value="verified">{{ __('Verified') }}</option>
                <option value="unverified">{{ __('Unverified') }}</option>
            </select>

            {{-- Per Page --}}
            <select wire:model.live="perPage"
                class="px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all min-w-[100px]">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    {{-- Bulk Actions Bar --}}
    @if ($selectedCount > 0)
        <div
            class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">
                    {{ $selectedCount }} {{ __('user(s) selected') }}
                </span>

                @if (!$selectAll && $selectedCount < $users->total())
                    <button wire:click="selectAllUsers"
                        class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        {{ __('Select all') }} {{ $users->total() }}
                    </button>
                @endif

                <button wire:click="deselectAll" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    {{ __('Deselect all') }}
                </button>
            </div>

            <button wire:click="bulkDelete"
                wire:confirm="{{ __('Are you sure you want to delete the selected users? Super-admin and your own account will be skipped.') }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                <x-lucide-trash-2 class="w-4 h-4" />
                {{ __('Delete Selected') }}
            </button>
        </div>
    @endif

    {{-- Table --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        {{-- Checkbox --}}
                        <th class="w-12 px-4 py-3">
                            <input type="checkbox" wire:model.live="selectPage"
                                class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:ring-2" />
                        </th>
                        {{-- Name --}}
                        <th class="text-left px-6 py-3">
                            <button wire:click="sortBy('name')"
                                class="flex items-center gap-1.5 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ __('Name') }}
                                @if ($sortField === 'name')
                                    <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')"
                                        class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-40" />
                                @endif
                            </button>
                        </th>
                        {{-- Email --}}
                        <th class="text-left px-6 py-3">
                            <button wire:click="sortBy('email')"
                                class="flex items-center gap-1.5 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ __('Email') }}
                                @if ($sortField === 'email')
                                    <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')"
                                        class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-40" />
                                @endif
                            </button>
                        </th>
                        {{-- Role --}}
                        <th
                            class="text-left px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Role') }}
                        </th>
                        {{-- Verified --}}
                        <th class="text-left px-6 py-3">
                            <button wire:click="sortBy('email_verified_at')"
                                class="flex items-center gap-1.5 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ __('Verified') }}
                                @if ($sortField === 'email_verified_at')
                                    <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')"
                                        class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-40" />
                                @endif
                            </button>
                        </th>
                        {{-- Created --}}
                        <th class="text-left px-6 py-3">
                            <button wire:click="sortBy('created_at')"
                                class="flex items-center gap-1.5 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs hover:text-gray-900 dark:hover:text-white transition-colors">
                                {{ __('Created') }}
                                @if ($sortField === 'created_at')
                                    <x-dynamic-component :component="'lucide-' . ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down')"
                                        class="w-3.5 h-3.5" />
                                @else
                                    <x-lucide-chevrons-up-down class="w-3.5 h-3.5 opacity-40" />
                                @endif
                            </button>
                        </th>
                        {{-- Actions --}}
                        <th
                            class="text-right px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-xs">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ in_array((string) $user->id, $selected) ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}"
                            wire:key="user-{{ $user->id }}">
                            {{-- Checkbox --}}
                            <td class="px-4 py-4">
                                <input type="checkbox" wire:model.live="selected" value="{{ $user->id }}"
                                    class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:ring-2" />
                            </td>
                            {{-- Name --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-semibold text-sm shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            {{-- Email --}}
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                            {{-- Role --}}
                            <td class="px-6 py-4">
                                @foreach ($user->roles as $role)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $role->name === 'super-admin' ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800' : 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800' }}">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            {{-- Verified --}}
                            <td class="px-6 py-4">
                                @if ($user->email_verified_at)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                        <x-lucide-check-circle class="w-3 h-3" />
                                        {{ __('Verified') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                        <x-lucide-alert-circle class="w-3 h-3" />
                                        {{ __('Unverified') }}
                                    </span>
                                @endif
                            </td>
                            {{-- Created --}}
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" wire:navigate
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                        <x-lucide-pencil class="w-4 h-4" />
                                        {{ __('Edit') }}
                                    </a>
                                    @if ($user->id !== auth()->id() && !$user->hasRole('super-admin'))
                                        <button wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="{{ __('Are you sure you want to delete this user?') }}"
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <x-lucide-users class="w-12 h-12 mx-auto mb-3 opacity-30" />
                                <p class="font-medium">{{ __('No users found.') }}</p>
                                @if ($search || $filterRole || $filterVerified)
                                    <p class="text-xs mt-1">{{ __('Try adjusting your search or filter criteria.') }}</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: Pagination + Info --}}
        @if ($users->hasPages())
            <div
                class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Showing') }} {{ $users->firstItem() }}–{{ $users->lastItem() }} {{ __('of') }}
                    {{ $users->total() }} {{ __('users') }}
                </p>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Total') }}: {{ $users->total() }} {{ __('users') }}
                </p>
            </div>
        @endif
    </div>
</div>