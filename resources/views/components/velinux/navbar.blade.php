@props([
    'user' => null,
    'notifications' => [],
])

@php
    $displayName = $user?->name ?? 'Raka Pratama';
    $displayEmail = $user?->email ?? 'raka@velinux.test';
    $displayRole = $user?->role?->name ?? 'Mahasiswa';
    $displayXp = number_format($user?->xp_points ?? 0);
    $initials = collect(explode(' ', $displayName))
        ->filter()
        ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

<nav class="bg-white/95 backdrop-blur-md border-b border-gray-200 px-4 sm:px-6 py-3 dark:bg-gray-900/95 dark:border-gray-800 fixed left-0 right-0 top-0 z-50 transition-colors duration-200">
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div class="flex justify-start items-center gap-3">
            <button
                type="button"
                data-drawer-target="drawer-navigation"
                data-drawer-toggle="drawer-navigation"
                aria-controls="drawer-navigation"
                aria-expanded="false"
                class="p-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-800 focus:ring-2 focus:ring-primary-500/40 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
            >
                <svg data-drawer-icon aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
                <svg data-drawer-icon aria-hidden="true" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Toggle sidebar</span>
            </button>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 mr-2">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white p-1 border border-gray-200 dark:border-primary-500/40 shadow-xs">
                    <img
                        src="{{ asset('images/velinux-logo.png') }}"
                        alt="Logo VELINUX"
                        class="h-full w-full object-contain"
                    />
                </span>
                <span class="flex flex-col leading-none">
                    <span class="text-lg font-bold tracking-wider text-gray-900 dark:text-white">VELINUX</span>
                    <span class="text-[10px] font-mono uppercase tracking-widest text-primary-600 dark:text-primary-400">Cybersec LMS</span>
                </span>
            </a>

            <form action="{{ route('courses.index') }}" method="GET" class="hidden md:block md:pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-72 lg:w-96">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"></path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        id="topbar-search"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-9 py-2 pr-3 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Search modules, labs, or CTF..."
                    />
                </div>
            </form>
        </div>

        <div class="flex items-center gap-2 sm:gap-2.5 lg:order-2">
            <span class="hidden sm:inline-flex items-center gap-1.5 rounded-lg border border-primary-500/30 bg-primary-500/10 px-2.5 py-1.5 text-xs font-mono font-semibold text-primary-700 dark:text-primary-300">
                <span class="h-1.5 w-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                {{ $displayXp }} XP
            </span>

            <!-- Light / Dark Theme Toggle -->
            <button
                type="button"
                data-theme-toggle
                aria-label="Ganti tema terang atau gelap"
                title="Ganti tema terang / gelap"
                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:border-primary-500/40 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition cursor-pointer"
            >
                <svg data-theme-icon="light" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path>
                </svg>
                <svg data-theme-icon="dark" class="hidden w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <span data-theme-label class="hidden lg:inline font-mono text-[11px]">Light</span>
            </button>

            <button
                type="button"
                data-drawer-toggle="drawer-navigation"
                data-focus-search
                aria-controls="drawer-navigation"
                class="p-2 text-gray-500 rounded-lg md:hidden hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 focus:ring-2 focus:ring-primary-500/40"
            >
                <span class="sr-only">Toggle search</span>
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"></path>
                </svg>
            </button>

            <!-- Notifications -->
            <div class="relative">
                <button
                    type="button"
                    data-dropdown-toggle="notification-dropdown"
                    aria-expanded="false"
                    class="relative p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 focus:ring-2 focus:ring-primary-500/40 cursor-pointer"
                >
                    <span class="sr-only">View notifications</span>
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    @if (count($notifications) > 0)
                        <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-primary-500"></span>
                    @endif
                </button>

                <div
                    class="hidden absolute right-0 top-full mt-2 w-80 sm:w-96 overflow-hidden z-50 text-base list-none bg-white divide-y divide-gray-100 shadow-xl border border-gray-200 dark:divide-gray-700 dark:bg-gray-800 dark:border-gray-700 rounded-xl"
                    id="notification-dropdown"
                >
                    <div class="block py-2.5 px-4 text-sm font-semibold text-center text-gray-700 bg-gray-50 dark:bg-gray-900 dark:text-gray-200">
                        Notifications
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-80 overflow-y-auto">
                        @forelse ($notifications as $notification)
                            <a href="{{ $notification['url'] ?? '#' }}" class="flex gap-3 py-3 px-4 hover:bg-gray-50 dark:hover:bg-gray-700/60">
                                <span class="shrink-0 mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-full bg-primary-500/15 text-primary-600 dark:text-primary-400 font-mono text-xs font-bold">
                                    {{ $notification['badge'] ?? 'AI' }}
                                </span>
                                <div class="w-full min-w-0">
                                    <div class="text-gray-600 font-normal text-sm mb-1 dark:text-gray-300">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $notification['title'] }}</span>
                                        {{ $notification['body'] }}
                                    </div>
                                    <div class="text-xs font-medium text-primary-600 dark:text-primary-400">
                                        {{ $notification['time'] }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="py-6 px-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada notifikasi terbaru.
                            </div>
                        @endforelse
                    </div>
                    <a
                        href="{{ route('quizzes.index') }}"
                        class="block py-2.5 text-sm font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-900 dark:text-primary-400 dark:hover:underline"
                    >
                        Lihat AI Assessments &amp; Aktivitas
                    </a>
                </div>
            </div>

            <!-- Apps / Platform Pillars -->
            <div class="relative">
                <button
                    type="button"
                    data-dropdown-toggle="apps-dropdown"
                    aria-expanded="false"
                    class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 focus:ring-2 focus:ring-primary-500/40 cursor-pointer"
                >
                    <span class="sr-only">Open platform modules</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>

                <div
                    class="hidden absolute right-0 top-full mt-2 w-80 overflow-hidden z-50 text-base list-none bg-white divide-y divide-gray-100 shadow-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:divide-gray-700 rounded-xl"
                    id="apps-dropdown"
                >
                    <div class="block py-2.5 px-4 text-sm font-semibold text-center text-gray-700 bg-gray-50 dark:bg-gray-900 dark:text-gray-200">
                        VELINUX Pillars
                    </div>
                    <div class="grid grid-cols-3 gap-2 p-3">
                        <a href="{{ route('courses.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/70 group">
                            <span class="mx-auto mb-1 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-primary-500/15 text-primary-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path></svg>
                            </span>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Modules</div>
                        </a>
                        <a href="{{ route('quizzes.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/70 group">
                            <span class="mx-auto mb-1 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-500/15 text-sky-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                            </span>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Cyber Quiz</div>
                        </a>
                        <a href="{{ route('labs.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/70 group">
                            <span class="mx-auto mb-1 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/15 text-emerald-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </span>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Cyber Lab</div>
                        </a>
                        <a href="{{ route('quizzes.index') }}#ai-feedback" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/70 group">
                            <span class="mx-auto mb-1 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-purple-500/15 text-purple-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6V7z"></path><path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2z" clip-rule="evenodd"></path></svg>
                            </span>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">AI Eval</div>
                        </a>
                        <a href="{{ route('leaderboard.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/70 group">
                            <span class="mx-auto mb-1 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/15 text-amber-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </span>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Ranking</div>
                        </a>
                        <a href="{{ route('admin.index') }}" class="block p-3 text-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/70 group">
                            <span class="mx-auto mb-1 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500/15 text-rose-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </span>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Mentor</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <button
                    type="button"
                    class="flex items-center gap-2 text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-primary-500/30 cursor-pointer"
                    id="user-menu-button"
                    aria-expanded="false"
                    data-dropdown-toggle="dropdown"
                >
                    <span class="sr-only">Open user menu</span>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary-600 text-xs font-bold text-white ring-2 ring-primary-400/50">
                        {{ $initials !== '' ? $initials : 'VL' }}
                    </span>
                </button>

                <div
                    class="hidden absolute right-0 top-full mt-2 z-50 w-60 text-base list-none bg-white divide-y divide-gray-100 shadow-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:divide-gray-700 rounded-xl"
                    id="dropdown"
                >
                    <div class="py-3 px-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="block text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $displayName }}</span>
                            <span class="rounded px-1.5 py-0.5 text-[10px] font-mono font-semibold bg-primary-500/15 text-primary-600 dark:text-primary-400">{{ $displayRole }}</span>
                        </div>
                        <span class="block text-xs text-gray-500 truncate dark:text-gray-400 mt-0.5">{{ $displayEmail }}</span>
                    </div>
                    <ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
                        <li>
                            <a href="{{ route('dashboard') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">Dashboard Overview</a>
                        </li>
                        <li>
                            <a href="{{ route('leaderboard.index') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">Achievements &amp; XP</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.index') }}" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">Mentor &amp; Admin Panel</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
