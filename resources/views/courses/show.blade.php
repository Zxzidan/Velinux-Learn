<x-layouts.app :title="$course->title" :user="$learner">
    <div class="pb-6 border-b vlx-border flex flex-wrap items-end justify-between gap-4">
        <div>
            <a href="{{ route('courses.index') }}" class="text-xs font-mono vlx-text-muted hover:vlx-text-primary">
                &larr; Course catalog
            </a>
            <h1 class="mt-2 text-2xl sm:text-[30px] font-medium tracking-[-0.02em] vlx-text-primary">{{ $course->title }}</h1>
            <p class="text-sm vlx-text-secondary mt-1 max-w-[65ch]">{{ $course->description }}</p>
        </div>

        <div class="rounded-xl border vlx-border vlx-surface-card px-4 py-2.5 text-right">
            <div class="text-xs vlx-text-muted">Progress kelas</div>
            <div class="text-xl font-medium font-mono tabular-nums text-emerald-700 dark:text-[#4cc38a]">
                {{ $enrollment?->progress_percentage ?? 0 }}%
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <aside class="space-y-4">
            <div class="rounded-xl border vlx-border vlx-surface-card p-5">
                <h2 class="text-sm font-medium vlx-text-primary mb-4">
                    Daftar modul ({{ $totalLessonsCount }} materi)
                </h2>
                <div class="space-y-5">
                    @foreach ($course->modules as $module)
                        <div>
                            <div class="text-xs font-mono vlx-text-muted mb-2">
                                {{ $module->sort_order }}. {{ $module->title }}
                            </div>
                            <ul class="space-y-1">
                                @foreach ($module->lessons as $lesson)
                                    @php
                                        $isActive = $currentLesson && $currentLesson->id === $lesson->id;
                                        $isDone = in_array($lesson->id, $completedLessonIds, true);
                                    @endphp
                                    <li>
                                        <a
                                            href="{{ route('courses.show', ['course' => $course, 'lesson' => $lesson->slug]) }}"
                                            class="flex items-center justify-between gap-2 rounded-lg px-3 h-9 text-xs font-medium transition-colors duration-150 {{ $isActive ? 'vlx-surface-raised vlx-text-primary border vlx-border' : 'vlx-text-secondary hover:bg-black/[0.03] dark:hover:bg-white/[0.04]' }}"
                                        >
                                            <span class="truncate">{{ $lesson->title }}</span>
                                            @if ($isDone)
                                                <span class="shrink-0 font-mono text-[11px] text-emerald-700 dark:text-[#4cc38a]">Done</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                @if ($course->quizzes->isNotEmpty())
                    <div class="mt-5 pt-4 border-t vlx-border">
                        <a
                            href="{{ route('quizzes.index') }}"
                            class="flex w-full items-center justify-center h-9 rounded-lg bg-gray-900 text-white hover:bg-gray-800 dark:bg-[#eef0f5] dark:text-[#0b0d14] dark:hover:bg-white px-4 text-xs font-medium active:scale-[0.98] transition-[background-color,transform] duration-150"
                        >
                            Mulai assessment modul
                        </a>
                    </div>
                @endif
            </div>
        </aside>

        <section class="lg:col-span-2 space-y-6">
            @if ($currentLesson)
                <article class="rounded-xl border vlx-border vlx-surface-card p-6">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                        <span class="font-mono text-xs tabular-nums vlx-text-muted">
                            {{ ucfirst($currentLesson->content_type) }} &middot; +{{ $currentLesson->xp_reward }} XP
                        </span>

                        <form action="{{ route('courses.lessons.complete', [$course, $currentLesson]) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="rounded-lg border vlx-border vlx-surface-raised px-3 h-8 text-xs font-medium vlx-text-primary hover:vlx-border-strong active:scale-[0.98] transition-[border-color,transform] duration-150 cursor-pointer"
                            >
                                {{ in_array($currentLesson->id, $completedLessonIds, true) ? 'Sudah selesai' : 'Tandai selesai' }}
                            </button>
                        </form>
                    </div>

                    <h2 class="text-xl font-medium vlx-text-primary">{{ $currentLesson->title }}</h2>

                    <div class="mt-4 space-y-3 text-sm leading-relaxed vlx-text-secondary whitespace-pre-line max-w-[65ch]">
                        {{ $currentLesson->content }}
                    </div>

                    @if ($currentLesson->code_snippet)
                        <div class="mt-6">
                            <x-velinux.terminal-preview
                                :title="$currentLesson->slug.'.'.($currentLesson->code_language ?? 'txt')"
                                :code="$currentLesson->code_snippet"
                                :language="$currentLesson->code_language ?? 'code'"
                            />
                        </div>
                    @endif

                    <div class="mt-6 pt-4 border-t vlx-border flex items-center justify-between gap-3">
                        @if ($previousLesson)
                            <a
                                href="{{ route('courses.show', ['course' => $course, 'lesson' => $previousLesson->slug]) }}"
                                class="rounded-lg border vlx-border vlx-surface-raised px-3.5 h-9 inline-flex items-center text-xs font-medium vlx-text-primary hover:vlx-border-strong"
                            >
                                &larr; Previous
                            </a>
                        @else
                            <span></span>
                        @endif

                        @if ($nextLesson)
                            <a
                                href="{{ route('courses.show', ['course' => $course, 'lesson' => $nextLesson->slug]) }}"
                                class="rounded-lg bg-gray-900 text-white hover:bg-gray-800 dark:bg-[#eef0f5] dark:text-[#0b0d14] dark:hover:bg-white px-3.5 h-9 inline-flex items-center text-xs font-medium"
                            >
                                Next lesson &rarr;
                            </a>
                        @endif
                    </div>
                </article>
            @else
                <div class="rounded-xl border vlx-border vlx-surface-card p-8 text-center text-sm vlx-text-muted">
                    Belum ada lesson pada course ini.
                </div>
            @endif
        </section>
    </div>
</x-layouts.app>
