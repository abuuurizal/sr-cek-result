@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-4 rounded-lg bg-green-50 border border-green-100 flex items-start gap-3']) }}>
        <x-lucide-check-circle class="w-5 h-5 text-green-600 mt-0.5 shrink-0" />
        <p class="text-sm text-green-600 font-medium">{{ $status }}</p>
    </div>
@endif