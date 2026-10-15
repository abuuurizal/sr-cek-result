<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Page Header -->
    <x-slot:header>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Thanks for signing up! Please verify your email address.') }}
        </p>
    </x-slot:header>

    <div class="mb-5 p-3.5 rounded-lg bg-slate-50 dark:bg-slate-700/40 border border-slate-200 dark:border-slate-700">
        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
            {{ __('Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 p-3 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-800 flex items-start gap-2.5">
            <x-lucide-check-circle class="w-4 h-4 text-green-600 dark:text-green-400 mt-0.5 shrink-0" />
            <p class="text-xs text-green-700 dark:text-green-300 font-medium">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </p>
        </div>
    @endif

    <div class="space-y-4">
        <button wire:click="sendVerification"
            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-[#4885d5] text-white border border-transparent rounded-lg font-bold text-sm uppercase tracking-widest hover:bg-[#3766a5] focus:outline-none focus:ring-1 focus:ring-[#4885d5] focus:ring-offset-1 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
            wire:loading.attr="disabled" wire:target="sendVerification">
            <span wire:loading.remove wire:target="sendVerification" class="flex items-center gap-2">
                <x-lucide-send class="w-4 h-4" /> {{ __('Resend Verification Email') }}
            </span>
            <span wire:loading wire:target="sendVerification" x-cloak class="flex items-center gap-2">
                <svg class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                {{ __('Sending...') }}
            </span>
        </button>

        <div class="text-center">
            <button wire:click="logout" type="button"
                class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors inline-flex items-center gap-1.5">
                <x-lucide-log-out class="w-3.5 h-3.5" /> {{ __('Log Out') }}
            </button>
        </div>
    </div>
</div>