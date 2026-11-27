@props([
    'code',
    'title' => 'velinux@cyberlab:~',
    'language' => 'bash',
    'copyId' => null,
])

@php
    $elementId = $copyId ?? 'snippet-'.md5($title.$code);
@endphp

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-white/10 bg-[#07080d] text-[#eef0f5]']) }}>
    <div class="flex items-center justify-between border-b border-white/8 bg-[#11141c] px-4 py-2">
        <div class="flex items-center gap-2 min-w-0">
            <span class="h-2 w-2 rounded-full bg-white/20"></span>
            <span class="h-2 w-2 rounded-full bg-white/20"></span>
            <span class="h-2 w-2 rounded-full bg-[#4cc38a]/70"></span>
            <span class="ml-1.5 font-mono text-xs text-[#7a8296] truncate">{{ $title }}</span>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <span class="font-mono text-[11px] text-[#7a8296]">{{ $language }}</span>
            <button
                type="button"
                data-copy-target="{{ $elementId }}"
                class="rounded-md border border-white/10 bg-[#1a1e28] px-2 py-0.5 text-[11px] font-mono text-[#a3a9b8] hover:text-white active:scale-[0.98] transition-[color,transform] duration-150 cursor-pointer"
            >
                <span data-copy-label>Copy</span>
            </button>
        </div>
    </div>
    <pre id="{{ $elementId }}" class="overflow-x-auto p-4 font-mono text-xs leading-relaxed text-[#4cc38a]"><code>{{ $code }}</code></pre>
</div>
