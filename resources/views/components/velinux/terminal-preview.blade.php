@props([
    'code',
    'title' => 'velinux@cyberlab:~',
    'language' => 'bash',
    'copyId' => null,
])

@php
    $elementId = $copyId ?? 'snippet-'.md5($title.$code);
@endphp

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-gray-800 bg-gray-950 text-gray-100 shadow-sm']) }}>
    <div class="flex items-center justify-between border-b border-gray-800 bg-gray-900/90 px-4 py-2">
        <div class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 rounded-full bg-rose-500/80"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-amber-500/80"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-primary-500/80"></span>
            <span class="ml-2 font-mono text-xs text-gray-400">{{ $title }}</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="font-mono text-[10px] uppercase text-primary-400">{{ $language }}</span>
            <button
                type="button"
                data-copy-target="{{ $elementId }}"
                class="rounded border border-gray-700 px-2 py-0.5 text-[11px] font-mono text-gray-300 hover:border-primary-500/50 hover:text-primary-300 transition"
            >
                <span data-copy-label>Copy</span>
            </button>
        </div>
    </div>
    <pre id="{{ $elementId }}" class="overflow-x-auto p-4 font-mono text-xs sm:text-sm leading-relaxed text-primary-300"><code>{{ $code }}</code></pre>
</div>
