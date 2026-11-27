@props([
    'leaders',
    'currentUserId' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border vlx-border vlx-surface-card']) }}>
    <div class="flex items-center justify-between border-b vlx-border px-5 py-3.5">
        <h3 class="text-base font-medium vlx-text-primary">Cyber Leaderboard</h3>
        <a href="{{ route('leaderboard.index') }}" class="text-xs font-medium text-emerald-700 dark:text-[#4cc38a] hover:underline">
            Lihat semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="vlx-surface-raised text-xs font-mono vlx-text-muted border-b vlx-border">
                <tr>
                    <th class="px-5 py-2.5 font-medium">#</th>
                    <th class="px-5 py-2.5 font-medium">Learner</th>
                    <th class="px-5 py-2.5 font-medium">Tier</th>
                    <th class="px-5 py-2.5 font-medium">Streak</th>
                    <th class="px-5 py-2.5 text-right font-medium">XP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/6 dark:divide-white/8">
                @forelse ($leaders as $index => $leader)
                    @php
                        $isCurrent = $currentUserId !== null && $leader->id === $currentUserId;
                    @endphp
                    <tr class="transition-colors duration-150 {{ $isCurrent ? 'vlx-surface-raised' : 'hover:bg-black/[0.02] dark:hover:bg-white/[0.02]' }}">
                        <td class="px-5 py-3 font-mono text-xs tabular-nums vlx-text-muted">
                            #{{ $index + 1 }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="font-medium vlx-text-primary flex items-center gap-2">
                                <span>{{ $leader->name }}</span>
                                @if ($isCurrent)
                                    <span class="rounded px-1.5 py-0.5 text-[11px] font-mono text-emerald-700 dark:text-[#4cc38a] bg-emerald-500/8 border border-emerald-600/15 dark:border-[#4cc38a]/20">You</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3 font-mono text-xs vlx-text-secondary">
                            {{ $leader->cyberRank() }}
                        </td>
                        <td class="px-5 py-3 font-mono text-xs tabular-nums vlx-text-secondary">
                            {{ $leader->current_streak }} hari
                        </td>
                        <td class="px-5 py-3 text-right font-mono tabular-nums font-medium text-emerald-700 dark:text-[#4cc38a]">
                            {{ number_format($leader->xp_points) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-6 text-center text-sm vlx-text-muted">
                            Belum ada data peringkat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
