<x-layouts.app title="Course Catalog" :user="$learner">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <span class="font-mono text-xs uppercase tracking-wider text-primary-600 dark:text-primary-400">Learning Management System</span>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Course Catalog &amp; Learning Paths</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Materi cybersecurity terstruktur dari Beginner hingga Advanced.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @foreach (['' => 'All', 'beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'] as $value => $label)
                <a
                    href="{{ route('courses.index', array_filter(['difficulty' => $value, 'q' => $search])) }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-mono font-semibold transition {{ $selectedDifficulty === $value ? 'bg-primary-500 text-gray-950' : 'border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-primary-500/40' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
        @forelse ($courses as $course)
            <x-velinux.course-card
                :course="$course"
                :enrollment="$enrollmentsByCourse->get($course->id)"
            />
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                Tidak ada course yang sesuai dengan pencarian Anda.
            </div>
        @endforelse
    </div>

    <section id="video-learning" class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-4">
            <span class="font-mono text-xs uppercase tracking-wider text-primary-600 dark:text-primary-400">Video Learning Support</span>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mt-1">Playlist Video Pembelajaran &amp; Praktik</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($videos as $video)
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950/70">
                    <div class="flex items-center justify-between text-xs font-mono text-primary-600 dark:text-primary-400 mb-2">
                        <span>{{ $video->module?->course?->title ?? 'VELINUX Video' }}</span>
                        <span>{{ $video->formattedDuration() }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $video->title }}</h3>
                    @if ($video->description)
                        <p class="mt-1.5 text-xs text-gray-600 dark:text-gray-400">{{ $video->description }}</p>
                    @endif
                    @if ($video->module?->course)
                        <a
                            href="{{ route('courses.show', $video->module->course) }}"
                            class="mt-3 inline-flex text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline"
                        >
                            Buka Modul Terkait &rarr;
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada video pembelajaran tersedia.</p>
            @endforelse
        </div>
    </section>
</x-layouts.app>
