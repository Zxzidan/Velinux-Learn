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
@endphp

<aside
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-900 dark:border-gray-800"
    aria-label="Sidenav"
    id="drawer-navigation"
>
    <div class="overflow-y-auto py-6 px-4 h-full pb-24 bg-white dark:bg-gray-900 transition-colors duration-200">
        <form action="{{ route('courses.index') }}" method="GET" class="md:hidden mb-4">
            <label for="sidebar-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"></path>
                    </svg>
                </div>
                <input
                    type="text"
                    name="q"
                    id="sidebar-search"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-9 p-2.5 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Search modules..."
                />
            </div>
        </form>

        <ul class="space-y-2">
            <li>
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg group {{ request()->routeIs('dashboard') ? 'bg-primary-500/15 text-primary-700 dark:text-primary-300 border border-primary-500/30' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                >
                    <svg aria-hidden="true" class="w-5 h-5 transition duration-75 {{ request()->routeIs('dashboard') ? 'text-primary-500' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Overview</span>
                </a>
            </li>

            <li>
                <button
                    type="button"
                    class="flex items-center px-3 py-2.5 w-full text-sm font-medium rounded-lg transition duration-75 group {{ $isCourses ? 'bg-primary-500/10 text-primary-700 dark:text-primary-300' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                    aria-controls="dropdown-learning"
                    aria-expanded="{{ $isCourses ? 'true' : 'false' }}"
                    data-collapse-toggle="dropdown-learning"
                >
                    <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                    </svg>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Learning (LMS)</span>
                    <svg data-collapse-chevron aria-hidden="true" class="w-4 h-4 transition-transform {{ $isCourses ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul id="dropdown-learning" class="{{ $isCourses ? '' : 'hidden' }} py-1.5 space-y-1">
                    <li>
                        <a href="{{ route('courses.index') }}" class="flex items-center p-2 pl-11 w-full text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white">
                            Course Catalog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index', ['difficulty' => 'beginner']) }}" class="flex items-center p-2 pl-11 w-full text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white">
                            Learning Paths
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}#video-learning" class="flex items-center p-2 pl-11 w-full text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white">
                            Video Learning
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <button
                    type="button"
                    class="flex items-center px-3 py-2.5 w-full text-sm font-medium rounded-lg transition duration-75 group {{ $isLabs ? 'bg-primary-500/10 text-primary-700 dark:text-primary-300' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                    aria-controls="dropdown-labs"
                    aria-expanded="{{ $isLabs ? 'true' : 'false' }}"
                    data-collapse-toggle="dropdown-labs"
                >
                    <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Cyber Lab</span>
                    <svg data-collapse-chevron aria-hidden="true" class="w-4 h-4 transition-transform {{ $isLabs ? 'rotate-180' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul id="dropdown-labs" class="{{ $isLabs ? '' : 'hidden' }} py-1.5 space-y-1">
                    <li>
                        <a href="{{ route('labs.index') }}" class="flex items-center p-2 pl-11 w-full text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white">
                            Virtual Sandbox Labs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('labs.index', ['category' => 'Web Security Lab']) }}" class="flex items-center p-2 pl-11 w-full text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white">
                            Web Security &amp; CTF
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('labs.index') }}#submissions" class="flex items-center p-2 pl-11 w-full text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white">
                            Flag Submissions
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a
                    href="{{ route('quizzes.index') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg group {{ $isQuizzes ? 'bg-primary-500/15 text-primary-700 dark:text-primary-300 border border-primary-500/30' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                >
                    <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="flex-1 ml-3 whitespace-nowrap">Cyber Quiz &amp; AI</span>
                    <span class="inline-flex justify-center items-center px-2 py-0.5 text-[11px] font-mono font-semibold rounded-full text-primary-800 bg-primary-100 dark:bg-primary-500/20 dark:text-primary-300">
                        AI
                    </span>
                </a>
            </li>

            <li>
                <a
                    href="{{ route('leaderboard.index') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg group {{ $isGamification ? 'bg-primary-500/15 text-primary-700 dark:text-primary-300 border border-primary-500/30' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                >
                    <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="flex-1 ml-3 whitespace-nowrap">Leaderboard &amp; XP</span>
                </a>
            </li>
        </ul>

        <ul class="pt-5 mt-5 space-y-2 border-t border-gray-200 dark:border-gray-800">
            <li>
                <a
                    href="{{ route('admin.index') }}"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition duration-75 group {{ $isAdmin ? 'bg-primary-500/15 text-primary-700 dark:text-primary-300 border border-primary-500/30' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
                >
                    <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="flex-1 ml-3 whitespace-nowrap">Mentor &amp; Admin</span>
                    @if ($pendingReviewCount > 0)
                        <span class="inline-flex justify-center items-center w-5 h-5 text-xs font-semibold rounded-full bg-amber-500/20 text-amber-600 dark:text-amber-400">
                            {{ $pendingReviewCount }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a
                    href="{{ route('labs.index') }}#lab-rules"
                    class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-200 group"
                >
                    <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Rules &amp; Ethics</span>
                </a>
            </li>
        </ul>

        <div class="mt-6 rounded-xl border border-primary-500/25 bg-primary-500/5 p-3.5">
            <p class="text-[11px] font-mono uppercase tracking-wider text-primary-600 dark:text-primary-400">UPN Veteran Jatim</p>
            <p class="mt-1 text-xs font-semibold text-gray-800 dark:text-gray-200">VELINUX Community</p>
            <p class="mt-0.5 text-[11px] text-gray-500 dark:text-gray-400">Learn. Practice. Secure.</p>
        </div>
    </div>

    <div class="hidden absolute bottom-0 left-0 justify-center items-center p-3 gap-3 w-full lg:flex bg-white border-t border-gray-200 dark:bg-gray-900 dark:border-gray-800 z-20">
        <button
            type="button"
            data-theme-toggle
            aria-label="Ganti tema terang atau gelap"
            class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800"
        >
            <svg data-theme-icon="light" class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path>
            </svg>
            <svg data-theme-icon="dark" class="hidden w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>

        <div class="relative">
            <a
                href="{{ route('admin.index') }}"
                data-tooltip-target="tooltip-settings"
                class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800"
            >
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <div
                id="tooltip-settings"
                role="tooltip"
                class="inline-block absolute bottom-full left-1/2 -translate-x-1/2 mb-2 invisible z-10 py-1.5 px-3 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-200 whitespace-nowrap border border-gray-700"
            >
                Mentor &amp; Admin Panel
            </div>
        </div>

        <div class="relative">
            <button
                type="button"
                data-dropdown-toggle="language-dropdown"
                aria-expanded="false"
                class="inline-flex justify-center items-center px-2 py-1.5 text-xs font-mono font-semibold text-gray-600 rounded-lg cursor-pointer dark:hover:text-white dark:text-gray-300 hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800"
            >
                ID / EN
            </button>
            <div
                class="hidden absolute bottom-full right-0 mb-2 z-50 w-40 text-sm list-none bg-white rounded-xl divide-y divide-gray-100 shadow-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700"
                id="language-dropdown"
            >
                <ul class="py-1" role="none">
                    <li>
                        <a href="{{ route('dashboard') }}" class="block py-2 px-4 text-xs text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Bahasa Indonesia</a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}" class="block py-2 px-4 text-xs text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">English (US)</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>
