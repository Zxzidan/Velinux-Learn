@props([
    'quiz',
])

@php
    $minutes = max(1, intdiv($quiz->time_limit_seconds, 60));
@endphp

<article {{ $attributes->merge(['class' => 'rounded-xl border vlx-border vlx-surface-card p-4 transition-colors duration-150 vlx-ease']) }}>
    <div class="flex items-center justify-between gap-2 mb-1.5">
        <span class="text-xs font-mono vlx-text-muted">
            {{ $quiz->quiz_type->label() }}
        </span>
        <span class="font-mono text-xs tabular-nums font-medium text-emerald-700 dark:text-[#4cc38a]">
            +{{ $quiz->xp_reward }} XP
        </span>
    </div>

    <h3 class="text-sm font-medium vlx-text-primary">{{ $quiz->title }}</h3>

    <div class="mt-3 pt-2.5 border-t vlx-border flex items-center justify-between text-xs">
        <span class="font-mono tabular-nums vlx-text-muted">{{ $quiz->questions_count ?? $quiz->questions->count() }} soal &middot; {{ $minutes }} menit</span>
        <a
            href="{{ route('quizzes.index') }}#quiz-{{ $quiz->slug }}"
            class="font-medium text-emerald-700 dark:text-[#4cc38a] hover:underline"
        >
            Mulai quiz
        </a>
    </div>
</article>
