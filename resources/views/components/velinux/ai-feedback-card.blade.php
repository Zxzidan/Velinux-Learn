@props([
    'assessment',
])

@php
    $feedback = $assessment->aiFeedback;
    $status = $assessment->status;
    $scorePercent = $assessment->max_score > 0
        ? min(100, max(0, (int) round(($assessment->score / $assessment->max_score) * 100)))
        : 0;
@endphp

<section {{ $attributes->merge(['class' => 'rounded-xl border vlx-border vlx-surface-card p-5 transition-colors duration-150 vlx-ease']) }}>
    <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="text-xs font-mono vlx-text-muted">AI Assessment Result</span>
                <span class="rounded-md px-2 py-0.5 text-[11px] font-mono font-medium {{ $status->badgeClasses() }}">
                    {{ $status->label() }}
                </span>
            </div>
            <h3 class="mt-1 text-base font-medium vlx-text-primary">
                {{ $assessment->quiz?->title ?? 'Cybersecurity Evaluation' }}
            </h3>
            @if ($assessment->user)
                <p class="text-xs vlx-text-muted mt-0.5">
                    {{ $assessment->user->name }} &middot; {{ $assessment->ai_evaluated_at?->diffForHumans() ?? 'Baru dievaluasi' }}
                </p>
            @endif
        </div>

        <div class="text-right">
            <div class="text-[28px] leading-none font-medium font-mono tabular-nums vlx-text-primary">
                {{ $assessment->score }}<span class="text-base vlx-text-muted">/{{ $assessment->max_score }}</span>
            </div>
            <div class="mt-2 h-1.5 w-24 rounded-full vlx-surface-raised overflow-hidden ml-auto">
                <div class="h-full rounded-full bg-emerald-600 dark:bg-[#4cc38a]" style="width: {{ $scorePercent }}%"></div>
            </div>
        </div>
    </div>

    @if ($feedback)
        <div class="space-y-2.5">
            <div class="rounded-lg border vlx-border vlx-surface-raised p-3.5">
                <div class="text-xs font-mono vlx-text-muted mb-1">AI feedback</div>
                <p class="text-xs sm:text-sm vlx-text-secondary leading-relaxed">
                    {{ $feedback->summary }}
                </p>
            </div>

            @if ($feedback->recommended_topic)
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg border vlx-border vlx-surface-page px-3.5 py-2.5 text-xs">
                    <span class="font-mono text-emerald-700 dark:text-[#4cc38a]">Rekomendasi</span>
                    <span class="font-medium vlx-text-primary">{{ $feedback->recommended_topic }}</span>
                </div>
            @endif

            @if ($feedback->mentor_notes)
                <div class="rounded-lg border vlx-border vlx-surface-page px-3.5 py-2.5 text-xs">
                    <span class="font-mono vlx-text-muted mr-2">Mentor:</span>
                    <span class="vlx-text-secondary">{{ $feedback->mentor_notes }}</span>
                </div>
            @endif
        </div>
    @else
        <p class="text-sm vlx-text-muted">Menunggu feedback AI.</p>
    @endif
</section>
