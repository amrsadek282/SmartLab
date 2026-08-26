@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', 'Financial Invoices')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Invoices &amp; Payments</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage patient billing, payments, discounts, and financial settlements.</p>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <a href="{{ route('reception.invoices.index') }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors whitespace-nowrap {{ empty($status) ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                All Invoices
            </a>
            <a href="{{ route('reception.invoices.index', ['status' => 'paid']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors whitespace-nowrap {{ $status === 'paid' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Paid
            </a>
            <a href="{{ route('reception.invoices.index', ['status' => 'partially_paid']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors whitespace-nowrap {{ $status === 'partially_paid' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Partially Paid
            </a>
            <a href="{{ route('reception.invoices.index', ['status' => 'unpaid']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors whitespace-nowrap {{ $status === 'unpaid' ? 'bg-red-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Unpaid
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('reception.invoices.index') }}" class="w-full md:w-80 flex items-center gap-2">
            @if(!empty($status))
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="relative w-full">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                    placeholder="Search invoice #, order #, patient...">
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-colors shrink-0">
                Search
            </button>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3.5">Invoice #</th>
                        <th class="px-5 py-3.5">Order #</th>
                        <th class="px-5 py-3.5">Patient</th>
                        <th class="px-5 py-3.5">Net Amount</th>
                        <th class="px-5 py-3.5">Paid Amount</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <a href="{{ route('reception.invoices.show', $invoice) }}" class="font-mono text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs font-semibold text-slate-700">
                                <a href="{{ route('reception.orders.show', $invoice->order) }}" class="hover:underline">
                                    {{ $invoice->order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $invoice->order->patient->name }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $invoice->order->patient->phone }}</p>
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-900 text-xs">
                                {{ number_format($invoice->net_amount, 2) }} EGP
                            </td>
                            <td class="px-5 py-4 font-bold text-emerald-600 text-xs">
                                {{ number_format($invoice->paid_amount, 2) }} EGP
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $payBadges = [
                                        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'partially_paid' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'unpaid' => 'bg-red-50 text-red-700 border-red-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center {{ $payBadges[$invoice->payment_status] ?? 'bg-slate-100' }} border text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                    {{ str_replace('_', ' ', $invoice->payment_status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $invoice->created_at->format('d M, h:i A') }}
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('reception.invoices.show', $invoice) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                    View Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <p class="text-sm font-medium text-slate-600">No invoices found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
