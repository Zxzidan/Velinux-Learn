@props([
    'leaders',
    'currentUserId' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 shadow-xs']) }}>
    <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-800">
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Cyber Leaderboard</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Peringkat komunitas VELINUX berdasarkan XP &amp; konsistensi streak</p>
        </div>
        <a href="{{ route('leaderboard.index') }}" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs font-mono uppercase text-gray-500 dark:bg-gray-950/60 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Learner</th>
                    <th class="px-4 py-3">Rank</th>
                    <th class="px-4 py-3">Streak</th>
                    <th class="px-4 py-3 text-right">XP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($leaders as $index => $leader)
                    @php
                        $isCurrent = $currentUserId !== null && $leader->id === $currentUserId;
                    @endphp
                    <tr class="{{ $isCurrent ? 'bg-primary-500/10' : 'hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                        <td class="px-4 py-3 font-mono font-bold text-gray-700 dark:text-gray-300">
                            #{{ $index + 1 }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $leader->name }}
                                @if ($isCurrent)
                                    <span class="ml-1.5 rounded bg-primary-500/20 px-1.5 py-0.5 text-[10px] font-mono text-primary-600 dark:text-primary-300">YOU</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $leader->cohort ?? 'VELINUX Member' }}</div>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-300">
                            {{ $leader->cyberRank() }}
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-amber-600 dark:text-amber-400">
                            {{ $leader->current_streak }} hari
                        </td>
                        <td class="px-4 py-3 text-right font-mono font-bold text-primary-600 dark:text-primary-400">
                            {{ number_format($leader->xp_points) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            Belum ada data peringkat peserta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
