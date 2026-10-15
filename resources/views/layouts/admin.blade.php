<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name', 'Cek Result Live') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=open-sans:300,400,500,600,700,800" rel="stylesheet" />

    <!-- Livewire Styles & Scripts -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('scripts')

    <!-- Alpine.js x-cloak styles, Custom Height & Scrollbar -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Exact Header Height Utility (72px / 4.5rem) */
        .h-18 {
            height: 4.5rem !important;
        }

        /* Custom Thin Scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }

        *::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        *::-webkit-scrollbar-track {
            background: transparent;
        }

        *::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
        }

        .dark * {
            scrollbar-color: rgba(107, 114, 128, 0.5) transparent;
        }

        .dark *::-webkit-scrollbar-thumb {
            background-color: rgba(107, 114, 128, 0.5);
        }

        /* Prevent iOS Safari auto-zoom on input focus (requires font-size >= 16px) */
        @media screen and (max-width: 768px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased transition-colors duration-200">
    <div x-data="{
            sidebarOpen: false,
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            darkMode: localStorage.getItem('darkMode') === 'true',
            searchQuery: '',
            init() {
                // Watch for sidebar collapsed changes and save to localStorage
                this.$watch('sidebarCollapsed', value => {
                    localStorage.setItem('sidebarCollapsed', value);
                });
                
                // Watch for dark mode changes and save to localStorage
                this.$watch('darkMode', value => {
                    localStorage.setItem('darkMode', value);
                    if (value) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                });
                
                // Apply dark mode on init
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                }
                
                // Keyboard shortcut: Ctrl+K to focus search & F11 for fullscreen
                window.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                        e.preventDefault();
                        if (this.$refs.searchInput) {
                            this.$refs.searchInput.focus();
                        }
                    }
                    if (e.key === 'F11') {
                        e.preventDefault();
                        this.toggleFullscreen();
                    }
                });

                // Sync fullscreen state when F11 or Escape is used
                document.addEventListener('fullscreenchange', () => {
                    this.isFullscreen = !!document.fullscreenElement;
                });
            },
            // Filter menu items based on search query
            filterMenu(text) {
                if (!this.searchQuery) return true;
                return text.toLowerCase().includes(this.searchQuery.toLowerCase());
            },
            // Toggle dark mode
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
            },
            // Toggle fullscreen
            isFullscreen: false,
            toggleFullscreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().catch(err => {
                        console.error(`Error attempting to enable fullscreen: ${err.message}`);
                    });
                    this.isFullscreen = true;
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                        this.isFullscreen = false;
                    }
                }
            }
        }" class="flex h-screen overflow-hidden">

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

        <!-- Sidebar (Visible by default on desktop) -->
        <aside
            :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', sidebarCollapsed ? 'lg:w-20' : 'lg:w-64']"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 transform transition-all duration-300 lg:translate-x-0 lg:static lg:inset-auto flex flex-col shrink-0"
            style="overflow: visible;">

            <!-- Logo -->
            <div class="flex items-center justify-center h-14 px-3" :class="sidebarCollapsed ? 'mt-1 mb-2' : 'mt-1'">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3">
                    <div class="h-10 w-auto flex items-center justify-center">
                        <img src="https://placehold.co/200x44/ffffff/1e293b?text=Logo"
                            alt="{{ config('app.name', 'Laravel') }}"
                            class="h-8 w-auto object-contain dark:hidden" />
                        <img src="https://placehold.co/200x44/1e293b/ffffff?text=Logo"
                            alt="{{ config('app.name', 'Laravel') }}"
                            class="h-8 w-auto object-contain hidden dark:block" />
                    </div>
                </a>
                <button @click="sidebarOpen = false"
                    class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 ml-auto">
                    <x-lucide-x class="w-6 h-6" />
                </button>
            </div>

            <!-- Menu -->
            <div class="flex-1 p-1"
                :class="sidebarCollapsed ? 'overflow-visible' : 'overflow-y-auto overflow-x-hidden'">
                <nav class="space-y-0 pb-10">

                    {{-- MAIN Section --}}
                    @canany(['menu.dashboard', 'menu.events', 'menu.test-template', 'superadmin_dashboard', 'superadmin_events'])
                        <div class="px-4 mb-2">
                            <!-- Full divider with text when expanded -->
                            <div x-show="!sidebarCollapsed" class="flex items-center gap-3">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    MAIN
                                </p>
                                <div class="flex-1 border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <!-- Only border when collapsed -->
                            <div x-show="sidebarCollapsed" class="border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                    @endcanany

                    {{-- Dashboard --}}
                    @canany(['menu.dashboard', 'superadmin_dashboard'])
                        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}" {{ Route::has('admin.dashboard') ? 'wire:navigate' : '' }} x-show="filterMenu('Dashboard')"
                            :class="sidebarCollapsed ? 'justify-center' : 'justify-start'"
                            class="group relative flex items-center gap-3 px-4 py-2 mb-1 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.dashboard') || request()->routeIs('superadmin.dashboard') ? '!text-blue-600 dark:!text-blue-400 !bg-blue-50 dark:!bg-gray-700 !font-bold' : '' }}">
                            <x-lucide-layout-dashboard class="w-5 h-5 shrink-0" />
                            <span x-show="!sidebarCollapsed" class="transition-opacity duration-300 group-hover:font-bold">Dashboard</span>
                            <!-- Tooltip -->
                            <span x-show="sidebarCollapsed"
                                class="hidden lg:block absolute left-full ml-2 bg-blue-600 text-white text-[11px] font-medium px-2.5 py-1 rounded-md shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 z-50">
                                Dashboard
                            </span>
                        </a>
                    @endcanany

                    {{-- Events --}}
                    @canany(['menu.events', 'superadmin_events'])
                        <a href="{{ Route::has('admin.events.index') ? route('admin.events.index') : '#' }}" {{ Route::has('admin.events.index') ? 'wire:navigate' : '' }} x-show="filterMenu('Events')"
                            :class="sidebarCollapsed ? 'justify-center' : 'justify-start'"
                            class="group relative flex items-center gap-3 px-4 py-2 mb-1 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.events.*') ? '!text-blue-600 dark:!text-blue-400 !bg-blue-50 dark:!bg-gray-700 !font-bold' : '' }}">
                            <x-lucide-calendar class="w-5 h-5 shrink-0" />
                            <span x-show="!sidebarCollapsed" class="transition-opacity duration-300 group-hover:font-bold">Events</span>
                            <!-- Tooltip -->
                            <span x-show="sidebarCollapsed"
                                class="hidden lg:block absolute left-full ml-2 bg-blue-600 text-white text-[11px] font-medium px-2.5 py-1 rounded-md shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 z-50">
                                Events
                            </span>
                        </a>
                    @endcanany

                    {{-- Test Products --}}
                    @canany(['menu.test-template'])
                        <a href="{{ Route::has('admin.test-products.index') ? route('admin.test-products.index') : '#' }}" {{ Route::has('admin.test-products.index') ? 'wire:navigate' : '' }} x-show="filterMenu('Test Products')"
                            :class="sidebarCollapsed ? 'justify-center' : 'justify-start'"
                            class="group relative flex items-center gap-3 px-4 py-2 mb-1 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.test-products.*') ? '!text-blue-600 dark:!text-blue-400 !bg-blue-50 dark:!bg-gray-700 !font-bold' : '' }}">
                            <x-lucide-package class="w-5 h-5 shrink-0" />
                            <span x-show="!sidebarCollapsed" class="transition-opacity duration-300 group-hover:font-bold">Test Products</span>
                            <!-- Tooltip -->
                            <span x-show="sidebarCollapsed"
                                class="hidden lg:block absolute left-full ml-2 bg-blue-600 text-white text-[11px] font-medium px-2.5 py-1 rounded-md shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 z-50">
                                Test Products
                            </span>
                        </a>
                    @endcanany

                    {{-- SETTING Section --}}
                    @canany(['menu.site-settings', 'menu.users', 'menu.roles', 'superadmin_site_settings', 'superadmin_users', 'superadmin_roles'])
                        <div class="px-4 mb-2 mt-8 pt-2">
                            <div x-show="!sidebarCollapsed" class="flex items-center gap-3">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    SETTING
                                </p>
                                <div class="flex-1 border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <div x-show="sidebarCollapsed" class="border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                    @endcanany

                    {{-- Site Settings --}}
                    @canany(['menu.site-settings', 'superadmin_site_settings'])
                        <a href="{{ Route::has('admin.site-settings.index') ? route('admin.site-settings.index') : '#' }}" {{ Route::has('admin.site-settings.index') ? 'wire:navigate' : '' }} x-show="filterMenu('Site Settings')"
                            :class="sidebarCollapsed ? 'justify-center' : 'justify-start'"
                            class="group relative flex items-center gap-3 px-4 py-2 mb-1 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.site-settings.*') ? '!text-blue-600 dark:!text-blue-400 !bg-blue-50 dark:!bg-gray-700 !font-bold' : '' }}">
                            <x-lucide-settings class="w-5 h-5 shrink-0" />
                            <span x-show="!sidebarCollapsed" class="transition-opacity duration-300 group-hover:font-bold">Site Settings</span>
                            <!-- Tooltip -->
                            <span x-show="sidebarCollapsed"
                                class="hidden lg:block absolute left-full ml-2 bg-blue-600 text-white text-[11px] font-medium px-2.5 py-1 rounded-md shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 z-50">
                                Site Settings
                            </span>
                        </a>
                    @endcanany

                    {{-- Users --}}
                    @canany(['menu.users', 'superadmin_users'])
                        <a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}" {{ Route::has('admin.users.index') ? 'wire:navigate' : '' }} x-show="filterMenu('Users')"
                            :class="sidebarCollapsed ? 'justify-center' : 'justify-start'"
                            class="group relative flex items-center gap-3 px-4 py-2 mb-1 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.users.*') || request()->routeIs('superadmin.users.*') ? '!text-blue-600 dark:!text-blue-400 !bg-blue-50 dark:!bg-gray-700 !font-bold' : '' }}">
                            <x-lucide-users class="w-5 h-5 shrink-0" />
                            <span x-show="!sidebarCollapsed" class="transition-opacity duration-300 group-hover:font-bold">Users</span>
                            <!-- Tooltip -->
                            <span x-show="sidebarCollapsed"
                                class="hidden lg:block absolute left-full ml-2 bg-blue-600 text-white text-[11px] font-medium px-2.5 py-1 rounded-md shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 z-50">
                                Users
                            </span>
                        </a>
                    @endcanany

                    {{-- Roles --}}
                    @canany(['menu.roles', 'superadmin_roles'])
                        <a href="{{ Route::has('admin.roles.index') ? route('admin.roles.index') : '#' }}" {{ Route::has('admin.roles.index') ? 'wire:navigate' : '' }} x-show="filterMenu('Roles')"
                            :class="sidebarCollapsed ? 'justify-center' : 'justify-start'"
                            class="group relative flex items-center gap-3 px-4 py-2 mb-1 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.roles.*') || request()->routeIs('superadmin.roles.*') ? '!text-blue-600 dark:!text-blue-400 !bg-blue-50 dark:!bg-gray-700 !font-bold' : '' }}">
                            <x-lucide-lock class="w-5 h-5 shrink-0" />
                            <span x-show="!sidebarCollapsed" class="transition-opacity duration-300 group-hover:font-bold">Roles</span>
                            <!-- Tooltip -->
                            <span x-show="sidebarCollapsed"
                                class="hidden lg:block absolute left-full ml-2 bg-blue-600 text-white text-[11px] font-medium px-2.5 py-1 rounded-md shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 z-50">
                                Roles
                            </span>
                        </a>
                    @endcanany

                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden bg-white dark:bg-gray-800">
            <!-- Top Bar -->
            <header
                class="h-18 bg-white dark:bg-gray-800 flex items-center justify-between px-4 lg:px-6 shrink-0 transition-colors duration-300">
                <div class="flex items-center gap-1">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden p-2 -ml-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <x-lucide-menu class="w-6 h-6" />
                    </button>
                    <button @click="sidebarCollapsed = !sidebarCollapsed"
                        class="hidden lg:block p-2 -ml-2 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100">
                        <x-lucide-menu class="w-6 h-6" />
                    </button>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100 transition-colors">
                        {{ $title ?? 'Title' }}
                    </h1>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-1">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode()"
                        class="hidden lg:block text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-100 transition-colors relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50">
                        <x-lucide-moon x-show="!darkMode" class="w-5 h-5" />
                        <x-lucide-sun x-show="darkMode" class="w-5 h-5" x-cloak />
                    </button>

                    <!-- Fullscreen Toggle -->
                    <button @click="toggleFullscreen()"
                        class="hidden lg:block text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-100 transition-colors relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50"
                        title="Toggle Fullscreen">
                        <x-lucide-expand x-show="!isFullscreen" class="w-5 h-5" />
                        <x-lucide-minimize x-show="isFullscreen" class="w-5 h-5" x-cloak />
                    </button>

                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl md:hover:bg-gray-100 dark:md:hover:bg-gray-700 transition-colors">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->name ?? 'User' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? '' }}
                                </p>
                            </div>
                            <x-lucide-chevron-down
                                class="w-4 h-4 text-gray-400 dark:text-gray-400 transition-transform hidden md:block"
                                x-bind:class="{ 'rotate-180': open }" />
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute right-0 top-full mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 py-2 z-50">

                            <!-- User Info -->
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ auth()->user()->name ?? 'User' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ auth()->user()->email ?? '' }}
                                    </p>
                                </div>
                                <button @click="toggleDarkMode(); open = false"
                                    class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-100 transition-colors relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50">
                                    <x-lucide-moon x-show="!darkMode" class="w-5 h-5" />
                                    <x-lucide-sun x-show="darkMode" class="w-5 h-5" x-cloak />
                                </button>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1">
                                <a href="{{ route('admin.profile') }}" wire:navigate @click="open = false"
                                    class="flex items-center gap-3 px-4 py-2.5 text-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition-colors {{ request()->routeIs('admin.profile.*') ? '!text-blue-600 dark:!text-blue-400 !bg-blue-50 dark:!bg-gray-700' : '' }}">
                                    <x-lucide-user class="w-4 h-4" />
                                    <span class="text-sm font-medium">{{ __('My Profile') }}</span>
                                </a>

                                <span
                                    class="flex items-center gap-3 px-4 py-2.5 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                    <x-lucide-image class="w-4 h-4" />
                                    <span class="text-sm font-medium">{{ __('Theme') }}</span>
                                </span>
                            </div>

                            <!-- Logout -->
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-1 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <x-lucide-log-out class="w-4 h-4" />
                                        <span class="text-sm font-medium">{{ __('Logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 lg:p-6 transition-colors duration-200 lg:rounded-lg">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
        class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"></div>

    @livewireScripts
</body>

</html>