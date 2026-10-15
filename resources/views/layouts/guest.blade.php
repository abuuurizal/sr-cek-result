<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ isset($site_settings->favicon_path) ? asset('storage/' . $site_settings->favicon_path) : asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=open-sans:300,400,500,600,700,800" rel="stylesheet" />

    <!-- Livewire Styles & Scripts -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Prevent iOS Safari auto-zoom on input focus (requires font-size >= 16px) */
        @media screen and (max-width: 768px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-slate-900 min-h-screen font-sans antialiased text-gray-900 dark:text-gray-100">
    <!-- Main Container -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-50 dark:bg-slate-900 relative">

        <!-- Background Pattern (Blue) -->
        <div class="fixed inset-0 z-0 opacity-40 dark:opacity-10" style="background-image: 
            radial-gradient(circle at 20% 30%, #4885d5 0%, transparent 20%), 
            radial-gradient(circle at 80% 70%, #4885d5 0%, transparent 20%);">
        </div>

        <div class="w-full sm:max-w-md min-h-screen sm:min-h-0 flex flex-col justify-center px-6 py-6 bg-white dark:bg-slate-800 overflow-hidden sm:rounded-xl relative z-10 sm:border border-gray-100 dark:border-slate-700 shadow-xl">
            <!-- Logo & Header -->
            <div class="mb-5 text-center">
                <a href="{{ url('/') }}" wire:navigate class="inline-block mb-3 h-12 hover:opacity-80 transition-opacity">
                    @if(isset($site_settings->logo_black) && isset($site_settings->logo_white))
                        <img src="{{ asset('storage/' . $site_settings->logo_black) }}"
                            alt="{{ $site_settings->site_name ?? config('app.name', 'Logo') }}" class="h-full w-auto object-contain dark:hidden" />
                        <img src="{{ asset('storage/' . $site_settings->logo_white) }}"
                            alt="{{ $site_settings->site_name ?? config('app.name', 'Logo') }}" class="h-full w-auto object-contain hidden dark:block" />
                    @else
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-[#4885d5] flex items-center justify-center text-white font-bold text-lg shadow-md shadow-[#4885d5]/30">
                                {{ strtoupper(substr(config('app.name', 'SR'), 0, 2)) }}
                            </div>
                            <span class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">{{ config('app.name', 'Laravel') }}</span>
                        </div>
                    @endif
                </a>

                {{ $header ?? '' }}
            </div>

            {{ $slot }}

            <!-- Footer Text -->
            <div class="mt-5 text-center">
                <p class="text-[11px] text-slate-400">
                    {{ $site_settings->copyright_text ?? '© ' . date('Y') . ' ' . config('app.name', 'Laravel') . '. All rights reserved.' }}
                </p>
            </div>
        </div>
    </div>

    @stack('scripts')
    @livewireScripts
</body>

</html>