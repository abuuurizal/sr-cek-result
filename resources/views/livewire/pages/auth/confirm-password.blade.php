<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (
            !Auth::guard('web')->validate([
                'email' => Auth::user()->email,
                'password' => $this->password,
            ])
        ) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div x-data="{ showPassword: false }">
    <!-- Page Header -->
    <x-slot:header>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('This is a secure area. Please confirm your password before continuing.') }}
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

    <form wire:submit="confirmPassword">
        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Password') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-lock class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="password" id="password" :type="showPassword ? 'text' : 'password'" name="password"
                    required autocomplete="current-password"
                    class="block w-full pl-9 pr-9 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:border-[#4885d5] transition-all text-sm font-medium text-slate-900 dark:text-white dark:bg-slate-900 placeholder-slate-400"
                    placeholder="••••••••">
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-[#3766a5] transition-colors focus:outline-none">
                    <span x-show="!showPassword"><x-lucide-eye class="w-4 h-4" /></span>
                    <span x-show="showPassword" x-cloak><x-lucide-eye-off class="w-4 h-4" /></span>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-end mt-2">
            <button type="submit"
                class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-[#4885d5] text-white border border-transparent rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-[#3766a5] focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:ring-offset-1 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                wire:loading.attr="disabled" wire:target="confirmPassword">
                <span wire:loading.remove wire:target="confirmPassword" class="flex items-center gap-2">
                    <x-lucide-check-circle class="w-4 h-4" /> {{ __('Confirm') }}
                </span>
                <span wire:loading wire:target="confirmPassword" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    {{ __('Confirming...') }}
                </span>
            </button>
        </div>
    </form>
</div>