@props([
    'label',
    'value',
    'subtitle' => null,
    'badge' => null,
    'accent' => 'emerald',
])

@php
    $accentClasses = match ($accent) {
        'sky' => 'text-sky-700 dark:text-sky-400 bg-sky-500/10 border-sky-500/20',
        'amber' => 'text-amber-700 dark:text-amber-400 bg-amber-500/10 border-amber-500/20',
        'purple' => 'text-purple-700 dark:text-purple-400 bg-purple-500/10 border-purple-500/20',
        default => 'text-primary-700 dark:text-primary-400 bg-primary-500/10 border-primary-500/20',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 shadow-xs transition-colors duration-200']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-xs font-mono uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $label }}</p>
            <p class="mt-2.5 text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
        </div>
        @if (isset($icon))
            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border {{ $accentClasses }}">
                {{ $icon }}
            </span>
        @endif
    </div>
    <div class="mt-4 flex items-center justify-between gap-3 text-xs">
        @if ($subtitle)
            <span class="text-gray-500 dark:text-gray-400 truncate">{{ $subtitle }}</span>
        @endif
        @if ($badge)
            <span class="shrink-0 whitespace-nowrap rounded-md px-2.5 py-1 font-mono font-medium {{ $accentClasses }}">
                {{ $badge }}
            </span>
        @endif
    </div>
</div>
