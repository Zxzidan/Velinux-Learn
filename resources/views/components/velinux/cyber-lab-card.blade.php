@props([
    'lab',
    'isSolved' => false,
    'activeSession' => null,
    'showSubmissionForm' => false,
])

<article {{ $attributes->merge(['class' => 'flex flex-col justify-between rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 shadow-xs transition-colors duration-200']) }}>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
            <span class="rounded-md bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-[11px] font-mono text-gray-700 dark:text-gray-300">
                {{ $lab->category }}
            </span>
            <div class="flex items-center gap-2">
                @if ($isSolved)
                    <span class="rounded-md bg-primary-500/15 border border-primary-500/30 px-2 py-0.5 text-[11px] font-mono font-semibold text-primary-600 dark:text-primary-400">
                        Solved
                    </span>
                @elseif ($activeSession)
                    <span class="rounded-md bg-amber-500/15 border border-amber-500/30 px-2 py-0.5 text-[11px] font-mono font-semibold text-amber-600 dark:text-amber-400">
                        Sandbox Running
                    </span>
                @endif
                <span class="rounded-md px-2 py-0.5 text-[11px] font-mono font-semibold {{ $lab->difficulty->badgeClasses() }}">
                    {{ $lab->difficulty->label() }}
                </span>
            </div>
        </div>

        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
            {{ $lab->title }}
        </h3>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ $lab->scenario }}
        </p>

        @if ($lab->terminal_prompt)
            <div class="mt-4">
                <x-velinux.terminal-preview
                    :title="$lab->target_host ? 'sandbox://'.$lab->target_host : 'velinux@cyberlab:~'"
                    :code="$lab->terminal_prompt"
                    language="shell"
                />
            </div>
        @endif
    </div>

    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
        @if ($showSubmissionForm)
            <form action="{{ route('labs.submit', $lab) }}" method="POST" class="space-y-3">
                @csrf
                <div class="flex flex-col sm:flex-row gap-2">
                    <input
                        type="text"
                        name="submitted_flag"
                        placeholder="VELINUX{masukkan_flag_disini}"
                        required
                        class="flex-1 rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 font-mono text-xs text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-950 dark:text-primary-300"
                    />
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-primary-500 px-4 py-2 text-xs font-semibold text-gray-950 hover:bg-primary-400 transition cursor-pointer"
                    >
                        Submit Flag (+{{ $lab->xp_reward }} XP)
                    </button>
                </div>
            </form>
        @else
            <div class="flex items-center justify-between gap-3">
                <span class="font-mono text-xs text-gray-500 dark:text-gray-400">
                    Target: <span class="text-gray-800 dark:text-gray-200">{{ $lab->target_host ?? 'Isolated Container' }}</span>
                    &middot; <span class="text-primary-600 dark:text-primary-400">+{{ $lab->xp_reward }} XP</span>
                </span>
                <a
                    href="{{ route('labs.index') }}"
                    class="inline-flex items-center rounded-lg bg-primary-500/15 px-3 py-1.5 text-xs font-semibold text-primary-700 dark:text-primary-300 hover:bg-primary-500/25 transition"
                >
                    Buka Cyber Lab
                </a>
            </div>
        @endif
    </div>
</article>
