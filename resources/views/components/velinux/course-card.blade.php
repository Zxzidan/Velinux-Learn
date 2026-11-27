@props([
    'course',
    'enrollment' => null,
])

@php
    $progress = $enrollment?->progress_percentage ?? null;
    $modulesCount = $course->modules_count ?? $course->modules->count();
@endphp

<article {{ $attributes->merge(['class' => 'flex flex-col justify-between rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 hover:border-primary-500/40 transition shadow-xs']) }}>
    <div>
        <div class="flex items-center justify-between gap-3 mb-4">
            <span class="rounded-md bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-[11px] font-mono font-medium text-gray-700 dark:text-gray-300">
                {{ $course->category }}
            </span>
            <span class="shrink-0 rounded-md px-2.5 py-0.5 text-[11px] font-mono font-semibold {{ $course->difficulty->badgeClasses() }}">
                {{ $course->difficulty->label() }}
            </span>
        </div>

        <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-snug">
            <a href="{{ route('courses.show', $course) }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition">
                {{ $course->title }}
            </a>
        </h3>

        <p class="mt-2.5 text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-2">
            {{ $course->description }}
        </p>
    </div>

    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-800">
        @if ($progress !== null)
            <div class="mb-3.5">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-gray-500 dark:text-gray-400">Progress</span>
                    <span class="font-mono font-semibold text-primary-600 dark:text-primary-400">{{ $progress }}%</span>
                </div>
                <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-800 overflow-hidden">
                    <div class="h-full rounded-full bg-primary-500" style="width: {{ min(100, max(0, $progress)) }}%"></div>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 text-xs text-gray-500 dark:text-gray-400 font-mono">
                <span>{{ $modulesCount }} Modul</span>
                <span>&middot;</span>
                <span>{{ $course->estimated_hours }}j</span>
                <span>&middot;</span>
                <span class="text-primary-600 dark:text-primary-400">+{{ $course->xp_reward }} XP</span>
            </div>

            <a
                href="{{ route('courses.show', $course) }}"
                class="shrink-0 inline-flex items-center rounded-lg bg-primary-500/15 px-3.5 py-1.5 text-xs font-semibold text-primary-700 dark:text-primary-300 hover:bg-primary-500/25 transition"
            >
                {{ $progress !== null ? 'Lanjutkan' : 'Pelajari' }}
            </a>
        </div>
    </div>
</article>
