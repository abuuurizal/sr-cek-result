<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $userId ? __('Edit User') : __('Create User') }}
        </h1>
        <a href="{{ route('admin.users.index') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-medium rounded-xl transition-colors shadow-sm">
            <x-lucide-arrow-left class="w-4 h-4" />
            {{ __('Back') }}
        </a>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __('User Information') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Name') }}
                    </label>
                    <input type="text" id="name" wire:model="name"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('Full name') }}" />
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Email') }}
                    </label>
                    <input type="email" id="email" wire:model="email"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('email@example.com') }}" />
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Password') }}
                        @if ($userId)
                            <span class="text-xs text-gray-400 font-normal">{{ __('(leave empty to keep current)') }}</span>
                        @endif
                    </label>
                    <input type="password" id="password" wire:model="password"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('Minimum 8 characters') }}" />
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Confirmation --}}
                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Confirm Password') }}
                    </label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all placeholder-gray-400"
                        placeholder="{{ __('Repeat password') }}" />
                </div>

                {{-- Role --}}
                <div>
                    <label for="selectedRole" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Role') }}
                    </label>
                    <select id="selectedRole" wire:model="selectedRole"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all">
                        <option value="">{{ __('-- Select Role --') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRole')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <x-lucide-save class="w-4 h-4" />
                <span wire:loading.remove
                    wire:target="save">{{ $userId ? __('Update User') : __('Create User') }}</span>
                <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
            </button>
            <a href="{{ route('admin.users.index') }}" wire:navigate
                class="px-6 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white text-sm font-medium transition-colors">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>