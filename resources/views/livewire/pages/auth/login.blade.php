<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div x-data="{ showPassword: false }">
    <!-- Page Header -->
    <x-slot:header>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Sign in to continue') }}
        </p>
    </x-slot:header>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800 flex items-start gap-3">
            <x-lucide-alert-circle class="w-4 h-4 text-red-600 dark:text-red-400 mt-0.5 shrink-0" />
            <div class="text-sm text-red-600 dark:text-red-400">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Google Sign In -->
    @if (Route::has('auth.google'))
        <a href="{{ route('auth.google') }}"
            class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg font-semibold text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-[#4885d5] focus:ring-offset-2 transition-all mb-4">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            {{ __('Sign in with Google') }}
        </a>

        <!-- Divider -->
        <div class="relative mb-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-slate-800 text-slate-400 font-medium text-xs">{{ __('or sign in with') }}</span>
            </div>
        </div>
    @endif

    <form wire:submit="login" method="POST">
        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Email') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-mail class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="form.email" id="email" type="email" name="email" required autofocus
                    autocomplete="username"
                    class="block w-full pl-9 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:border-[#4885d5] transition-all text-sm font-medium text-slate-900 dark:text-white dark:bg-slate-900 placeholder-slate-400"
                    placeholder="user@example.com">
            </div>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Password') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-lock class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="form.password" id="password" type="password" :type="showPassword ? 'text' : 'password'" name="password" required
                    autocomplete="current-password"
                    class="block w-full pl-9 pr-9 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:border-[#4885d5] transition-all text-sm font-medium text-slate-900 dark:text-white dark:bg-slate-900 placeholder-slate-400"
                    placeholder="••••••••">
                <!-- Show/Hide Password Toggle -->
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#3766a5] transition-colors focus:outline-none">
                    <span x-show="!showPassword"><x-lucide-eye class="w-4 h-4" /></span>
                    <span x-show="showPassword" x-cloak><x-lucide-eye-off class="w-4 h-4" /></span>
                </button>
            </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-3 mb-5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative flex items-center">
                    <input wire:model="form.remember" id="remember_me" type="checkbox"
                        class="peer h-4 w-4 cursor-pointer appearance-none rounded border border-slate-300 dark:border-slate-600 dark:bg-slate-900 transition-all checked:border-[#4885d5] checked:bg-[#4885d5] focus:ring-1 focus:ring-[#4885d5]/20"
                        name="remember">
                    <!-- Custom Check Icon -->
                    <x-lucide-check
                        class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" />
                </div>
                <span
                    class="ml-2 text-xs text-slate-600 dark:text-slate-400 font-medium group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate
                    class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-end mt-2">
            <button type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-[#4885d5] text-white border border-transparent rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-[#3766a5] focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:ring-offset-1 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                wire:loading.attr="disabled" wire:target="login">
                <span wire:loading.remove wire:target="login" class="flex items-center gap-2">
                    <x-lucide-log-in class="w-4 h-4" /> {{ __('Sign In') }}
                </span>
                <span wire:loading wire:target="login" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    {{ __('Signing in...') }}
                </span>
            </button>
        </div>
    </form>

    <!-- Register Link -->
    @if (Route::has('register'))
        <div class="mt-5 text-center">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" wire:navigate
                    class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors">{{ __('Register') }}</a>
            </p>
        </div>
    @endif
</div>