@props([
    'label',
    'value',
    'subtitle' => null,
    'highlight' => false,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border vlx-border vlx-surface-card p-6 transition-colors duration-150 vlx-ease']) }}>
    <p class="text-[13px] vlx-text-muted">{{ $label }}</p>
    <p class="mt-3 text-[28px] sm:text-[32px] leading-none font-medium tabular-nums tracking-[-0.02em] vlx-text-primary">
        {{ $value }}
    </p>
    @if ($subtitle)
        <p class="mt-3 text-xs tabular-nums {{ $highlight ? 'text-emerald-700 dark:text-[#4cc38a]' : 'vlx-text-secondary' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>
