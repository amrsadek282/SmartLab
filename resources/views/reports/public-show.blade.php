<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Test Report — {{ $report->report_number }} — SmartLab</title>

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

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
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
<body class="min-h-full flex flex-col bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased selection:bg-blue-600 selection:text-white transition-colors duration-150">

    <!-- Top Public Header -->
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 sticky top-0 z-30 shadow-xs">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white shadow-md shadow-blue-600/20 shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-slate-900 dark:text-white tracking-tight leading-tight">SmartLab</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Smart Laboratory Information System</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Theme Switcher Toggle -->
                <button type="button" onclick="toggleTheme()" id="theme-toggle-btn"
                    class="p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                    title="Toggle Theme">
                    <svg id="theme-icon-dark" class="w-4 h-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg id="theme-icon-light" class="w-4 h-4 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>

                <span class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 text-xs font-semibold px-3 py-1.5 rounded-full border border-emerald-200/60 dark:border-emerald-800/50">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Verified Medical Report
                </span>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 py-8 space-y-6">

        <!-- Report Status Banner -->
        <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-900/10 relative overflow-hidden">
            <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-xs text-blue-100 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Official Diagnostic Results
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Your Medical Report is Ready</h2>
                    <p class="text-blue-100 text-sm mt-1 max-w-xl">
                        Dear <span class="font-bold text-white">{{ $order->patient->name }}</span>, your test results for Order <span class="font-mono font-semibold">{{ $order->order_number }}</span> have been verified and finalized by SmartLab.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                    <a href="{{ route('public.reports.download', ['token' => $report->share_token]) }}"
                        class="inline-flex items-center justify-center gap-2 bg-white text-blue-900 hover:bg-blue-50 text-sm font-bold px-5 py-3 rounded-2xl shadow-lg shadow-black/10 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download PDF Report
                    </a>
                    <a href="{{ route('public.reports.stream', ['token' => $report->share_token]) }}" target="_blank"
                        class="inline-flex items-center justify-center gap-2 bg-blue-600/40 hover:bg-blue-600/60 border border-white/20 text-white text-sm font-semibold px-4 py-3 rounded-2xl transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        View in Browser
                    </a>
                </div>
            </div>
        </div>

        <!-- Meta Details Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Patient Name</p>
                <p class="text-sm font-bold text-slate-900 dark:text-white mt-1 truncate">{{ $order->patient->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ ucfirst($order->patient->gender) }} &middot; {{ $order->patient->age }} yrs</p>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Report Number</p>
                <p class="text-sm font-bold font-mono text-blue-700 dark:text-blue-400 mt-1">{{ $report->report_number }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Order {{ $order->order_number }}</p>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Report Date</p>
                <p class="text-sm font-bold text-slate-900 dark:text-white mt-1">{{ $report->generated_at ? $report->generated_at->format('d M Y, h:i A') : now()->format('d M Y') }}</p>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-0.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Finalized
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-xs">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Tests</p>
                <p class="text-sm font-bold text-slate-900 dark:text-white mt-1">{{ $order->orderItems->count() }} Investigation(s)</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">All results verified</p>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/40">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Laboratory Test Results</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Diagnostic investigations and biological reference ranges</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-950/60 border-b border-slate-200/80 dark:border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            <th class="px-6 py-3.5">Test Parameter</th>
                            <th class="px-6 py-3.5">Result Value</th>
                            <th class="px-6 py-3.5">Reference Range</th>
                            <th class="px-6 py-3.5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @foreach($order->orderItems as $item)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $item->test->name }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 font-mono">{{ $item->test->code }} @if($item->test->unit) &middot; {{ $item->test->unit }} @endif</p>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white whitespace-pre-line">
                                    {{ $item->result?->result_text ?? '—' }}
                                    @if($item->result?->notes)
                                        <p class="text-xs text-slate-400 dark:text-slate-500 font-normal italic mt-1">{{ $item->result->notes }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs">
                                    {{ $item->result?->reference_range ?? $item->test->reference_range ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold px-2.5 py-1 rounded-full border border-emerald-200/60 dark:border-emerald-800/40">
                                        Completed
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50/70 dark:bg-slate-950/50 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400">
                <p>Medical Technologist: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $order->orderItems->first()?->result?->technician?->name ?? 'Clinical Lab Specialist' }}</span></p>
                <div class="flex items-center gap-3">
                    <a href="{{ route('public.reports.download', ['token' => $report->share_token]) }}"
                        class="inline-flex items-center gap-1.5 font-bold text-blue-600 dark:text-blue-400 hover:underline">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download Official Signed PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Medical Disclaimer & Help Box -->
        <div class="bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/50 rounded-2xl p-5 text-xs text-amber-900 dark:text-amber-300 space-y-2">
            <div class="flex items-center gap-2 font-bold text-amber-950 dark:text-amber-200">
                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                Important Medical Notice &amp; Consultation Advice
            </div>
            <p class="leading-relaxed">
                Laboratory test results should always be interpreted by your treating physician in conjunction with your clinical symptoms and medical history. Do not initiate or discontinue any medications based solely on laboratory values without consulting a certified medical doctor.
            </p>
        </div>
    </main>

    <!-- Public Footer -->
    <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-6 px-4 text-center text-xs text-slate-500 dark:text-slate-400">
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <p>&copy; {{ date('Y') }} SmartLab. All rights reserved.</p>
            <p class="text-slate-400 dark:text-slate-500">Smart Laboratory Information System</p>
        </div>
    </footer>

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
    </script>
</body>
</html>
