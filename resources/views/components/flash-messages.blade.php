{{-- Success Toast --}}
@if (session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8"
        x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-8"
        class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-3 bg-green-600 text-white rounded-xl shadow-lg shadow-green-600/20 max-w-sm">
        <x-lucide-check-circle class="w-5 h-5 shrink-0" />
        <p class="text-sm font-medium">{{ session('success') }}</p>
        <button @click="show = false" class="ml-auto shrink-0 hover:text-green-200 transition-colors">
            <x-lucide-x class="w-4 h-4" />
        </button>
    </div>
@endif

{{-- Error Inline Alert --}}
@if (session('error'))
    <div
        class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center gap-3">
        <x-lucide-alert-circle class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" />
        <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
    </div>
@endif