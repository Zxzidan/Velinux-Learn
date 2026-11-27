<x-layouts.app title="Mentor & Admin Panel" :user="$learner">
    <div class="pb-5 border-b vlx-border">
        <span class="text-xs font-mono vlx-text-muted">Governance</span>
        <h1 class="text-2xl sm:text-[28px] font-medium tracking-[-0.02em] vlx-text-primary mt-1">Admin &amp; Mentor Panel</h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach ([
            'Users' => $stats['users'],
            'Courses' => $stats['courses'],
            'Quizzes' => $stats['quizzes'],
            'Cyber labs' => $stats['labs'],
            'Need review' => $stats['pending_reviews'],
            'Solved flags' => $stats['verified_submissions'],
        ] as $label => $count)
            <div class="rounded-xl border vlx-border vlx-surface-card p-4">
                <div class="text-xs vlx-text-muted">{{ $label }}</div>
                <div class="mt-2 text-2xl font-medium font-mono tabular-nums vlx-text-primary">{{ $count }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2 space-y-4">
            <h2 class="text-lg font-medium vlx-text-primary">Review AI Assessments</h2>

            @forelse ($assessmentsToReview as $assessment)
                <div class="rounded-xl border vlx-border vlx-surface-card p-6 space-y-4">
                    <x-velinux.ai-feedback-card :assessment="$assessment" class="border-0 p-0" />

                    <form action="{{ route('admin.assessments.review', $assessment) }}" method="POST" class="border-t vlx-border pt-4 grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-mono vlx-text-muted mb-1">Skor final</label>
                            <input
                                type="number"
                                name="score"
                                min="0"
                                max="100"
                                value="{{ $assessment->score }}"
                                class="w-full h-9 rounded-lg border vlx-border vlx-surface-raised px-3 text-xs font-mono tabular-nums vlx-text-primary"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-mono vlx-text-muted mb-1">Status</label>
                            <select
                                name="status"
                                class="w-full h-9 rounded-lg border vlx-border vlx-surface-raised px-3 text-xs vlx-text-primary"
                            >
                                @foreach (\App\Enums\AssessmentStatus::cases() as $statusCase)
                                    <option value="{{ $statusCase->value }}" @selected($assessment->status === $statusCase)>
                                        {{ $statusCase->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-mono vlx-text-muted mb-1">Catatan mentor</label>
                            <input
                                type="text"
                                name="mentor_notes"
                                value="{{ $assessment->aiFeedback?->mentor_notes }}"
                                placeholder="Catatan validasi mentor"
                                class="w-full h-9 rounded-lg border vlx-border vlx-surface-raised px-3 text-xs vlx-text-primary"
                            />
                        </div>

                        <button
                            type="submit"
                            class="h-9 rounded-lg bg-gray-900 text-white hover:bg-gray-800 dark:bg-[#eef0f5] dark:text-[#0b0d14] dark:hover:bg-white px-4 text-xs font-medium active:scale-[0.98] transition-[background-color,transform] duration-150 cursor-pointer"
                        >
                            Simpan validasi
                        </button>
                    </form>
                </div>
            @empty
                <div class="rounded-xl border vlx-border vlx-surface-card p-8 text-center text-sm vlx-text-muted">
                    Tidak ada assessment yang perlu ditinjau.
                </div>
            @endforelse
        </section>

        <section class="rounded-xl border vlx-border vlx-surface-card p-6 h-fit">
            <h2 class="text-base font-medium vlx-text-primary mb-4">Audit activity logs</h2>
            <ul class="divide-y divide-black/6 dark:divide-white/8">
                @forelse ($recentLogs as $log)
                    <li class="py-3 first:pt-0 last:pb-0 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono font-medium vlx-text-primary">{{ $log->action }}</span>
                            <span class="text-[11px] vlx-text-muted">{{ $log->created_at?->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 vlx-text-secondary">
                            User: {{ $log->user?->name ?? 'System' }}
                        </p>
                    </li>
                @empty
                    <li class="text-sm vlx-text-muted">Belum ada activity log.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-layouts.app>
