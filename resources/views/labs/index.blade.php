<x-layouts.app title="Cyber Lab & CTF Arena" :user="$learner">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 pb-5 border-b vlx-border">
        <div>
            <span class="text-xs font-mono vlx-text-muted">Sandbox</span>
            <h1 class="text-2xl sm:text-[28px] font-medium tracking-[-0.02em] vlx-text-primary mt-1">Cyber Lab &amp; CTF Sandbox</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ route('labs.index') }}"
                class="rounded-lg px-3 h-8 inline-flex items-center text-xs font-medium {{ $selectedCategory === '' ? 'bg-gray-900 text-white dark:bg-[#eef0f5] dark:text-[#0b0d14]' : 'border vlx-border vlx-surface-raised vlx-text-secondary hover:vlx-text-primary' }}"
            >
                All labs
            </a>
            @foreach ($categories as $category)
                <a
                    href="{{ route('labs.index', ['category' => $category]) }}"
                    class="rounded-lg px-3 h-8 inline-flex items-center text-xs font-medium {{ $selectedCategory === $category ? 'bg-gray-900 text-white dark:bg-[#eef0f5] dark:text-[#0b0d14]' : 'border vlx-border vlx-surface-raised vlx-text-secondary hover:vlx-text-primary' }}"
                >
                    {{ $category }}
                </a>
            @endforeach
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-rose-500/25 bg-rose-500/8 p-4 text-sm text-rose-700 dark:text-rose-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse ($labs as $lab)
            @php
                $session = $activeSessions->get($lab->id);
            @endphp
            <div class="space-y-2.5">
                <x-velinux.cyber-lab-card
                    :lab="$lab"
                    :is-solved="in_array($lab->id, $solvedLabIds, true)"
                    :active-session="$session"
                    :show-submission-form="true"
                />

                <div class="flex items-center justify-between rounded-xl border vlx-border vlx-surface-card px-4 py-2.5 text-xs">
                    @if ($session)
                        <span class="font-mono tabular-nums text-amber-700 dark:text-amber-300">
                            Container: {{ $session->container_id }} &middot; Exp {{ $session->expires_at->diffForHumans() }}
                        </span>
                    @else
                        <span class="font-mono tabular-nums vlx-text-muted">
                            Durasi sesi: {{ $lab->time_limit_minutes }} menit (terisolasi)
                        </span>
                    @endif

                    <form action="{{ route('labs.start', $lab) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-lg border vlx-border vlx-surface-raised px-3 h-8 font-mono text-xs font-medium vlx-text-primary hover:vlx-border-strong active:scale-[0.98] transition-[border-color,transform] duration-150 cursor-pointer"
                        >
                            {{ $session ? 'Session aktif' : 'Launch sandbox' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border vlx-border vlx-surface-card p-8 text-center text-sm vlx-text-muted">
                Belum ada lab challenge aktif.
            </div>
        @endforelse
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section id="submissions" class="lg:col-span-2 rounded-xl border vlx-border vlx-surface-card p-6">
            <h2 class="text-base font-medium vlx-text-primary mb-4">Riwayat flag submission</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="vlx-surface-raised font-mono vlx-text-muted border-b vlx-border">
                        <tr>
                            <th class="px-3 py-2.5 font-medium">Lab</th>
                            <th class="px-3 py-2.5 font-medium">Submitted flag</th>
                            <th class="px-3 py-2.5 font-medium">Status</th>
                            <th class="px-3 py-2.5 text-right font-medium">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/6 dark:divide-white/8">
                        @forelse ($recentSubmissions as $submission)
                            <tr>
                                <td class="px-3 py-3 font-medium vlx-text-primary">
                                    {{ $submission->labChallenge?->title ?? 'Lab' }}
                                </td>
                                <td class="px-3 py-3 font-mono vlx-text-secondary">
                                    {{ $submission->submitted_flag }}
                                </td>
                                <td class="px-3 py-3">
                                    <span class="rounded px-2 py-0.5 font-mono {{ $submission->is_correct ? 'text-emerald-700 dark:text-[#4cc38a] bg-emerald-500/8' : 'text-rose-700 dark:text-rose-300 bg-rose-500/8' }}">
                                        {{ $submission->is_correct ? 'Verified' : 'Invalid' }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-right vlx-text-muted">
                                    {{ $submission->created_at?->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center vlx-text-muted">
                                    Belum ada riwayat submit flag.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section id="lab-rules" class="rounded-xl border vlx-border vlx-surface-card p-5 h-fit">
            <h2 class="text-base font-medium vlx-text-primary mb-3">Aturan Lab</h2>
            <ul class="space-y-2 text-xs vlx-text-secondary">
                <li>1. Container terisolasi &amp; berbatas waktu.</li>
                <li>2. Hanya uji target simulasi VELINUX.</li>
                <li>3. Aktivitas sesi tercatat pada audit log.</li>
            </ul>
        </section>
    </div>
</x-layouts.app>
