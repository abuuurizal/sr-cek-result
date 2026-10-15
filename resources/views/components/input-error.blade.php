@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'p-4 rounded-lg bg-red-50 border border-red-100 flex items-start gap-3']) }}>
        <x-lucide-alert-circle class="w-5 h-5 text-red-600 mt-0.5 shrink-0" />
        <ul class="text-sm text-red-600 space-y-1">
            @foreach ((array) $messages as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif