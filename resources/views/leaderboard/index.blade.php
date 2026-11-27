<x-layouts.app title="Leaderboard & Achievements" :user="$learner">
    <div class="mb-6">
        <span class="font-mono text-xs uppercase tracking-wider text-primary-600 dark:text-primary-400">Gamification &amp; Skill Recognition</span>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Leaderboard, Badges &amp; XP Ledger</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Peringkat komunitas VELINUX, pencapaian badge kompetensi, dan riwayat perolehan XP.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2">
            <x-velinux.leaderboard-table
                :leaders="$leaders"
                :current-user-id="$learner?->id"
            />
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Riwayat XP Anda</h2>
            <ul class="space-y-2.5">
                @forelse ($pointHistory as $point)
                    <li class="flex items-center justify-between gap-2 rounded-lg border border-gray-100 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-950/60">
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $point->description }}</p>
                            <p class="text-[11px] font-mono text-gray-500 dark:text-gray-400">{{ $point->awarded_at?->format('d M Y H:i') }}</p>
                        </div>
                        <span class="shrink-0 font-mono text-xs font-bold text-primary-600 dark:text-primary-400">
                            +{{ $point->points }} XP
                        </span>
                    </li>
                @empty
                    <li class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat poin.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <section>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Achievements &amp; Badges</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($achievements as $achievement)
                @php
                    $unlocked = in_array($achievement->id, $earnedAchievementIds, true);
                @endphp
                <div class="rounded-xl border p-5 {{ $unlocked ? 'border-primary-500/40 bg-primary-500/10' : 'border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 opacity-75' }}">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="font-mono text-xs uppercase {{ $unlocked ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ $unlocked ? 'Unlocked' : 'Locked' }}
                        </span>
                        <span class="font-mono text-xs font-semibold text-primary-600 dark:text-primary-400">
                            +{{ $achievement->xp_bonus }} XP
                        </span>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $achievement->name }}</h3>
                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">{{ $achievement->description }}</p>
                </div>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada daftar achievement.
                </div>
            @endforelse
        </div>
    </section>
</x-layouts.app>
