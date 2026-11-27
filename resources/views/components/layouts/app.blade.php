@props([
    'title' => 'Dashboard',
    'user' => null,
    'notifications' => [],
    'pendingReviewCount' => 0,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }} — VELINUX Cybersecurity LMS</title>

        <link rel="icon" type="image/png" href="{{ asset('images/velinux-logo.png') }}">

        @fonts

        <script>
            (() => {
                const storedTheme = localStorage.getItem('velinux-theme');
                if (storedTheme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 text-gray-900 dark:bg-gray-950 dark:text-gray-100 font-sans transition-colors duration-200">
        <div class="antialiased bg-gray-100 dark:bg-gray-950 min-h-screen">
            <x-velinux.navbar :user="$user" :notifications="$notifications" />

            <x-velinux.sidebar :user="$user" :pending-review-count="$pendingReviewCount" />

            <div id="drawer-backdrop" class="hidden fixed inset-0 z-30 bg-gray-950/70 backdrop-blur-xs md:hidden"></div>

            <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 md:ml-64 min-h-screen">
                <div class="mx-auto max-w-7xl space-y-8">
                    @if (session('status'))
                        <div
                            role="alert"
                            class="flex items-center justify-between gap-3 rounded-xl border border-primary-500/30 bg-primary-50 px-5 py-3.5 text-sm font-medium text-primary-800 dark:bg-primary-950/60 dark:text-primary-300"
                        >
                            <div class="flex items-center gap-2.5">
                                <span class="inline-flex h-2 w-2 rounded-full bg-primary-500"></span>
                                <span>{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            role="alert"
                            class="flex items-center justify-between gap-3 rounded-xl border border-rose-500/30 bg-rose-50 px-5 py-3.5 text-sm font-medium text-rose-800 dark:bg-rose-950/60 dark:text-rose-300"
                        >
                            <div class="flex items-center gap-2.5">
                                <span class="inline-flex h-2 w-2 rounded-full bg-rose-500"></span>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
