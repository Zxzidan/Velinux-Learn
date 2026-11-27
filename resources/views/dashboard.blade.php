<x-layouts.app title="Overview" :user="$learner">
    <x-velinux.hero-banner
        :user="$learner"
        :published-course-count="$publishedCourseCount"
        :active-lab-count="$activeLabCount"
    />

    <!-- Compact numeric KPI row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-velinux.stat-card
            label="Learning progress"
            :value="$averageProgress.'%'"
            :subtitle="$publishedCourseCount.' kelas'"
            :highlight="$averageProgress > 0"
        />

        <x-velinux.stat-card
            label="Total XP"
            :value="number_format($learner?->xp_points ?? 0)"
            :subtitle="$learner?->cyberRank() ?? 'Cadet'"
            :highlight="true"
        />

        <x-velinux.stat-card
            label="Cyber labs solved"
            :value="count($solvedLabIds).' / '.$activeLabCount"
            subtitle="Sandbox aktif"
        />

        <x-velinux.stat-card
            label="Daily streak"
            :value="($learner?->current_streak ?? 0).' hari'"
            :subtitle="$achievementsCount.' badge'"
        />
    </div>

    <!-- AI Assessment + Daily Challenge -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-7">
            @if ($latestAssessment)
                <x-velinux.ai-feedback-card :assessment="$latestAssessment" />
            @else
                <div class="rounded-xl border vlx-border vlx-surface-card p-6 text-center text-sm vlx-text-muted">
                    Belum ada evaluasi AI.
                </div>
            @endif
        </div>

        <div class="lg:col-span-5 rounded-xl border vlx-border vlx-surface-card p-5 space-y-4">
            <div class="flex items-center justify-between gap-2">
                <h2 class="text-base font-medium vlx-text-primary">Daily challenge &amp; XP</h2>
                <a href="{{ route('quizzes.index') }}" class="text-xs font-medium text-emerald-700 dark:text-[#4cc38a] hover:underline">
                    Semua quiz
                </a>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div class="rounded-lg border vlx-border vlx-surface-raised p-2.5 text-center">
                    <div class="font-mono text-xs tabular-nums font-medium text-emerald-700 dark:text-[#4cc38a]">+50 XP</div>
                    <div class="text-[11px] vlx-text-muted mt-0.5">Quiz</div>
                </div>
                <div class="rounded-lg border vlx-border vlx-surface-raised p-2.5 text-center">
                    <div class="font-mono text-xs tabular-nums font-medium text-emerald-700 dark:text-[#4cc38a]">+100 XP</div>
                    <div class="text-[11px] vlx-text-muted mt-0.5">Lab</div>
                </div>
                <div class="rounded-lg border vlx-border vlx-surface-raised p-2.5 text-center">
                    <div class="font-mono text-xs tabular-nums font-medium text-emerald-700 dark:text-[#4cc38a]">+25 XP</div>
                    <div class="text-[11px] vlx-text-muted mt-0.5">Streak</div>
                </div>
            </div>

            <div class="space-y-3">
                @foreach ($dailyQuizzes as $quiz)
                    <x-velinux.quiz-challenge-card :quiz="$quiz" class="vlx-surface-page" />
                @endforeach
            </div>
        </div>
    </div>

    <!-- Cyber Lab Sandbox -->
    <section>
        <div class="flex items-center justify-between gap-4 mb-4">
            <h2 class="text-lg font-medium vlx-text-primary">Cyber Lab</h2>
            <a href="{{ route('labs.index') }}" class="shrink-0 text-xs font-medium text-emerald-700 dark:text-[#4cc38a] hover:underline">
                Lihat semua
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse ($featuredLabs as $lab)
                <x-velinux.cyber-lab-card
                    :lab="$lab"
                    :is-solved="in_array($lab->id, $solvedLabIds, true)"
                    :active-session="$activeLabSessions->get($lab->id)"
                />
            @empty
                <div class="col-span-full rounded-xl border vlx-border vlx-surface-card p-6 text-center text-sm vlx-text-muted">
                    Belum ada lab aktif.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Courses -->
    <section>
        <div class="flex items-center justify-between gap-4 mb-4">
            <h2 class="text-lg font-medium vlx-text-primary">Courses</h2>
            <a href="{{ route('courses.index') }}" class="shrink-0 text-xs font-medium text-emerald-700 dark:text-[#4cc38a] hover:underline">
                Lihat semua
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse ($courses as $course)
                <x-velinux.course-card
                    :course="$course"
                    :enrollment="$enrollmentsByCourse->get($course->id)"
                />
            @empty
                <div class="col-span-full rounded-xl border vlx-border vlx-surface-card p-6 text-center text-sm vlx-text-muted">
                    Belum ada kelas tersedia.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Leaderboard + Recent XP -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-velinux.leaderboard-table
                :leaders="$leaders"
                :current-user-id="$learner?->id"
            />
        </div>

        <div class="rounded-xl border vlx-border vlx-surface-card p-5">
            <h3 class="text-base font-medium vlx-text-primary mb-3">Riwayat XP</h3>

            <ul class="divide-y divide-black/6 dark:divide-white/8">
                @forelse ($recentPoints as $point)
                    <li class="flex items-center justify-between gap-3 py-2.5 first:pt-0 last:pb-0">
                        <div class="min-w-0">
                            <p class="text-xs font-medium vlx-text-primary truncate">{{ $point->description }}</p>
                            <p class="text-[11px] vlx-text-muted mt-0.5">{{ $point->awarded_at?->diffForHumans() }}</p>
                        </div>
                        <span class="shrink-0 font-mono text-xs tabular-nums font-medium text-emerald-700 dark:text-[#4cc38a]">
                            +{{ $point->points }} XP
                        </span>
                    </li>
                @empty
                    <li class="text-sm vlx-text-muted">Belum ada riwayat XP.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.app>
