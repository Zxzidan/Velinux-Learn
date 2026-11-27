@props([
    'lab',
    'isSolved' => false,
    'activeSession' => null,
    'showSubmissionForm' => false,
])

<article {{ $attributes->merge(['class' => 'flex flex-col justify-between rounded-xl border vlx-border vlx-surface-card p-5 transition-colors duration-150 vlx-ease']) }}>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
            <span class="text-xs font-mono vlx-text-muted">
                {{ $lab->category }}
            </span>
            <div class="flex items-center gap-2">
                @if ($isSolved)
                    <span class="rounded-md px-2 py-0.5 text-[11px] font-mono font-medium text-emerald-700 dark:text-[#4cc38a] bg-emerald-500/8 border border-emerald-600/15 dark:border-[#4cc38a]/20">
                        Solved
                    </span>
                @elseif ($activeSession)
                    <span class="rounded-md px-2 py-0.5 text-[11px] font-mono font-medium text-amber-700 dark:text-amber-300 bg-amber-500/8 border border-amber-500/20">
                        Running
                    </span>
                @endif
                <span class="rounded-md px-2 py-0.5 text-[11px] font-mono font-medium {{ $lab->difficulty->badgeClasses() }}">
                    {{ $lab->difficulty->label() }}
                </span>
            </div>
        </div>

        <h3 class="text-base font-medium vlx-text-primary">
            {{ $lab->title }}
        </h3>

        @if ($showSubmissionForm)
            <p class="mt-1.5 text-xs vlx-text-secondary line-clamp-2 max-w-[60ch]">
                {{ $lab->scenario }}
            </p>
        @endif

        @if ($lab->terminal_prompt)
            <div class="mt-3.5">
                <x-velinux.terminal-preview
                    :title="$lab->target_host ? 'sandbox://'.$lab->target_host : 'velinux@cyberlab:~'"
                    :code="$lab->terminal_prompt"
                    language="shell"
                />
            </div>
        @endif
    </div>

    <div class="mt-4 pt-3.5 border-t vlx-border">
        @if ($showSubmissionForm)
            <form action="{{ route('labs.submit', $lab) }}" method="POST">
                @csrf
                <div class="flex flex-col sm:flex-row gap-2">
                    <input
                        type="text"
                        name="submitted_flag"
                        placeholder="VELINUX{flag}"
                        required
                        class="flex-1 h-9 rounded-lg border vlx-border vlx-surface-raised px-3 font-mono text-xs vlx-text-primary focus:outline-none focus:border-emerald-600 dark:focus:border-[#4cc38a]"
                    />
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center h-9 rounded-lg bg-gray-900 text-white hover:bg-gray-800 dark:bg-[#eef0f5] dark:text-[#0b0d14] dark:hover:bg-white px-4 text-xs font-medium active:scale-[0.98] transition-[background-color,transform] duration-150 cursor-pointer"
                    >
                        Submit (+{{ $lab->xp_reward }} XP)
                    </button>
                </div>
            </form>
        @else
            <div class="flex items-center justify-between gap-3">
                <span class="font-mono text-xs tabular-nums vlx-text-muted">
                    {{ $lab->target_host ?? 'Sandbox' }}
                    &middot; <span class="text-emerald-700 dark:text-[#4cc38a]">+{{ $lab->xp_reward }} XP</span>
                </span>
                <a
                    href="{{ route('labs.index') }}"
                    class="inline-flex items-center h-8 rounded-lg border vlx-border vlx-surface-raised px-3 text-xs font-medium vlx-text-primary hover:vlx-border-strong active:scale-[0.98] transition-[border-color,transform] duration-150"
                >
                    Buka lab
                </a>
            </div>
        @endif
    </div>
</article>
