<x-layouts.app title="Mentor & Admin Panel" :user="$learner">
    <div class="mb-6">
        <span class="font-mono text-xs uppercase tracking-wider text-primary-600 dark:text-primary-400">Human-in-the-Loop Governance</span>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Admin &amp; Mentor Dashboard</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Validasi hasil penilaian AI, pantau aktivitas Cyber Lab, dan ringkasan platform VELINUX.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
        @foreach ([
            'Users' => $stats['users'],
            'Courses' => $stats['courses'],
            'Quizzes' => $stats['quizzes'],
            'Cyber Labs' => $stats['labs'],
            'Need Review' => $stats['pending_reviews'],
            'Solved Flags' => $stats['verified_submissions'],
        ] as $label => $count)
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="text-[11px] font-mono uppercase text-gray-500 dark:text-gray-400">{{ $label }}</div>
                <div class="mt-1 text-2xl font-bold font-mono text-gray-900 dark:text-white">{{ $count }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2 space-y-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Review AI Assessments</h2>

            @forelse ($assessmentsToReview as $assessment)
                <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 space-y-4">
                    <x-velinux.ai-feedback-card :assessment="$assessment" class="border-0 p-0 shadow-none bg-transparent dark:bg-transparent" />

                    <form action="{{ route('admin.assessments.review', $assessment) }}" method="POST" class="border-t border-gray-200 dark:border-gray-800 pt-4 grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-[11px] font-mono text-gray-500 dark:text-gray-400 mb-1">Skor Final</label>
                            <input
                                type="number"
                                name="score"
                                min="0"
                                max="100"
                                value="{{ $assessment->score }}"
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-1.5 text-xs font-mono text-gray-900 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                            />
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono text-gray-500 dark:text-gray-400 mb-1">Status</label>
                            <select
                                name="status"
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-1.5 text-xs text-gray-900 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                            >
                                @foreach (\App\Enums\AssessmentStatus::cases() as $statusCase)
                                    <option value="{{ $statusCase->value }}" @selected($assessment->status === $statusCase)>
                                        {{ $statusCase->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-mono text-gray-500 dark:text-gray-400 mb-1">Catatan Mentor</label>
                            <input
                                type="text"
                                name="mentor_notes"
                                value="{{ $assessment->aiFeedback?->mentor_notes }}"
                                placeholder="Catatan validasi mentor..."
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-1.5 text-xs text-gray-900 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                            />
                        </div>

                        <button
                            type="submit"
                            class="rounded-lg bg-primary-500 px-4 py-2 text-xs font-semibold text-gray-950 hover:bg-primary-400 transition cursor-pointer"
                        >
                            Simpan Validasi
                        </button>
                    </form>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Tidak ada assessment yang perlu ditinjau.
                </div>
            @endforelse
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 h-fit">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Audit Activity Logs</h2>
            <ul class="space-y-3">
                @forelse ($recentLogs as $log)
                    <li class="rounded-lg border border-gray-100 bg-gray-50 p-3 text-xs dark:border-gray-800 dark:bg-gray-950/60">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono font-semibold text-primary-600 dark:text-primary-400">{{ $log->action }}</span>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400">{{ $log->created_at?->diffForHumans() }}</span>
                        </div>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">
                            User: {{ $log->user?->name ?? 'System' }}
                        </p>
                    </li>
                @empty
                    <li class="text-sm text-gray-500 dark:text-gray-400">Belum ada activity log.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-layouts.app>
