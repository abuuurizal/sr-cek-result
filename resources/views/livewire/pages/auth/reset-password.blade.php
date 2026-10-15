<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div x-data="{ showPassword: false, showPasswordConfirm: false }">
    <!-- Page Header -->
    <x-slot:header>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Enter your new password below') }}
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

    <form wire:submit="resetPassword">
        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Email') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-mail class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="email" id="email" type="email" name="email" required autofocus
                    autocomplete="username"
                    class="block w-full pl-9 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:border-[#4885d5] transition-all text-sm font-medium text-slate-900 dark:text-white dark:bg-slate-900 placeholder-slate-400"
                    placeholder="user@example.com">
            </div>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('New Password') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-lock class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="password" id="password" :type="showPassword ? 'text' : 'password'" name="password" required
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
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ __('Confirm New Password') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-lucide-lock class="h-4 w-4 text-slate-400" />
                </div>
                <input wire:model="password_confirmation" id="password_confirmation" :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" required
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
                wire:loading.attr="disabled" wire:target="resetPassword">
                <span wire:loading.remove wire:target="resetPassword" class="flex items-center gap-2">
                    <x-lucide-key class="w-4 h-4" /> {{ __('Reset Password') }}
                </span>
                <span wire:loading wire:target="resetPassword" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    {{ __('Resetting...') }}
                </span>
            </button>
        </div>
    </form>
</div>