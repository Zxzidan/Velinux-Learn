@props([
    'user' => null,
    'activeLabCount' => 0,
    'publishedCourseCount' => 0,
])

<section {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl border border-gray-200 bg-white text-gray-900 p-6 sm:p-8 velinux-grid-bg shadow-sm dark:border-primary-500/25 dark:bg-gray-900 dark:text-white dark:shadow-lg transition-colors duration-200']) }}>
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 lg:gap-8">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-primary-500/30 bg-primary-500/10 px-3.5 py-1 text-xs font-mono font-medium text-primary-700 dark:text-primary-300 mb-4">
                <span class="h-2 w-2 rounded-full bg-primary-500 animate-pulse"></span>
                VELINUX · UPN "VETERAN" JAWA TIMUR
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                Learn. Practice. <span class="text-primary-600 dark:text-primary-400">Secure.</span>
            </h1>
            <p class="mt-3 text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                Master Cybersecurity Through Interactive Learning.
                @if ($user)
                    Selamat datang kembali, <span class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</span> — rank
                    <span class="font-mono font-semibold text-primary-600 dark:text-primary-400">{{ $user->cyberRank() }}</span>.
                @endif
            </p>
            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a
                    href="{{ route('courses.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-gray-950 hover:bg-primary-400 transition shadow-xs"
                >
                    Start Learning
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a
                    href="{{ route('labs.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-gray-50 px-5 py-2.5 text-sm font-semibold text-gray-800 hover:border-primary-500/50 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-800/80 dark:text-gray-100 dark:hover:text-primary-300 transition"
                >
                    Explore Cyber Lab
                </a>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 sm:gap-4 shrink-0">
            <a href="{{ route('courses.index') }}" class="rounded-xl border border-gray-200 bg-gray-50/90 p-4 hover:border-primary-500/40 dark:border-gray-800 dark:bg-gray-950/80 transition">
                <div class="text-xs font-mono text-primary-600 dark:text-primary-400">01. Modules</div>
                <div class="mt-1.5 text-lg font-bold text-gray-900 dark:text-white">{{ $publishedCourseCount }}</div>
                <div class="mt-0.5 text-[11px] text-gray-500 dark:text-gray-400">Learn theory</div>
            </a>
            <a href="{{ route('quizzes.index') }}" class="rounded-xl border border-gray-200 bg-gray-50/90 p-4 hover:border-primary-500/40 dark:border-gray-800 dark:bg-gray-950/80 transition">
                <div class="text-xs font-mono text-sky-600 dark:text-sky-400">02. Challenges</div>
                <div class="mt-1.5 text-lg font-bold text-gray-900 dark:text-white">AI + Quiz</div>
                <div class="mt-0.5 text-[11px] text-gray-500 dark:text-gray-400">Test skills</div>
            </a>
            <a href="{{ route('labs.index') }}" class="rounded-xl border border-gray-200 bg-gray-50/90 p-4 hover:border-primary-500/40 dark:border-gray-800 dark:bg-gray-950/80 transition">
                <div class="text-xs font-mono text-emerald-600 dark:text-emerald-400">03. Cyber Lab</div>
                <div class="mt-1.5 text-lg font-bold text-gray-900 dark:text-white">{{ $activeLabCount }}</div>
                <div class="mt-0.5 text-[11px] text-gray-500 dark:text-gray-400">Practice safely</div>
            </a>
        </div>
    </div>
</section>
