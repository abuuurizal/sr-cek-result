<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <button @click="open = !open"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <div
            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-sm">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        <div class="hidden md:block text-left">
            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                x-on:profile-updated.window="name = $event.detail.name"></p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
        </div>
        <x-lucide-chevron-down 
            class="w-4 h-4 text-gray-400 dark:text-gray-400 transition-transform hidden md:block" 
            x-bind:class="{ 'rotate-180': open }" />
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute right-0 top-full mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">

        <!-- User Info -->
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white"
                    x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
            </div>
            <!-- Dark Mode Toggle (Mobile) -->
            <button @click="toggleDarkMode(); open = false"
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <x-lucide-moon x-show="!darkMode" class="w-5 h-5" />
                <x-lucide-sun x-show="darkMode" class="w-5 h-5" x-cloak />
            </button>
        </div>

        <!-- My Profile -->
        <a href="{{ route('admin.profile') }}" wire:navigate @click="open = false"
            class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
            <x-lucide-user class="w-4 h-4" />
            <span class="text-sm font-medium">{{ __('My Profile') }}</span>
        </a>

        <!-- Logout -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-1 mt-1">
            <button wire:click="logout" @click="open = false"
                class="w-full flex items-center gap-3 px-4 py-2.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                <x-lucide-log-out class="w-4 h-4" />
                <span class="text-sm font-medium">{{ __('Logout') }}</span>
            </button>
        </div>
    </div>
</div>