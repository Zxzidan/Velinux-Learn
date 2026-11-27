@props([
    'user' => null,
    'pendingReviewCount' => 0,
])

@php
    $isCourses = request()->routeIs('courses.*');
    $isLabs = request()->routeIs('labs.*');
    $isQuizzes = request()->routeIs('quizzes.*');
    $isGamification = request()->routeIs('leaderboard.*');
    $isAdmin = request()->routeIs('admin.*');
    $isDashboard = request()->routeIs('dashboard');

    $activeRow = 'vlx-surface-raised vlx-text-primary border vlx-border dark:shadow-[inset_0_1px_0_rgba(255,255,255,0.08)]';
    $idleRow = 'vlx-text-secondary border border-transparent hover:bg-black/[0.03] dark:hover:bg-white/[0.04] hover:vlx-text-primary';
@endphp

<aside
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform duration-200 vlx-ease -translate-x-full vlx-surface-deep border-r vlx-border md:translate-x-0"
    aria-label="Sidenav"
    id="drawer-navigation"
>
    <div class="overflow-y-auto py-6 px-4 h-full pb-20 vlx-surface-deep">
        <form action="{{ route('courses.index') }}" method="GET" class="md:hidden mb-4">
            <label for="sidebar-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 vlx-text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"></path>
                    </svg>
                </div>
                <input
                    type="text"
                    name="q"
                    id="sidebar-search"
                    class="vlx-surface-raised border vlx-border vlx-text-primary text-sm rounded-lg focus:outline-none focus:border-emerald-600 dark:focus:border-[#4cc38a] block w-full h-9 pl-9 pr-3"
                    placeholder="Search"
                />
            </div>
        </form>

        <div class="px-3 mb-2 text-xs vlx-text-muted">Navigation</div>
        <ul class="space-y-1">
            <li>
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center h-9 px-3 gap-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isDashboard ? $activeRow : $idleRow }}"
                >
                    <svg aria-hidden="true" class="w-[18px] h-[18px] shrink-0 {{ $isDashboard ? 'text-emerald-600 dark:text-[#4cc38a]' : 'vlx-text-muted' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path>
                    </svg>
                    <span>Overview</span>
                </a>
            </li>

            <li>
                <button
                    type="button"
                    class="flex items-center w-full h-9 px-3 gap-2.5 text-sm font-medium rounded-lg transition-colors duration-150 cursor-pointer {{ $isCourses ? $activeRow : $idleRow }}"
                    aria-controls="dropdown-learning"
                    aria-expanded="{{ $isCourses ? 'true' : 'false' }}"
                    data-collapse-toggle="dropdown-learning"
                >
                    <svg aria-hidden="true" class="w-[18px] h-[18px] shrink-0 {{ $isCourses ? 'text-emerald-600 dark:text-[#4cc38a]' : 'vlx-text-muted' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                    </svg>
                    <span class="flex-1 text-left whitespace-nowrap">Learning (LMS)</span>
                    <svg data-collapse-chevron aria-hidden="true" class="w-4 h-4 vlx-text-muted transition-transform duration-150 {{ $isCourses ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </button>
                <ul id="dropdown-learning" class="{{ $isCourses ? '' : 'hidden' }} py-1 space-y-1">
                    <li>
                        <a href="{{ route('courses.index') }}" class="flex items-center h-8 pl-10 pr-3 text-sm vlx-text-secondary rounded-lg hover:bg-black/[0.03] dark:hover:bg-white/[0.04] hover:vlx-text-primary">
                            Course catalog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index', ['difficulty' => 'beginner']) }}" class="flex items-center h-8 pl-10 pr-3 text-sm vlx-text-secondary rounded-lg hover:bg-black/[0.03] dark:hover:bg-white/[0.04] hover:vlx-text-primary">
                            Learning paths
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}#video-learning" class="flex items-center h-8 pl-10 pr-3 text-sm vlx-text-secondary rounded-lg hover:bg-black/[0.03] dark:hover:bg-white/[0.04] hover:vlx-text-primary">
                            Video learning
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <button
                    type="button"
                    class="flex items-center w-full h-9 px-3 gap-2.5 text-sm font-medium rounded-lg transition-colors duration-150 cursor-pointer {{ $isLabs ? $activeRow : $idleRow }}"
                    aria-controls="dropdown-labs"
                    aria-expanded="{{ $isLabs ? 'true' : 'false' }}"
                    data-collapse-toggle="dropdown-labs"
                >
                    <svg aria-hidden="true" class="w-[18px] h-[18px] shrink-0 {{ $isLabs ? 'text-emerald-600 dark:text-[#4cc38a]' : 'vlx-text-muted' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z"></path>
                    </svg>
                    <span class="flex-1 text-left whitespace-nowrap">Cyber lab</span>
                    <svg data-collapse-chevron aria-hidden="true" class="w-4 h-4 vlx-text-muted transition-transform duration-150 {{ $isLabs ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </button>
                <ul id="dropdown-labs" class="{{ $isLabs ? '' : 'hidden' }} py-1 space-y-1">
                    <li>
                        <a href="{{ route('labs.index') }}" class="flex items-center h-8 pl-10 pr-3 text-sm vlx-text-secondary rounded-lg hover:bg-black/[0.03] dark:hover:bg-white/[0.04] hover:vlx-text-primary">
                            Sandbox labs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('labs.index', ['category' => 'Web Security Lab']) }}" class="flex items-center h-8 pl-10 pr-3 text-sm vlx-text-secondary rounded-lg hover:bg-black/[0.03] dark:hover:bg-white/[0.04] hover:vlx-text-primary">
                            Web security
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('labs.index') }}#submissions" class="flex items-center h-8 pl-10 pr-3 text-sm vlx-text-secondary rounded-lg hover:bg-black/[0.03] dark:hover:bg-white/[0.04] hover:vlx-text-primary">
                            Flag submissions
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a
                    href="{{ route('quizzes.index') }}"
                    class="flex items-center h-9 px-3 gap-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isQuizzes ? $activeRow : $idleRow }}"
                >
                    <svg aria-hidden="true" class="w-[18px] h-[18px] shrink-0 {{ $isQuizzes ? 'text-emerald-600 dark:text-[#4cc38a]' : 'vlx-text-muted' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"></path>
                    </svg>
                    <span class="flex-1 whitespace-nowrap">Cyber quiz &amp; AI</span>
                    <span class="font-mono text-xs tabular-nums vlx-text-muted">AI</span>
                </a>
            </li>

            <li>
                <a
                    href="{{ route('leaderboard.index') }}"
                    class="flex items-center h-9 px-3 gap-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isGamification ? $activeRow : $idleRow }}"
                >
                    <svg aria-hidden="true" class="w-[18px] h-[18px] shrink-0 {{ $isGamification ? 'text-emerald-600 dark:text-[#4cc38a]' : 'vlx-text-muted' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path>
                    </svg>
                    <span class="flex-1 whitespace-nowrap">Leaderboard &amp; XP</span>
                </a>
            </li>
        </ul>

        <div class="pt-6 mt-6 border-t vlx-border">
            <div class="px-3 mb-2 text-xs vlx-text-muted">Governance</div>
            <ul class="space-y-1">
                <li>
                    <a
                        href="{{ route('admin.index') }}"
                        class="flex items-center h-9 px-3 gap-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isAdmin ? $activeRow : $idleRow }}"
                    >
                        <svg aria-hidden="true" class="w-[18px] h-[18px] shrink-0 {{ $isAdmin ? 'text-emerald-600 dark:text-[#4cc38a]' : 'vlx-text-muted' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path>
                        </svg>
                        <span class="flex-1 whitespace-nowrap">Mentor &amp; Admin</span>
                        @if ($pendingReviewCount > 0)
                            <span class="font-mono text-xs tabular-nums vlx-text-muted">{{ $pendingReviewCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('labs.index') }}#lab-rules"
                        class="flex items-center h-9 px-3 gap-2.5 text-sm font-medium rounded-lg transition-colors duration-150 {{ $idleRow }}"
                    >
                        <svg aria-hidden="true" class="w-[18px] h-[18px] shrink-0 vlx-text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                        </svg>
                        <span>Rules &amp; ethics</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="hidden absolute bottom-0 left-0 justify-between items-center px-4 h-12 w-full lg:flex vlx-surface-deep border-t vlx-border z-20">
        <span class="text-xs vlx-text-muted">UPN Veteran Jatim</span>

        <div class="flex items-center gap-1">
            <div class="relative">
                <a
                    href="{{ route('admin.index') }}"
                    data-tooltip-target="tooltip-settings"
                    class="inline-flex justify-center items-center h-8 w-8 vlx-text-muted rounded-lg hover:vlx-text-primary hover:bg-black/5 dark:hover:bg-white/5"
                >
                    <svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </a>
                <div
                    id="tooltip-settings"
                    role="tooltip"
                    class="inline-block absolute bottom-full left-1/2 -translate-x-1/2 mb-2 invisible z-10 py-1.5 px-2.5 text-xs font-medium vlx-text-primary vlx-surface-card rounded-lg shadow-md opacity-0 transition-opacity duration-150 whitespace-nowrap border vlx-border"
                >
                    Mentor &amp; Admin
                </div>
            </div>

            <div class="relative">
                <button
                    type="button"
                    data-dropdown-toggle="language-dropdown"
                    aria-expanded="false"
                    class="inline-flex justify-center items-center px-2 h-8 text-xs font-mono vlx-text-muted rounded-lg cursor-pointer hover:vlx-text-primary hover:bg-black/5 dark:hover:bg-white/5"
                >
                    ID
                </button>
                <div
                    class="hidden absolute bottom-full right-0 mb-2 z-50 w-40 text-sm list-none vlx-surface-card rounded-xl divide-y divide-black/6 dark:divide-white/8 shadow-lg border vlx-border"
                    id="language-dropdown"
                >
                    <ul class="py-1" role="none">
                        <li>
                            <a href="{{ route('dashboard') }}" class="block py-2 px-4 text-xs vlx-text-secondary hover:bg-black/[0.04] dark:hover:bg-white/[0.04]">Bahasa Indonesia</a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard') }}" class="block py-2 px-4 text-xs vlx-text-secondary hover:bg-black/[0.04] dark:hover:bg-white/[0.04]">English (US)</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>
