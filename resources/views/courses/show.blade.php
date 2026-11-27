<x-layouts.app :title="$course->title" :user="$learner">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('courses.index') }}" class="text-xs font-mono text-primary-600 dark:text-primary-400 hover:underline">
                &larr; Kembali ke Course Catalog
            </a>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $course->title }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $course->description }}</p>
        </div>

        <div class="rounded-xl border border-primary-500/30 bg-primary-500/10 px-4 py-2.5 text-right">
            <div class="text-xs font-mono text-gray-500 dark:text-gray-400">Progress Kelas</div>
            <div class="text-xl font-bold font-mono text-primary-600 dark:text-primary-400">
                {{ $enrollment?->progress_percentage ?? 0 }}%
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daftar Materi & Submateri -->
        <aside class="space-y-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                    Daftar Modul ({{ $totalLessonsCount }} Materi)
                </h2>
                <div class="space-y-4">
                    @foreach ($course->modules as $module)
                        <div>
                            <div class="text-xs font-mono uppercase tracking-wider text-primary-600 dark:text-primary-400 mb-2">
                                {{ $module->sort_order }}. {{ $module->title }}
                            </div>
                            <ul class="space-y-1.5">
                                @foreach ($module->lessons as $lesson)
                                    @php
                                        $isActive = $currentLesson && $currentLesson->id === $lesson->id;
                                        $isDone = in_array($lesson->id, $completedLessonIds, true);
                                    @endphp
                                    <li>
                                        <a
                                            href="{{ route('courses.show', ['course' => $course, 'lesson' => $lesson->slug]) }}"
                                            class="flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-xs font-medium transition {{ $isActive ? 'bg-primary-500/15 text-primary-700 dark:text-primary-300 border border-primary-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                                        >
                                            <span class="truncate">{{ $lesson->title }}</span>
                                            @if ($isDone)
                                                <span class="shrink-0 font-mono text-[10px] text-primary-500">DONE</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                @if ($course->quizzes->isNotEmpty())
                    <div class="mt-5 pt-4 border-t border-gray-200 dark:border-gray-800">
                        <a
                            href="{{ route('quizzes.index') }}"
                            class="flex w-full items-center justify-center rounded-xl bg-primary-500 px-4 py-2.5 text-xs font-semibold text-gray-950 hover:bg-primary-400 transition"
                        >
                            Mulai Assessment Modul &rarr;
                        </a>
                    </div>
                @endif
            </div>
        </aside>

        <!-- Konten Pembelajaran -->
        <section class="lg:col-span-2 space-y-6">
            @if ($currentLesson)
                <article class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                        <span class="rounded-md bg-primary-500/15 px-2.5 py-1 text-xs font-mono font-semibold text-primary-700 dark:text-primary-300">
                            {{ strtoupper($currentLesson->content_type) }} &middot; +{{ $currentLesson->xp_reward }} XP
                        </span>

                        <form action="{{ route('courses.lessons.complete', [$course, $currentLesson]) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="rounded-lg border border-primary-500/40 bg-primary-500/10 px-3 py-1.5 text-xs font-semibold text-primary-700 dark:text-primary-300 hover:bg-primary-500/20 transition cursor-pointer"
                            >
                                {{ in_array($currentLesson->id, $completedLessonIds, true) ? 'Sudah Selesai' : 'Tandai Selesai' }}
                            </button>
                        </form>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $currentLesson->title }}</h2>

                    <div class="mt-4 space-y-3 text-sm leading-relaxed text-gray-700 dark:text-gray-300 whitespace-pre-line">
                        {{ $currentLesson->content }}
                    </div>

                    @if ($currentLesson->code_snippet)
                        <div class="mt-5">
                            <x-velinux.terminal-preview
                                :title="$currentLesson->slug.'.'.($currentLesson->code_language ?? 'txt')"
                                :code="$currentLesson->code_snippet"
                                :language="$currentLesson->code_language ?? 'code'"
                            />
                        </div>
                    @endif

                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between gap-3">
                        @if ($previousLesson)
                            <a
                                href="{{ route('courses.show', ['course' => $course, 'lesson' => $previousLesson->slug]) }}"
                                class="rounded-lg border border-gray-300 dark:border-gray-700 px-3.5 py-2 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:border-primary-500/40"
                            >
                                &larr; Previous
                            </a>
                        @else
                            <span></span>
                        @endif

                        @if ($nextLesson)
                            <a
                                href="{{ route('courses.show', ['course' => $course, 'lesson' => $nextLesson->slug]) }}"
                                class="rounded-lg bg-primary-500 px-3.5 py-2 text-xs font-semibold text-gray-950 hover:bg-primary-400"
                            >
                                Next Lesson &rarr;
                            </a>
                        @endif
                    </div>
                </article>
            @else
                <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada lesson pada course ini.
                </div>
            @endif
        </section>
    </div>
</x-layouts.app>
