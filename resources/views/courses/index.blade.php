<x-layouts.app title="Course Catalog" :user="$learner">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 pb-5 border-b vlx-border">
        <div>
            <span class="text-xs font-mono vlx-text-muted">LMS</span>
            <h1 class="text-2xl sm:text-[28px] font-medium tracking-[-0.02em] vlx-text-primary mt-1">Course Catalog</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @foreach (['' => 'All', 'beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'] as $value => $label)
                <a
                    href="{{ route('courses.index', array_filter(['difficulty' => $value, 'q' => $search])) }}"
                    class="rounded-lg px-3 h-8 inline-flex items-center text-xs font-medium transition-colors duration-150 {{ $selectedDifficulty === $value ? 'bg-gray-900 text-white dark:bg-[#eef0f5] dark:text-[#0b0d14]' : 'border vlx-border vlx-surface-raised vlx-text-secondary hover:vlx-text-primary' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($courses as $course)
            <x-velinux.course-card
                :course="$course"
                :enrollment="$enrollmentsByCourse->get($course->id)"
            />
        @empty
            <div class="col-span-full rounded-xl border vlx-border vlx-surface-card p-8 text-center text-sm vlx-text-muted">
                Tidak ada course yang sesuai dengan pencarian Anda.
            </div>
        @endforelse
    </div>

    <section id="video-learning" class="rounded-xl border vlx-border vlx-surface-card p-6">
        <div class="mb-5">
            <h2 class="text-base font-medium vlx-text-primary">Video pembelajaran &amp; praktik</h2>
            <p class="text-xs vlx-text-muted mt-0.5">Materi visual pendukung modul kelas VELINUX</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($videos as $video)
                <div class="rounded-lg border vlx-border vlx-surface-raised p-4">
                    <div class="flex items-center justify-between text-xs font-mono tabular-nums vlx-text-muted mb-2">
                        <span class="truncate">{{ $video->module?->course?->title ?? 'VELINUX Video' }}</span>
                        <span class="shrink-0 ml-2">{{ $video->formattedDuration() }}</span>
                    </div>
                    <h3 class="text-sm font-medium vlx-text-primary">{{ $video->title }}</h3>
                    @if ($video->description)
                        <p class="mt-1.5 text-xs vlx-text-secondary leading-relaxed">{{ $video->description }}</p>
                    @endif
                    @if ($video->module?->course)
                        <a
                            href="{{ route('courses.show', $video->module->course) }}"
                            class="mt-3 inline-flex text-xs font-medium text-emerald-700 dark:text-[#4cc38a] hover:underline"
                        >
                            Buka modul terkait
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-sm vlx-text-muted">Belum ada video pembelajaran tersedia.</p>
            @endforelse
        </div>
    </section>
</x-layouts.app>
