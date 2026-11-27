<x-layouts.app title="Leaderboard & Achievements" :user="$learner">
    <div class="pb-5 border-b vlx-border">
        <span class="text-xs font-mono vlx-text-muted">Gamification</span>
        <h1 class="text-2xl sm:text-[28px] font-medium tracking-[-0.02em] vlx-text-primary mt-1">Leaderboard &amp; Achievements</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-velinux.leaderboard-table
                :leaders="$leaders"
                :current-user-id="$learner?->id"
            />
        </div>

        <div class="rounded-xl border vlx-border vlx-surface-card p-6">
            <h2 class="text-base font-medium vlx-text-primary mb-4">Riwayat XP Anda</h2>
            <ul class="divide-y divide-black/6 dark:divide-white/8">
                @forelse ($pointHistory as $point)
                    <li class="flex items-center justify-between gap-2 py-3 first:pt-0 last:pb-0">
                        <div class="min-w-0">
                            <p class="text-xs font-medium vlx-text-primary truncate">{{ $point->description }}</p>
                            <p class="text-[11px] font-mono tabular-nums vlx-text-muted mt-0.5">{{ $point->awarded_at?->format('d M Y H:i') }}</p>
                        </div>
                        <span class="shrink-0 font-mono text-xs tabular-nums font-medium text-emerald-700 dark:text-[#4cc38a]">
                            +{{ $point->points }} XP
                        </span>
                    </li>
                @empty
                    <li class="text-sm vlx-text-muted">Belum ada riwayat poin.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <section>
        <h2 class="text-lg font-medium vlx-text-primary mb-4">Achievements &amp; Badges</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($achievements as $achievement)
                @php
                    $unlocked = in_array($achievement->id, $earnedAchievementIds, true);
                @endphp
                <div class="rounded-xl border vlx-border p-5 {{ $unlocked ? 'vlx-surface-card' : 'vlx-surface-raised opacity-70' }}">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="font-mono text-xs {{ $unlocked ? 'text-emerald-700 dark:text-[#4cc38a]' : 'vlx-text-muted' }}">
                            {{ $unlocked ? 'Unlocked' : 'Locked' }}
                        </span>
                        <span class="font-mono text-xs tabular-nums vlx-text-secondary">
                            +{{ $achievement->xp_bonus }} XP
                        </span>
                    </div>
                    <h3 class="text-base font-medium vlx-text-primary">{{ $achievement->name }}</h3>
                    <p class="mt-1 text-xs vlx-text-secondary leading-relaxed">{{ $achievement->description }}</p>
                </div>
            @empty
                <div class="col-span-full rounded-xl border vlx-border vlx-surface-card p-8 text-center text-sm vlx-text-muted">
                    Belum ada daftar achievement.
                </div>
            @endforelse
        </div>
    </section>
</x-layouts.app>
