@props([
    'assessment',
])

@php
    $feedback = $assessment->aiFeedback;
    $status = $assessment->status;
@endphp

<section {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-800 dark:bg-gray-900 shadow-xs']) }}>
    <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-mono uppercase tracking-wider text-primary-600 dark:text-primary-400">
                    AI Assessment Result
                </span>
                <span class="rounded-md px-2 py-0.5 text-[11px] font-mono font-semibold {{ $status->badgeClasses() }}">
                    {{ $status->label() }}
                </span>
            </div>
            <h3 class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                {{ $assessment->quiz?->title ?? 'Cybersecurity Evaluation' }}
            </h3>
            @if ($assessment->user)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Peserta: {{ $assessment->user->name }} &middot; {{ $assessment->ai_evaluated_at?->diffForHumans() ?? 'Baru dievaluasi' }}
                </p>
            @endif
        </div>

        <div class="rounded-xl border border-primary-500/30 bg-primary-500/10 px-4 py-2 text-right">
            <div class="text-2xl font-bold font-mono text-primary-600 dark:text-primary-400">
                {{ $assessment->score }}/{{ $assessment->max_score }}
            </div>
            <div class="text-[11px] text-gray-500 dark:text-gray-400">Skor Evaluasi</div>
        </div>
    </div>

    @if ($feedback)
        <div class="space-y-3">
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950/70">
                <div class="text-xs font-mono uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                    AI Feedback
                </div>
                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                    {{ $feedback->summary }}
                </p>
            </div>

            @if ($feedback->recommended_topic)
                <div class="rounded-xl border border-primary-500/25 bg-primary-500/10 p-4">
                    <div class="text-xs font-mono uppercase tracking-wider text-primary-700 dark:text-primary-300 mb-1">
                        Rekomendasi Pembelajaran
                    </div>
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        Pelajari kembali materi <span class="font-semibold text-primary-700 dark:text-primary-300">{{ $feedback->recommended_topic }}</span>.
                    </p>
                </div>
            @endif

            @if ($feedback->mentor_notes)
                <div class="rounded-xl border border-cyan-500/25 bg-cyan-500/10 p-4">
                    <div class="text-xs font-mono uppercase tracking-wider text-cyan-700 dark:text-cyan-300 mb-1">
                        Validasi Mentor (Human-in-the-Loop)
                    </div>
                    <p class="text-sm text-gray-800 dark:text-gray-200">{{ $feedback->mentor_notes }}</p>
                </div>
            @endif
        </div>
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu ringkasan feedback AI.</p>
    @endif
</section>
