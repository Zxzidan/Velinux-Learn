@props([
    'course',
    'enrollment' => null,
])

@php
    $progress = $enrollment?->progress_percentage ?? null;
    $modulesCount = $course->modules_count ?? $course->modules->count();
@endphp

<article {{ $attributes->merge(['class' => 'flex flex-col justify-between rounded-xl border vlx-border vlx-surface-card p-5 hover:vlx-border-strong transition-colors duration-150 vlx-ease']) }}>
    <div>
        <div class="flex items-center justify-between gap-3 mb-3">
            <span class="text-xs font-mono vlx-text-muted truncate">
                {{ $course->category }}
            </span>
            <span class="shrink-0 rounded-md px-2 py-0.5 text-[11px] font-mono font-medium {{ $course->difficulty->badgeClasses() }}">
                {{ $course->difficulty->label() }}
            </span>
        </div>

        <h3 class="text-base font-medium vlx-text-primary leading-snug">
            <a href="{{ route('courses.show', $course) }}" class="hover:text-emerald-700 dark:hover:text-[#4cc38a] transition-colors duration-150">
                {{ $course->title }}
            </a>
        </h3>
    </div>

    <div class="mt-4 pt-3.5 border-t vlx-border">
        @if ($progress !== null)
            <div class="mb-3">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="vlx-text-muted">Progress</span>
                    <span class="font-mono tabular-nums font-medium vlx-text-primary">{{ $progress }}%</span>
                </div>
                <div class="h-1.5 w-full rounded-full vlx-surface-raised overflow-hidden">
                    <div class="h-full rounded-full bg-emerald-600 dark:bg-[#4cc38a]" style="width: {{ min(100, max(0, $progress)) }}%"></div>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-xs font-mono tabular-nums vlx-text-muted">
                <span>{{ $modulesCount }} modul</span>
                <span>&middot;</span>
                <span>{{ $course->estimated_hours }}j</span>
                <span>&middot;</span>
                <span class="text-emerald-700 dark:text-[#4cc38a]">+{{ $course->xp_reward }} XP</span>
            </div>

            <a
                href="{{ route('courses.show', $course) }}"
                class="shrink-0 inline-flex items-center h-8 px-3 rounded-lg border vlx-border vlx-surface-raised text-xs font-medium vlx-text-primary hover:vlx-border-strong active:scale-[0.98] transition-[border-color,transform] duration-150"
            >
                {{ $progress !== null ? 'Lanjutkan' : 'Pelajari' }}
            </a>
        </div>
    </div>
</article>
