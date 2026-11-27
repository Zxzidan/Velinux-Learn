<x-layouts.app title="Cyber Quiz & AI Assessment" :user="$learner">
    <div class="pb-5 border-b vlx-border">
        <span class="text-xs font-mono vlx-text-muted">Quiz &amp; AI</span>
        <h1 class="text-2xl sm:text-[28px] font-medium tracking-[-0.02em] vlx-text-primary mt-1">Cyber Quiz &amp; AI Assessment</h1>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-rose-500/25 bg-rose-500/8 p-4 text-sm text-rose-700 dark:text-rose-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse ($quizzes as $quiz)
            <section id="quiz-{{ $quiz->slug }}" class="rounded-xl border vlx-border vlx-surface-card p-6">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <span class="text-xs font-mono vlx-text-muted">
                        {{ $quiz->quiz_type->label() }}
                    </span>
                    <span class="font-mono text-xs tabular-nums font-medium text-emerald-700 dark:text-[#4cc38a]">
                        +{{ $quiz->xp_reward }} XP &middot; Passing {{ $quiz->passing_score }}%
                    </span>
                </div>

                <h2 class="text-lg font-medium vlx-text-primary">{{ $quiz->title }}</h2>
                @if ($quiz->description)
                    <p class="mt-1.5 text-sm vlx-text-secondary">{{ $quiz->description }}</p>
                @endif

                <form action="{{ route('quizzes.submit', $quiz) }}" method="POST" class="mt-5 space-y-5">
                    @csrf
                    @foreach ($quiz->questions as $qIndex => $question)
                        <div class="rounded-lg border vlx-border vlx-surface-raised p-4">
                            <div class="flex items-center justify-between text-xs font-mono tabular-nums vlx-text-muted mb-2">
                                <span>Soal {{ $qIndex + 1 }} &middot; {{ $question->question_type->label() }}</span>
                                <span>{{ $question->points }} pts</span>
                            </div>

                            <p class="text-sm font-medium vlx-text-primary">{{ $question->prompt }}</p>

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
                                        <label class="flex items-start gap-2.5 rounded-lg border vlx-border vlx-surface-card p-3 text-xs vlx-text-secondary hover:vlx-border-strong cursor-pointer transition-colors duration-150">
                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}]"
                                                value="{{ $key }}"
                                                class="mt-0.5 accent-emerald-600"
                                                required
                                            />
                                            <span><strong class="font-mono font-medium vlx-text-primary">{{ $key }}.</strong> {{ $optionText }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-3">
                                    <textarea
                                        name="answers[{{ $question->id }}]"
                                        rows="3"
                                        required
                                        placeholder="Tuliskan penjelasan teknis Anda..."
                                        class="w-full rounded-lg border vlx-border vlx-surface-card p-3 text-xs vlx-text-primary focus:outline-none focus:border-emerald-600 dark:focus:border-[#4cc38a]"
                                    ></textarea>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <button
                        type="submit"
                        class="w-full h-10 rounded-lg bg-gray-900 text-white hover:bg-gray-800 dark:bg-[#eef0f5] dark:text-[#0b0d14] dark:hover:bg-white px-4 text-sm font-medium active:scale-[0.98] transition-[background-color,transform] duration-150 cursor-pointer"
                    >
                        Kirim jawaban &amp; evaluasi AI
                    </button>
                </form>
            </section>
        @empty
            <div class="col-span-full rounded-xl border vlx-border vlx-surface-card p-8 text-center text-sm vlx-text-muted">
                Belum ada quiz tersedia.
            </div>
        @endforelse
    </div>

    <section id="ai-feedback" class="space-y-4">
        <div>
            <h2 class="text-lg font-medium vlx-text-primary">Riwayat AI assessment &amp; rekomendasi</h2>
            <p class="text-xs vlx-text-muted mt-0.5">Hasil evaluasi otomatis berbasis AI dan tinjauan mentor</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse ($assessments as $assessment)
                <x-velinux.ai-feedback-card :assessment="$assessment" />
            @empty
                <div class="col-span-full rounded-xl border vlx-border vlx-surface-card p-8 text-center text-sm vlx-text-muted">
                    Belum ada riwayat evaluasi AI.
                </div>
            @endforelse
        </div>
    </section>
</x-layouts.app>
