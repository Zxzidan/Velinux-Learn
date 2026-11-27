@props([
    'quiz',
])

@php
    $minutes = max(1, intdiv($quiz->time_limit_seconds, 60));
@endphp

<article {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 shadow-xs transition-colors duration-200']) }}>
    <div class="flex items-center justify-between gap-2 mb-3">
        <span class="rounded-md px-2.5 py-1 text-[11px] font-mono font-semibold {{ $quiz->is_daily ? 'bg-amber-500/15 text-amber-600 dark:text-amber-300 border border-amber-500/30' : 'bg-sky-500/15 text-sky-600 dark:text-sky-300 border border-sky-500/30' }}">
            {{ $quiz->quiz_type->label() }}
        </span>
        <span class="font-mono text-xs font-semibold text-primary-600 dark:text-primary-400">
            +{{ $quiz->xp_reward }} XP
        </span>
    </div>

    <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
    @if ($quiz->description)
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $quiz->description }}</p>
    @endif

    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
        <span class="font-mono">{{ $quiz->questions_count ?? $quiz->questions->count() }} Soal &middot; {{ $minutes }} menit</span>
        <a
            href="{{ route('quizzes.index') }}#quiz-{{ $quiz->slug }}"
            class="font-semibold text-primary-600 dark:text-primary-400 hover:underline"
        >
            Mulai Quiz &rarr;
        </a>
    </div>
</article>
