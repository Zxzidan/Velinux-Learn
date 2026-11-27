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
    <body class="vlx-surface-page vlx-text-primary font-sans antialiased transition-colors duration-150 vlx-ease">
        <div class="min-h-screen vlx-surface-page">
            <x-velinux.navbar :user="$user" :notifications="$notifications" />

            <x-velinux.sidebar :user="$user" :pending-review-count="$pendingReviewCount" />

            <div id="drawer-backdrop" class="hidden fixed inset-0 z-30 bg-black/50 md:hidden"></div>

            <main class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 md:ml-64 min-h-screen">
                <div class="mx-auto max-w-[1440px] space-y-8">
                    @if (session('status'))
                        <div
                            role="alert"
                            class="flex items-center justify-between gap-3 rounded-xl border vlx-border vlx-surface-card px-4 py-3 text-sm font-medium vlx-text-primary"
                        >
                            <div class="flex items-center gap-2.5">
                                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600 dark:bg-[#4cc38a]"></span>
                                <span>{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            role="alert"
                            class="flex items-center justify-between gap-3 rounded-xl border border-rose-500/25 bg-rose-500/8 px-4 py-3 text-sm font-medium text-rose-700 dark:text-rose-300"
                        >
                            <div class="flex items-center gap-2.5">
                                <span class="inline-flex h-2 w-2 rounded-full bg-rose-600 dark:bg-rose-400"></span>
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
