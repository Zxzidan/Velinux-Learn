<x-layouts.app title="Cyber Quiz & AI Assessment" :user="$learner">
    <div class="mb-6">
        <span class="font-mono text-xs uppercase tracking-wider text-primary-600 dark:text-primary-400">Interactive Quiz &amp; AI Evaluation</span>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Cyber Quiz &amp; AI Assessment</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Uji pemahaman melalui tantangan interaktif dan dapatkan koreksi otomatis beserta feedback AI.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-600 dark:text-rose-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @forelse ($quizzes as $quiz)
            <section id="quiz-{{ $quiz->slug }}" class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 shadow-xs">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <span class="rounded-md px-2.5 py-1 text-[11px] font-mono font-semibold {{ $quiz->is_daily ? 'bg-amber-500/15 text-amber-600 dark:text-amber-300 border border-amber-500/30' : 'bg-sky-500/15 text-sky-600 dark:text-sky-300 border border-sky-500/30' }}">
                        {{ $quiz->quiz_type->label() }}
                    </span>
                    <span class="font-mono text-xs text-primary-600 dark:text-primary-400 font-semibold">
                        +{{ $quiz->xp_reward }} XP &middot; Passing {{ $quiz->passing_score }}%
                    </span>
                </div>

                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h2>
                @if ($quiz->description)
                    <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400">{{ $quiz->description }}</p>
                @endif

                <form action="{{ route('quizzes.submit', $quiz) }}" method="POST" class="mt-5 space-y-5">
                    @csrf
                    @foreach ($quiz->questions as $qIndex => $question)
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950/60">
                            <div class="flex items-center justify-between text-xs font-mono text-gray-500 dark:text-gray-400 mb-2">
                                <span>Soal {{ $qIndex + 1 }} &middot; {{ $question->question_type->label() }}</span>
                                <span>{{ $question->points }} pts</span>
                            </div>

                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $question->prompt }}</p>

                            @if ($question->code_snippet)
                                <div class="mt-3">
                                    <x-velinux.terminal-preview
                                        title="vuln-check.php"
                                        :code="$question->code_snippet"
                                        language="php"
                                    />
                                </div>
                            @endif

                            @if (is_array($question->options) && count($question->options) > 0)
                                <div class="mt-3 space-y-2">
                                    @foreach ($question->options as $key => $optionText)
                                        <label class="flex items-start gap-2.5 rounded-lg border border-gray-200 bg-white p-2.5 text-xs text-gray-800 hover:border-primary-500/40 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 cursor-pointer">
                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}]"
                                                value="{{ $key }}"
                                                class="mt-0.5 text-primary-500 focus:ring-primary-500"
                                                required
                                            />
                                            <span><strong class="font-mono text-primary-600 dark:text-primary-400">{{ $key }}.</strong> {{ $optionText }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-3">
                                    <textarea
                                        name="answers[{{ $question->id }}]"
                                        rows="3"
                                        required
                                        placeholder="Tuliskan penjelasan teknis Anda (misal: penggunaan prepared statements & parameter binding)..."
                                        class="w-full rounded-lg border border-gray-300 bg-white p-3 text-xs text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                    ></textarea>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-primary-500 px-4 py-2.5 text-sm font-semibold text-gray-950 hover:bg-primary-400 transition cursor-pointer"
                    >
                        Kirim Jawaban &amp; Evaluasi AI
                    </button>
                </form>
            </section>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                Belum ada quiz tersedia.
            </div>
        @endforelse
    </div>

    <section id="ai-feedback" class="space-y-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat AI Assessment &amp; Rekomendasi</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">Hasil evaluasi otomatis berbasis AI dan tinjauan mentor</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @forelse ($assessments as $assessment)
                <x-velinux.ai-feedback-card :assessment="$assessment" />
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada riwayat evaluasi AI.
                </div>
            @endforelse
        </div>
    </section>
</x-layouts.app>
