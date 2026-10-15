<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('admin.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div x-data="{ showPassword: false, showPasswordConfirm: false }">
    <!-- Page Header -->
    <x-slot:header>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Register to start your journey') }}
        </p>
    </x-slot:header>

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

    <!-- Google Sign Up -->
    @if (Route::has('auth.google'))
        <a href="{{ route('auth.google') }}"
            class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg font-semibold text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-[#4885d5] focus:ring-offset-2 transition-all mb-4">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            {{ __('Sign up with Google') }}
        </a>

        <!-- Divider -->
        <div class="relative mb-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-slate-800 text-slate-400 font-medium text-xs">{{ __('or register with') }}</span>
            </div>
        </div>
    @endif

    <form wire:submit="register">
        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Full Name') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-user class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                    class="block w-full pl-9 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:border-[#4885d5] transition-all text-sm font-medium text-slate-900 dark:text-white dark:bg-slate-900 placeholder-slate-400"
                    placeholder="John Doe">
            </div>
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Email') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-mail class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
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
                <input wire:model="password" id="password" type="password" :type="showPassword ? 'text' : 'password'" name="password" required
                    autocomplete="new-password"
                    class="block w-full pl-9 pr-9 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:border-[#4885d5] transition-all text-sm font-medium text-slate-900 dark:text-white dark:bg-slate-900 placeholder-slate-400"
                    placeholder="••••••••">
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#3766a5] transition-colors focus:outline-none">
                    <span x-show="!showPassword"><x-lucide-eye class="w-4 h-4" /></span>
                    <span x-show="showPassword" x-cloak><x-lucide-eye-off class="w-4 h-4" /></span>
                </button>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-5">
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Confirm Password') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-lock class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" required
                    autocomplete="new-password"
                    class="block w-full pl-9 pr-9 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:border-[#4885d5] transition-all text-sm font-medium text-slate-900 dark:text-white dark:bg-slate-900 placeholder-slate-400"
                    placeholder="••••••••">
                <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#3766a5] transition-colors focus:outline-none">
                    <span x-show="!showPasswordConfirm"><x-lucide-eye class="w-4 h-4" /></span>
                    <span x-show="showPasswordConfirm" x-cloak><x-lucide-eye-off class="w-4 h-4" /></span>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-end mt-2">
            <button type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-[#4885d5] text-white border border-transparent rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-[#3766a5] focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:ring-offset-1 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                wire:loading.attr="disabled" wire:target="register">
                <span wire:loading.remove wire:target="register" class="flex items-center gap-2">
                    <x-lucide-user-plus class="w-4 h-4" /> {{ __('Register') }}
                </span>
                <span wire:loading wire:target="register" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    {{ __('Creating account...') }}
                </span>
            </button>
        </div>
    </form>

    <!-- Login Link -->
    <div class="mt-5 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" wire:navigate class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors">{{ __('Sign in') }}</a>
        </p>
    </div>
</div>