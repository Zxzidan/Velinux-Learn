<x-layouts.app title="Overview" :user="$learner">
    <x-velinux.hero-banner
        :user="$learner"
        :published-course-count="$publishedCourseCount"
        :active-lab-count="$activeLabCount"
    />

    <!-- Top 4 KPI Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-velinux.stat-card
            label="Learning Progress"
            :value="$averageProgress.'%'"
            subtitle="Rata-rata kelas"
            badge="LMS Active"
            accent="emerald"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </x-slot:icon>
        </x-velinux.stat-card>

        <x-velinux.stat-card
            label="Total XP"
            :value="number_format($learner?->xp_points ?? 0)"
            :subtitle="$learner?->cyberRank() ?? 'Cadet'"
            badge="+XP Rewards"
            accent="sky"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                </svg>
            </x-slot:icon>
        </x-velinux.stat-card>

        <x-velinux.stat-card
            label="Cyber Labs Solved"
            :value="count($solvedLabIds).' / '.$activeLabCount"
            subtitle="Sandbox terisolasi"
            badge="Practical"
            accent="purple"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </x-slot:icon>
        </x-velinux.stat-card>

        <x-velinux.stat-card
            label="Daily Streak"
            :value="($learner?->current_streak ?? 0).' Hari'"
            :subtitle="$achievementsCount.' Badge diraih'"
            badge="+25 XP/hari"
            accent="amber"
        >
            <x-slot:icon>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03z" clip-rule="evenodd"></path>
                </svg>
            </x-slot:icon>
        </x-velinux.stat-card>
    </div>

    <!-- Continue Learning & Recommended Courses -->
    <section>
        <div class="flex items-center justify-between gap-4 mb-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Continue Learning &amp; Course Catalog</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Jalur pembelajaran cybersecurity terstruktur dari Beginner hingga Advanced</p>
            </div>
            <a href="{{ route('courses.index') }}" class="shrink-0 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                Semua Kelas &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse ($courses as $course)
                <x-velinux.course-card
                    :course="$course"
                    :enrollment="$enrollmentsByCourse->get($course->id)"
                />
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada modul pembelajaran tersedia.
                </div>
            @endforelse
        </div>
    </section>

    <!-- AI Assessment + Daily Challenges & XP Reward Matrix -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            @if ($latestAssessment)
                <x-velinux.ai-feedback-card :assessment="$latestAssessment" />
            @else
                <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Selesaikan quiz pertama Anda untuk melihat evaluasi AI &amp; rekomendasi materi.
                </div>
            @endif
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Reward System &amp; Daily Challenges</h3>
                <span class="font-mono text-xs text-primary-600 dark:text-primary-400">Gamification</span>
            </div>
            <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="rounded-lg border border-primary-500/25 bg-primary-500/10 p-3.5 text-center">
                    <div class="font-mono text-sm font-bold text-primary-600 dark:text-primary-400">+50 XP</div>
                    <div class="text-[11px] text-gray-600 dark:text-gray-300 mt-1">Complete Quiz</div>
                </div>
                <div class="rounded-lg border border-sky-500/25 bg-sky-500/10 p-3.5 text-center">
                    <div class="font-mono text-sm font-bold text-sky-600 dark:text-sky-400">+100 XP</div>
                    <div class="text-[11px] text-gray-600 dark:text-gray-300 mt-1">Finish Challenge</div>
                </div>
                <div class="rounded-lg border border-amber-500/25 bg-amber-500/10 p-3.5 text-center">
                    <div class="font-mono text-sm font-bold text-amber-600 dark:text-amber-400">+25 XP</div>
                    <div class="text-[11px] text-gray-600 dark:text-gray-300 mt-1">Daily Streak</div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach ($dailyQuizzes as $quiz)
                    <x-velinux.quiz-challenge-card :quiz="$quiz" />
                @endforeach
            </div>
        </div>
    </div>

    <!-- Cyber Lab Preview -->
    <section>
        <div class="flex items-center justify-between gap-4 mb-5">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Cyber Lab &amp; Isolated Sandbox</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Praktik keamanan siber aman menggunakan container terisolasi</p>
            </div>
            <a href="{{ route('labs.index') }}" class="shrink-0 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                Masuk Cyber Lab &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            @forelse ($featuredLabs as $lab)
                <x-velinux.cyber-lab-card
                    :lab="$lab"
                    :is-solved="in_array($lab->id, $solvedLabIds, true)"
                    :active-session="$activeLabSessions->get($lab->id)"
                />
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada lab challenge aktif.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Leaderboard + Recent XP History -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-velinux.leaderboard-table
                :leaders="$leaders"
                :current-user-id="$learner?->id"
            />
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 shadow-xs">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Riwayat XP Terbaru</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Pencapaian poin aktivitas pembelajaran</p>

            <ul class="space-y-3">
                @forelse ($recentPoints as $point)
                    <li class="flex items-center justify-between gap-3 rounded-lg border border-gray-100 bg-gray-50 p-3.5 dark:border-gray-800 dark:bg-gray-950/60">
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $point->description }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">{{ $point->awarded_at?->diffForHumans() }}</p>
                        </div>
                        <span class="shrink-0 font-mono text-xs font-bold text-primary-600 dark:text-primary-400">
                            +{{ $point->points }} XP
                        </span>
                    </li>
                @empty
                    <li class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat XP.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.app>
