<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartLab') - Smart Laboratory Information System</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Theme Initialization Script (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('smartlab_theme') || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (savedTheme === 'system' && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Tailwind CSS CDN + Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased selection:bg-blue-600 selection:text-white transition-colors duration-150">

    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- ========================================== -->
        <!-- MOBILE SIDEBAR OVERLAY & DRAWER            -->
        <!-- ========================================== -->
        <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity" onclick="toggleMobileSidebar()"></div>

        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 dark:bg-slate-950 text-slate-100 flex flex-col transform -translate-x-full lg:hidden transition-transform duration-200 ease-in-out shadow-2xl border-r border-slate-800">
            <!-- Mobile Brand Header -->
            <div class="h-16 px-6 flex items-center justify-between border-b border-slate-800 bg-slate-950/50">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-base font-bold text-white tracking-tight">SmartLab</span>
                        <p class="text-[10px] text-blue-400 font-medium">Smart Laboratory System</p>
                    </div>
                </a>
                <button type="button" onclick="toggleMobileSidebar()" class="text-slate-400 hover:text-white p-1 rounded-lg cursor-pointer">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Nav Items -->
            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
                @include('layouts.partials.sidebar-nav')
            </div>

            <!-- Mobile User Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                @include('layouts.partials.sidebar-user')
            </div>
        </aside>

        <!-- ========================================== -->
        <!-- DESKTOP SIDEBAR                            -->
        <!-- ========================================== -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-slate-900 dark:bg-slate-950 text-slate-100 shrink-0 border-r border-slate-800 shadow-xl">
            <!-- Brand Header -->
            <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-800 bg-slate-950/40">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-white tracking-tight leading-none">SmartLab</h1>
                        <span class="text-[11px] text-blue-400 font-medium">Smart Laboratory System</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-3.5 py-5 space-y-6">
                @include('layouts.partials.sidebar-nav')
            </div>

            <!-- User Info & Logout at Bottom -->
            <div class="p-3.5 border-t border-slate-800 bg-slate-950/50">
                @include('layouts.partials.sidebar-user')
            </div>
        </aside>

        <!-- ========================================== -->
        <!-- MAIN CONTENT AREA & TOP NAVBAR             -->
        <!-- ========================================== -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200/90 dark:border-slate-800 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs transition-colors duration-150">
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Mobile Hamburger Toggle -->
                    <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Breadcrumb / Page Title -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-slate-800 dark:text-white">
                            @yield('page-title', 'Dashboard')
                        </span>
                    </div>
                </div>

                <!-- Right Side Actions & User Menu -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Online Booking Quick Shortcut -->
                    <a href="{{ route('booking.create') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 dark:hover:bg-blue-900/50 border border-blue-200 dark:border-blue-800/60 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        <span>Public Booking</span>
                    </a>

                    <!-- Dark Mode / Theme Toggle Button -->
                    <button type="button" onclick="toggleTheme()" id="theme-toggle-topbar"
                        class="p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                        title="Toggle Light/Dark Theme">
                        <!-- Sun Icon (shown in dark mode) -->
                        <svg id="topbar-theme-icon-dark" class="w-4 h-4 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <!-- Moon Icon (shown in light mode) -->
                        <svg id="topbar-theme-icon-light" class="w-4 h-4 block dark:hidden text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    <!-- User Profile & Role Info -->
                    @auth
                        <div class="flex items-center gap-3 pl-3 sm:border-l sm:border-slate-200 dark:sm:border-slate-800">
                            <a href="{{ route('profile.show') }}" class="hidden sm:flex flex-col items-end group">
                                <span class="text-xs font-bold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-tight">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ auth()->user()->role }}</span>
                            </a>

                            <a href="{{ route('profile.show') }}" title="My Profile" class="w-9 h-9 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm flex items-center justify-center ring-2 ring-blue-100 dark:ring-slate-800 shadow-xs transition-all hover:scale-105">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </a>

                            <!-- Logout Form Button -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" title="Sign out" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 transition-colors focus:outline-none cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Global Flash Notifications -->
            <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                @if (session('success'))
                    <x-alert type="success" class="mb-4">
                        {{ session('success') }}
                    </x-alert>
                @endif

                @if (session('error'))
                    <x-alert type="error" class="mb-4">
                        {{ session('error') }}
                    </x-alert>
                @endif

                @if (session('warning'))
                    <x-alert type="warning" class="mb-4">
                        {{ session('warning') }}
                    </x-alert>
                @endif

                @if (session('info'))
                    <x-alert type="info" class="mb-4">
                        {{ session('info') }}
                    </x-alert>
                @endif

                @if (!empty($errors) && $errors->any())
                    <x-alert type="error" title="Please review the following errors:" class="mb-4">
                        <ul class="list-disc list-inside space-y-1 mt-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif
            </div>

            <!-- Page Content Body -->
            <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
                {{ $slot ?? '' }}
            </main>

            <!-- Global Footer -->
            <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 py-4 px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500 dark:text-slate-400">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 max-w-7xl mx-auto">
                    <p>&copy; {{ date('Y') }} SmartLab — Smart Laboratory Information System. All rights reserved.</p>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500">Clinical Laboratory Management Platform</p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Theme & Mobile Drawer JavaScript -->
    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('smartlab_theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('smartlab_theme', 'dark');
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (!sidebar || !backdrop) return;

            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }
    </script>
</body>
</html>
