@extends('layouts.app')

@section('page-title', 'Laboratory Dashboard')

@section('content')
<div class="space-y-8">
    <!-- =================================================== -->
    <!-- GREETING & HEADER BAR                               -->
    <!-- =================================================== -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    @php
                        $hour = (int) now()->format('H');
                        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                    @endphp
                    {{ $greeting }}, {{ $user->name }}
                </h2>
                <x-badge :role="$user->role" size="sm">
                    {{ $user->role }}
                </x-badge>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-2">
                <span>{{ now()->format('l, F j, Y') }}</span>
                <span class="inline-block w-1 h-1 rounded-full bg-slate-300"></span>
                <span>SmartLab — Diagnostic Control Center</span>
            </p>
        </div>

        <!-- Quick Top Actions -->
        <div class="flex flex-wrap items-center gap-2.5">
            @if ($user->isAdmin() || $user->isReceptionist())
                <x-button href="{{ route('reception.patients.create') }}" variant="primary" size="sm">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.765Z" />
                    </svg>
                    New Patient
                </x-button>

                <x-button href="{{ route('reception.orders.create') }}" variant="secondary" size="sm">
                    <svg class="w-4 h-4 mr-1 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Order
                </x-button>
            @endif

            @if ($user->isAdmin() || $user->isTechnician())
                <x-button href="{{ route('lab.orders.index') }}" variant="outline" size="sm">
                    <svg class="w-4 h-4 mr-1 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                    </svg>
                    Lab Queue
                </x-button>
            @endif

            <x-button href="{{ route('booking.create') }}" variant="ghost" size="sm" target="_blank">
                <svg class="w-4 h-4 mr-1 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                </svg>
                Online Booking
            </x-button>
        </div>
    </div>

    <!-- =================================================== -->
    <!-- KEY STATISTICS CARDS (Using $stats array)          -->
    <!-- =================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- 1. Total Patients -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Patients</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_patients'] ?? 0 }}</span>
                <p class="text-[11px] text-slate-400 mt-1">Registered in system</p>
            </div>
        </div>

        <!-- 2. Today's Appointments -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Today's Visits</span>
                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-sky-700 tracking-tight">{{ $stats['todays_appointments'] ?? 0 }}</span>
                <p class="text-[11px] text-slate-400 mt-1">Scheduled for today</p>
            </div>
        </div>

        <!-- 3. Pending Appointments -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending Bookings</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-600 tracking-tight">{{ $stats['pending_appointments'] ?? 0 }}</span>
                <p class="text-[11px] text-slate-400 mt-1">Awaiting confirmation</p>
            </div>
        </div>

        <!-- 4. Pending Lab Orders -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending Orders</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-indigo-600 tracking-tight">{{ $stats['pending_orders'] ?? 0 }}</span>
                <p class="text-[11px] text-slate-400 mt-1">Samples / Queued</p>
            </div>
        </div>

        <!-- 5. Pending Results -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending Results</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-2xl sm:text-3xl font-extrabold text-purple-600 tracking-tight">{{ $stats['pending_results'] ?? 0 }}</span>
                <p class="text-[11px] text-slate-400 mt-1">Testing in progress</p>
            </div>
        </div>

        <!-- 6. Total Revenue -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Revenue</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-xl sm:text-2xl font-extrabold text-emerald-700 tracking-tight truncate block">
                    {{ number_format($stats['total_revenue'] ?? 0, 2) }}
                    <span class="text-xs font-semibold text-emerald-600">EGP</span>
                </span>
                <p class="text-[11px] text-slate-400 mt-1">Paid receipts sum</p>
            </div>
        </div>
    </div>

    <!-- =================================================== -->
    <!-- QUICK SHORTCUTS & SYSTEM WORKFLOW                  -->
    <!-- =================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    Role Quick Actions
                </h3>
                <p class="text-xs text-slate-500 mt-1">Common tasks for your account</p>

                <div class="mt-4 space-y-2">
                    @if ($user->isAdmin() || $user->isReceptionist())
                        <a href="{{ route('reception.patients.create') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/80 hover:border-blue-200 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                    +P
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-slate-800 group-hover:text-blue-700 block">Register Patient</span>
                                    <span class="text-[10px] text-slate-400">Add medical record & info</span>
                                </div>
                            </div>
                            <span class="text-slate-400 group-hover:text-blue-600">&rarr;</span>
                        </a>

                        <a href="{{ route('reception.orders.create') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/80 hover:border-blue-200 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs">
                                    +O
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-slate-800 group-hover:text-blue-700 block">Create Lab Order</span>
                                    <span class="text-[10px] text-slate-400">Select tests & generate invoice</span>
                                </div>
                            </div>
                            <span class="text-slate-400 group-hover:text-blue-600">&rarr;</span>
                        </a>
                    @endif

                    @if ($user->isAdmin() || $user->isTechnician())
                        <a href="{{ route('lab.orders.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-purple-50 border border-slate-200/80 hover:border-purple-200 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">
                                    LQ
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-slate-800 group-hover:text-purple-700 block">Lab Work Queue</span>
                                    <span class="text-[10px] text-slate-400">Enter results & complete tests</span>
                                </div>
                            </div>
                            <span class="text-slate-400 group-hover:text-purple-600">&rarr;</span>
                        </a>
                    @endif

                    @if ($user->isAdmin())
                        <a href="{{ route('admin.tests.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-indigo-50 border border-slate-200/80 hover:border-indigo-200 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                    TC
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-slate-800 group-hover:text-indigo-700 block">Manage Test Catalog</span>
                                    <span class="text-[10px] text-slate-400">Edit tests, units & prices</span>
                                </div>
                            </div>
                            <span class="text-slate-400 group-hover:text-indigo-600">&rarr;</span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('booking.create') }}" target="_blank" class="text-xs font-medium text-blue-600 hover:text-blue-700 flex items-center justify-center gap-1">
                    <span>Open Public Patient Booking Portal</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Laboratory Workflow Stepper Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs lg:col-span-2 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                    </svg>
                    SmartLab Core Operational Workflow
                </h3>
                <p class="text-xs text-slate-500 mt-1">End-to-end sample processing lifecycle</p>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-5 gap-3">
                    <div class="p-3 bg-blue-50/60 rounded-xl border border-blue-100 text-center">
                        <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold text-xs inline-flex items-center justify-center mb-2">1</span>
                        <h4 class="text-xs font-semibold text-slate-800">Booking / Visit</h4>
                        <p class="text-[10px] text-slate-500 mt-1">Online booking or Reception check-in</p>
                    </div>

                    <div class="p-3 bg-sky-50/60 rounded-xl border border-sky-100 text-center">
                        <span class="w-6 h-6 rounded-full bg-sky-600 text-white font-bold text-xs inline-flex items-center justify-center mb-2">2</span>
                        <h4 class="text-xs font-semibold text-slate-800">Order & Invoice</h4>
                        <p class="text-[10px] text-slate-500 mt-1">Select tests, collect fee & create order</p>
                    </div>

                    <div class="p-3 bg-purple-50/60 rounded-xl border border-purple-100 text-center">
                        <span class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-xs inline-flex items-center justify-center mb-2">3</span>
                        <h4 class="text-xs font-semibold text-slate-800">Lab Analysis</h4>
                        <p class="text-[10px] text-slate-500 mt-1">Technician tests sample & enters values</p>
                    </div>

                    <div class="p-3 bg-indigo-50/60 rounded-xl border border-indigo-100 text-center">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white font-bold text-xs inline-flex items-center justify-center mb-2">4</span>
                        <h4 class="text-xs font-semibold text-slate-800">PDF Report</h4>
                        <p class="text-[10px] text-slate-500 mt-1">Generate official verified medical report</p>
                    </div>

                    <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 text-center">
                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-bold text-xs inline-flex items-center justify-center mb-2">5</span>
                        <h4 class="text-xs font-semibold text-slate-800">WhatsApp Delivery</h4>
                        <p class="text-[10px] text-slate-500 mt-1">One-click instant report link delivery</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    System Status: Operational
                </span>
                <span>Active Role: <strong class="capitalize text-slate-700">{{ $user->role }}</strong></span>
            </div>
        </div>
    </div>

    <!-- =================================================== -->
    <!-- RECENT ACTIVITY FEEDS (Recent Orders & Appointments) -->
    <!-- =================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- 1. Recent Orders -->
        <x-card title="Recent Lab Orders" subtitle="Latest laboratory orders created in the system">
            <x-slot:actions>
                @if ($user->isAdmin() || $user->isReceptionist())
                    <x-button href="{{ route('reception.orders.index') }}" variant="ghost" size="sm">
                        View All &rarr;
                    </x-button>
                @elseif ($user->isTechnician())
                    <x-button href="{{ route('lab.orders.index') }}" variant="ghost" size="sm">
                        Lab Queue &rarr;
                    </x-button>
                @endif
            </x-slot:actions>

            @if ($recentOrders->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <p class="text-sm font-medium text-slate-500">No recent orders.</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-6 -my-6">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Order #</th>
                                <th class="px-4 py-3">Patient</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Invoice</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="font-medium text-slate-800">{{ $order->patient?->name ?? 'N/A' }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $order->patient?->phone ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-500 whitespace-nowrap">
                                        {{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <x-badge :status="$order->status" size="sm" dot />
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($order->invoice)
                                            <span class="font-semibold text-slate-700">
                                                {{ number_format($order->invoice->total_amount, 2) }} EGP
                                            </span>
                                            <div class="mt-0.5">
                                                <x-badge :status="$order->invoice->status" size="sm" />
                                            </div>
                                        @else
                                            <span class="text-slate-400">No Invoice</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if ($order->patient?->phone)
                                                <a
                                                    href="{{ $order->whatsapp_url }}"
                                                    target="_blank"
                                                    title="Send WhatsApp Report"
                                                    class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors"
                                                >
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.203c.043.072.043.419-.101.824z"/>
                                                    </svg>
                                                </a>
                                            @endif

                                            @if ($user->isAdmin() || $user->isReceptionist())
                                                <a href="{{ route('reception.orders.show', $order) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 px-2 py-1 rounded-md hover:bg-blue-50 transition-colors">
                                                    View
                                                </a>
                                            @elseif ($user->isTechnician())
                                                <a href="{{ route('lab.orders.results.edit', $order) }}" class="text-xs font-semibold text-purple-600 hover:text-purple-800 px-2 py-1 rounded-md hover:bg-purple-50 transition-colors">
                                                    Results
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

        <!-- 2. Recent Appointments -->
        <x-card title="Recent Appointments" subtitle="Upcoming & latest patient bookings">
            <x-slot:actions>
                @if ($user->isAdmin() || $user->isReceptionist())
                    <x-button href="{{ route('reception.appointments.index') }}" variant="ghost" size="sm">
                        View All &rarr;
                    </x-button>
                @endif
            </x-slot:actions>

            @if ($recentAppointments->isEmpty())
                <div class="py-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                    </svg>
                    <p class="text-sm font-medium text-slate-500">No recent appointments.</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-6 -my-6">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="px-6 py-3">Patient</th>
                                <th class="px-4 py-3">Date & Time</th>
                                <th class="px-4 py-3">Requested Test</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentAppointments as $appointment)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-3.5">
                                        <div class="font-semibold text-slate-800">
                                            {{ $appointment->patient?->name ?? $appointment->patient_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            {{ $appointment->phone ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600 whitespace-nowrap">
                                        @if ($appointment->appointment_date)
                                            <div class="font-medium text-slate-700">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $appointment->appointment_date->format('h:i A') }}</div>
                                        @else
                                            <span class="text-slate-400">Unscheduled</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium text-[11px]">
                                            {{ $appointment->test?->name ?? 'General Consultation' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <x-badge :status="$appointment->status" size="sm" dot />
                                    </td>
                                    <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                        @if ($user->isAdmin() || $user->isReceptionist())
                                            <a href="{{ route('reception.appointments.show', $appointment) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 px-2 py-1 rounded-md hover:bg-blue-50 transition-colors">
                                                Manage
                                            </a>
                                        @else
                                            <span class="text-slate-400 text-[11px]">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>
    </div>
</div>
@endsection
