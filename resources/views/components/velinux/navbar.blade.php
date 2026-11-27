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

<nav class="vlx-surface-deep border-b vlx-border px-4 sm:px-6 h-16 flex items-center fixed left-0 right-0 top-0 z-50 transition-colors duration-150 vlx-ease">
    <div class="flex w-full justify-between items-center gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <button
                type="button"
                data-drawer-target="drawer-navigation"
                data-drawer-toggle="drawer-navigation"
                aria-controls="drawer-navigation"
                aria-expanded="false"
                class="p-2 rounded-lg cursor-pointer md:hidden vlx-text-muted hover:vlx-text-primary hover:bg-black/5 dark:hover:bg-white/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/50 transition-colors duration-150"
            >
                <svg data-drawer-icon aria-hidden="true" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
                </svg>
                <svg data-drawer-icon aria-hidden="true" class="hidden w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="sr-only">Toggle sidebar</span>
            </button>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white p-1 border vlx-border">
                    <img
                        src="{{ asset('images/velinux-logo.png') }}"
                        alt="Logo VELINUX"
                        class="h-full w-full object-contain"
                    />
                </span>
                <span class="flex flex-col leading-none">
                    <span class="text-base font-semibold tracking-tight vlx-text-primary">VELINUX</span>
                    <span class="text-[11px] vlx-text-muted mt-0.5">Cybersecurity LMS</span>
                </span>
            </a>

            <form action="{{ route('courses.index') }}" method="GET" class="hidden md:block ml-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative w-64 lg:w-80">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 vlx-text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"></path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        id="topbar-search"
                        class="vlx-surface-raised border vlx-border vlx-text-primary placeholder:text-gray-400 dark:placeholder:text-[#7a8296] text-sm rounded-lg focus:outline-none focus:border-emerald-600 dark:focus:border-[#4cc38a] block w-full h-9 pl-9 pr-3 transition-colors duration-150"
                        placeholder="Search"
                    />
                </div>
            </form>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <span class="hidden sm:inline-flex items-center rounded-lg border vlx-border vlx-surface-raised px-2.5 h-8 text-xs font-mono tabular-nums font-medium vlx-text-secondary">
                {{ $displayXp }} XP
            </span>

            <button
                type="button"
                data-theme-toggle
                aria-label="Ganti tema terang atau gelap"
                title="Ganti tema terang / gelap"
                class="inline-flex items-center gap-2 rounded-lg border vlx-border vlx-surface-raised px-2.5 h-8 text-xs font-medium vlx-text-secondary hover:vlx-text-primary active:scale-[0.98] transition-[color,background-color,transform] duration-150 vlx-ease cursor-pointer"
            >
                <svg data-theme-icon="light" class="w-4 h-4 vlx-text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
                </svg>
                <svg data-theme-icon="dark" class="hidden w-4 h-4 vlx-text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"></path>
                </svg>
                <span data-theme-label class="hidden lg:inline">Mode terang</span>
            </button>

            <button
                type="button"
                data-drawer-toggle="drawer-navigation"
                data-focus-search
                aria-controls="drawer-navigation"
                class="p-2 rounded-lg md:hidden vlx-text-muted hover:vlx-text-primary hover:bg-black/5 dark:hover:bg-white/5"
            >
                <span class="sr-only">Toggle search</span>
                <svg aria-hidden="true" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"></path>
                </svg>
            </button>

            <!-- Notifications -->
            <div class="relative">
                <button
                    type="button"
                    data-dropdown-toggle="notification-dropdown"
                    aria-expanded="false"
                    class="relative inline-flex items-center justify-center h-8 w-8 rounded-lg vlx-text-muted hover:vlx-text-primary hover:bg-black/5 dark:hover:bg-white/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/50 transition-colors duration-150 cursor-pointer"
                >
                    <span class="sr-only">Notifications</span>
                    <svg aria-hidden="true" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                    </svg>
                    @if (count($notifications) > 0)
                        <span class="absolute top-1.5 right-1.5 h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-[#4cc38a]"></span>
                    @endif
                </button>

                <div
                    class="hidden absolute right-0 top-full mt-2 w-80 sm:w-96 overflow-hidden z-50 list-none vlx-surface-card divide-y divide-black/6 dark:divide-white/8 shadow-lg border vlx-border rounded-xl"
                    id="notification-dropdown"
                >
                    <div class="py-2.5 px-4 text-xs font-medium vlx-text-secondary vlx-surface-raised">
                        Notifications
                    </div>
                    <div class="divide-y divide-black/6 dark:divide-white/8 max-h-80 overflow-y-auto">
                        @forelse ($notifications as $notification)
                            <a href="{{ $notification['url'] ?? '#' }}" class="flex gap-3 py-3 px-4 hover:bg-black/[0.03] dark:hover:bg-white/[0.03] transition-colors duration-150">
                                <span class="shrink-0 mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-lg vlx-surface-raised border vlx-border font-mono text-xs tabular-nums font-medium vlx-text-primary">
                                    {{ $notification['badge'] ?? 'AI' }}
                                </span>
                                <div class="w-full min-w-0">
                                    <div class="text-sm vlx-text-secondary leading-snug">
                                        <span class="font-medium vlx-text-primary">{{ $notification['title'] }}</span>
                                        {{ $notification['body'] }}
                                    </div>
                                    <div class="mt-1 text-xs vlx-text-muted">
                                        {{ $notification['time'] }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="py-6 px-4 text-center text-sm vlx-text-muted">
                                Belum ada notifikasi terbaru
                            </div>
                        @endforelse
                    </div>
                    <a
                        href="{{ route('quizzes.index') }}"
                        class="block py-2.5 text-xs font-medium text-center text-emerald-700 dark:text-[#4cc38a] vlx-surface-raised hover:underline"
                    >
                        Lihat AI assessments
                    </a>
                </div>
            </div>

            <!-- Apps / Platform Navigation -->
            <div class="relative">
                <button
                    type="button"
                    data-dropdown-toggle="apps-dropdown"
                    aria-expanded="false"
                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg vlx-text-muted hover:vlx-text-primary hover:bg-black/5 dark:hover:bg-white/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/50 transition-colors duration-150 cursor-pointer"
                >
                    <span class="sr-only">Open modules menu</span>
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path>
                    </svg>
                </button>

                <div
                    class="hidden absolute right-0 top-full mt-2 w-72 overflow-hidden z-50 list-none vlx-surface-card divide-y divide-black/6 dark:divide-white/8 shadow-lg border vlx-border rounded-xl"
                    id="apps-dropdown"
                >
                    <div class="py-2.5 px-4 text-xs font-medium vlx-text-secondary vlx-surface-raised">
                        Workspace shortcuts
                    </div>
                    <div class="grid grid-cols-3 gap-1.5 p-2.5">
                        @foreach ([
                            ['route' => route('courses.index'), 'label' => 'Modules'],
                            ['route' => route('quizzes.index'), 'label' => 'Cyber quiz'],
                            ['route' => route('labs.index'), 'label' => 'Cyber lab'],
                            ['route' => route('quizzes.index').'#ai-feedback', 'label' => 'AI eval'],
                            ['route' => route('leaderboard.index'), 'label' => 'Ranking'],
                            ['route' => route('admin.index'), 'label' => 'Mentor'],
                        ] as $shortcut)
                            <a href="{{ $shortcut['route'] }}" class="flex flex-col items-center justify-center rounded-lg p-2.5 text-center hover:bg-black/[0.04] dark:hover:bg-white/[0.04] transition-colors duration-150">
                                <span class="text-xs font-medium vlx-text-primary">{{ $shortcut['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <button
                    type="button"
                    class="flex items-center gap-2 rounded-lg p-1 hover:bg-black/5 dark:hover:bg-white/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/50 cursor-pointer"
                    id="user-menu-button"
                    aria-expanded="false"
                    data-dropdown-toggle="dropdown"
                >
                    <span class="sr-only">Open user menu</span>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg vlx-surface-raised border vlx-border text-xs font-mono font-medium vlx-text-primary">
                        {{ $initials !== '' ? $initials : 'VL' }}
                    </span>
                </button>

                <div
                    class="hidden absolute right-0 top-full mt-2 z-50 w-60 list-none vlx-surface-card divide-y divide-black/6 dark:divide-white/8 shadow-lg border vlx-border rounded-xl"
                    id="dropdown"
                >
                    <div class="py-3 px-4">
                        <div class="flex items-center justify-between gap-2">
                            <span class="block text-sm font-medium vlx-text-primary truncate">{{ $displayName }}</span>
                            <span class="rounded px-1.5 py-0.5 text-[11px] font-mono vlx-surface-raised border vlx-border vlx-text-secondary">{{ $displayRole }}</span>
                        </div>
                        <span class="block text-xs vlx-text-muted truncate mt-0.5">{{ $displayEmail }}</span>
                    </div>
                    <ul class="py-1 text-sm vlx-text-secondary" aria-labelledby="dropdown">
                        <li>
                            <a href="{{ route('dashboard') }}" class="block py-2 px-4 hover:bg-black/[0.04] dark:hover:bg-white/[0.04] hover:vlx-text-primary">Overview</a>
                        </li>
                        <li>
                            <a href="{{ route('leaderboard.index') }}" class="block py-2 px-4 hover:bg-black/[0.04] dark:hover:bg-white/[0.04] hover:vlx-text-primary">Achievements &amp; XP</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.index') }}" class="block py-2 px-4 hover:bg-black/[0.04] dark:hover:bg-white/[0.04] hover:vlx-text-primary">Mentor &amp; Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
