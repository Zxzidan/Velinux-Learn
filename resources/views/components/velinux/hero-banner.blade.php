@props([
    'user' => null,
    'activeLabCount' => 0,
    'publishedCourseCount' => 0,
])

<header {{ $attributes->merge(['class' => 'flex flex-col md:flex-row md:items-end md:justify-between gap-4 pb-6 border-b vlx-border']) }}>
    <div>
        <div class="text-xs font-mono vlx-text-muted">
            VELINUX &middot; Learn. Practice. Secure.
        </div>
        <h1 class="mt-1.5 text-2xl sm:text-[30px] leading-[1.15] font-medium tracking-[-0.02em] vlx-text-primary">
            Cybersecurity Workspace
            @if ($user)
                <span class="vlx-text-muted font-normal">&middot; {{ $user->cyberRank() }}</span>
            @endif
        </h1>
    </div>

    <div class="flex flex-wrap items-center gap-3 shrink-0">
        <a
            href="{{ route('courses.index') }}"
            class="inline-flex items-center justify-center h-9 px-4 rounded-lg text-sm font-medium bg-gray-900 text-white hover:bg-gray-800 dark:bg-[#eef0f5] dark:text-[#0b0d14] dark:hover:bg-white active:scale-[0.98] transition-[background-color,transform] duration-150 vlx-ease"
        >
            Start learning
        </a>
        <a
            href="{{ route('labs.index') }}"
            class="inline-flex items-center justify-center h-9 px-4 rounded-lg text-sm font-medium border vlx-border vlx-surface-raised vlx-text-primary hover:vlx-border-strong active:scale-[0.98] transition-[border-color,transform] duration-150 vlx-ease"
        >
            Cyber Lab ({{ $activeLabCount }})
        </a>
    </div>
</header>
