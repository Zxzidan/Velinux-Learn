<x-layouts.app title="Cyber Lab & CTF Arena" :user="$learner">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <span class="font-mono text-xs uppercase tracking-wider text-primary-600 dark:text-primary-400">Isolated Practical Environment</span>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Cyber Lab &amp; CTF Sandbox</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Praktik eksploitasi dan hardening aman pada lingkungan container terisolasi.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ route('labs.index') }}"
                class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold {{ $selectedCategory === '' ? 'bg-primary-500 text-gray-950' : 'border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300' }}"
            >
                All Labs
            </a>
            @foreach ($categories as $category)
                <a
                    href="{{ route('labs.index', ['category' => $category]) }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold {{ $selectedCategory === $category ? 'bg-primary-500 text-gray-950' : 'border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300' }}"
                >
                    {{ $category }}
                </a>
            @endforeach
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-600 dark:text-rose-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @forelse ($labs as $lab)
            @php
                $session = $activeSessions->get($lab->id);
            @endphp
            <div class="space-y-2">
                <x-velinux.cyber-lab-card
                    :lab="$lab"
                    :is-solved="in_array($lab->id, $solvedLabIds, true)"
                    :active-session="$session"
                    :show-submission-form="true"
                />

                <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs dark:border-gray-800 dark:bg-gray-900">
                    @if ($session)
                        <span class="font-mono text-amber-600 dark:text-amber-400">
                            Container: {{ $session->container_id }} &middot; Exp {{ $session->expires_at->diffForHumans() }}
                        </span>
                    @else
                        <span class="font-mono text-gray-500 dark:text-gray-400">
                            Durasi sesi: {{ $lab->time_limit_minutes }} menit (terisolasi)
                        </span>
                    @endif

                    <form action="{{ route('labs.start', $lab) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-lg border border-primary-500/40 bg-primary-500/10 px-3 py-1 font-mono font-semibold text-primary-700 dark:text-primary-300 hover:bg-primary-500/20 cursor-pointer"
                        >
                            {{ $session ? 'Session Aktif' : 'Launch Sandbox' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                Belum ada lab challenge aktif.
            </div>
        @endforelse
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section id="submissions" class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Riwayat Flag Submission</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 font-mono uppercase text-gray-500 dark:bg-gray-950/60 dark:text-gray-400">
                        <tr>
                            <th class="px-3 py-2.5">Lab</th>
                            <th class="px-3 py-2.5">Submitted Flag</th>
                            <th class="px-3 py-2.5">Status</th>
                            <th class="px-3 py-2.5 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($recentSubmissions as $submission)
                            <tr>
                                <td class="px-3 py-2.5 font-medium text-gray-900 dark:text-white">
                                    {{ $submission->labChallenge?->title ?? 'Lab' }}
                                </td>
                                <td class="px-3 py-2.5 font-mono text-gray-600 dark:text-gray-300">
                                    {{ $submission->submitted_flag }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="rounded px-2 py-0.5 font-mono font-semibold {{ $submission->is_correct ? 'bg-primary-500/15 text-primary-600 dark:text-primary-400' : 'bg-rose-500/15 text-rose-600 dark:text-rose-400' }}">
                                        {{ $submission->is_correct ? 'VERIFIED' : 'INVALID' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-right text-gray-500 dark:text-gray-400">
                                    {{ $submission->created_at?->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada riwayat submit flag.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section id="lab-rules" class="rounded-xl border border-amber-500/30 bg-amber-500/5 p-5">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-2">Etika &amp; Batasan Cyber Lab</h2>
            <ul class="space-y-2 text-xs text-gray-700 dark:text-gray-300 leading-relaxed">
                <li>1. Seluruh praktik dijalankan di dalam container/sandbox terisolasi dengan batas resource.</li>
                <li>2. Dilarang mengarahkan tool enumerasi atau eksploitasi ke target publik di luar lingkungan simulasi VELINUX.</li>
                <li>3. Setiap aktivitas sesi dan pengumpulan flag tercatat pada audit log platform.</li>
            </ul>
        </section>
    </div>
</x-layouts.app>
