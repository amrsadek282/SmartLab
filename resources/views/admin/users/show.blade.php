@extends('layouts.app')

@section('title', "Staff: {$user->name}")
@section('page-title', "Staff Profile — {$user->name}")

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Breadcrumb & Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Staff List
        </a>

        <div class="flex items-center gap-2">
            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    {{ $user->isActive() ? 'Deactivate Account' : 'Activate Account' }}
                </button>
            </form>

            <a href="{{ route('admin.users.edit', $user) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                </svg>
                Edit Profile
            </a>
        </div>
    </div>

    <!-- User Header Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-2xl flex items-center justify-center shadow-md shadow-blue-500/20 shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $user->name }}</h2>
                        <x-badge :role="$user->role" size="sm">
                            {{ $user->role }}
                        </x-badge>
                        @if($user->isActive())
                            <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-[11px] font-semibold px-2 py-0.5 rounded-full border border-emerald-200/60 dark:border-emerald-800/40">
                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 text-[11px] font-semibold px-2 py-0.5 rounded-full border border-rose-200/60 dark:border-rose-800/40">
                                <span class="w-1 h-1 rounded-full bg-rose-500"></span> Inactive
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Staff member registered on {{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Demographics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 text-xs">
            <div>
                <span class="text-slate-400 dark:text-slate-500 block font-medium">Email Address</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ $user->email }}</span>
            </div>
            <div>
                <span class="text-slate-400 dark:text-slate-500 block font-medium">Phone Number</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 mt-0.5 block">{{ $user->phone ?: 'Not provided' }}</span>
            </div>
            <div>
                <span class="text-slate-400 dark:text-slate-500 block font-medium">Role Responsibility</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 mt-0.5 block capitalize">{{ $user->role }} Staff</span>
            </div>
        </div>
    </div>

    <!-- Activity Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Orders Created</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 tracking-tight">{{ $user->created_orders_count }}</span>
                <span class="text-xs text-slate-400">Total orders</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Results Entered</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-teal-600 dark:text-teal-400 tracking-tight">{{ $user->entered_results_count }}</span>
                <span class="text-xs text-slate-400">Lab investigations</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Reports Authorized</span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400 tracking-tight">{{ $user->generated_reports_count }}</span>
                <span class="text-xs text-slate-400">Final diagnostic reports</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders Created -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Recent Orders Created
            </h3>

            @if($recentOrders->isNotEmpty())
                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @foreach($recentOrders as $order)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <a href="{{ route('reception.orders.show', $order) }}" class="font-mono font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <p class="text-slate-500 dark:text-slate-400 mt-0.5">Patient: {{ $order->patient?->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <x-badge :status="$order->status" size="sm">
                                    {{ $order->status }}
                                </x-badge>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 dark:text-slate-500 py-4 text-center">No orders created by this user yet.</p>
            @endif
        </div>

        <!-- Recent Results / Reports -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                </svg>
                Recent Lab Investigations
            </h3>

            @if($recentResults->isNotEmpty())
                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @foreach($recentResults as $res)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $res->orderItem?->test?->name ?? 'Investigation' }}</span>
                                <p class="text-slate-500 dark:text-slate-400 mt-0.5">Value: {{ Str::limit($res->result_text, 25) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md">
                                    Entered
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $res->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 dark:text-slate-500 py-4 text-center">No lab test results entered by this technician yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
