@extends('layouts.app')

@section('title', "Invoice {$invoice->invoice_number}")
@section('page-title', "Invoice — {$invoice->invoice_number}")

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('reception.invoices.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Invoices
        </a>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                </svg>
                Print Receipt
            </button>
            <a href="{{ route('reception.orders.show', $invoice->order) }}"
                class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-colors">
                View Lab Order
            </a>
        </div>
    </div>

    <!-- Invoice Receipt Paper Box -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-3xl p-8 sm:p-10 shadow-sm space-y-8">

        <!-- Receipt Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs">SL</div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">SMARTLAB</h1>
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Official Medical Analysis Invoice &amp; Payment Receipt — Smart Laboratory Information System</p>
            </div>

            <div class="text-left sm:text-right">
                <p class="font-mono text-base font-bold text-blue-700">{{ $invoice->invoice_number }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Date: {{ $invoice->created_at->format('d M Y, h:i A') }}</p>
                <div class="mt-2">
                    @php
                        $payBadges = [
                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'partially_paid' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'unpaid' => 'bg-red-50 text-red-700 border-red-200',
                        ];
                    @endphp
                    <span class="inline-flex items-center {{ $payBadges[$invoice->payment_status] ?? 'bg-slate-100' }} border text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                        {{ str_replace('_', ' ', $invoice->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Meta Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-5 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
            <div>
                <p class="font-bold text-slate-400 uppercase tracking-wider text-[11px] mb-1">Billed To (Patient)</p>
                <p class="text-sm font-bold text-slate-900">{{ $invoice->order->patient->name }}</p>
                <p class="text-slate-500 mt-0.5">Phone: {{ $invoice->order->patient->phone }}</p>
                <p class="text-slate-500">Gender / Age: {{ ucfirst($invoice->order->patient->gender) }} &middot; {{ $invoice->order->patient->age }} yrs</p>
            </div>
            <div class="sm:text-right">
                <p class="font-bold text-slate-400 uppercase tracking-wider text-[11px] mb-1">Order Reference</p>
                <p class="text-sm font-mono font-bold text-slate-900">{{ $invoice->order->order_number }}</p>
                <p class="text-slate-500 mt-0.5">Payment Method: <span class="font-semibold text-slate-700 uppercase">{{ $invoice->payment_method ?? 'Not Set' }}</span></p>
                <p class="text-slate-500">Staff: {{ $invoice->order->creator?->name ?? 'Front Desk' }}</p>
            </div>
        </div>

        <!-- Itemized Tests Table -->
        <div>
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Itemized Laboratory Services</h2>
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-left font-semibold uppercase text-slate-500 border-b border-slate-200">
                            <th class="px-5 py-3">#</th>
                            <th class="px-5 py-3">Test Name</th>
                            <th class="px-5 py-3">Category / Code</th>
                            <th class="px-5 py-3 text-right">Price (EGP)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($invoice->order->orderItems as $idx => $item)
                            <tr>
                                <td class="px-5 py-3.5 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $item->test->name }}</td>
                                <td class="px-5 py-3.5 text-slate-500 font-mono">{{ $item->test->code }}</td>
                                <td class="px-5 py-3.5 text-right font-semibold text-slate-900">{{ number_format($item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="flex justify-end pt-4">
            <div class="w-full sm:w-80 space-y-2.5 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Subtotal</span>
                    <span class="font-semibold text-slate-800">{{ number_format($invoice->total_amount, 2) }} EGP</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Discount</span>
                    <span class="font-semibold text-slate-800">- {{ number_format($invoice->discount, 2) }} EGP</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-200 text-sm font-bold text-slate-900">
                    <span>Net Amount</span>
                    <span class="font-black text-blue-700">{{ number_format($invoice->net_amount, 2) }} EGP</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Amount Paid</span>
                    <span class="font-bold text-emerald-600">{{ number_format($invoice->paid_amount, 2) }} EGP</span>
                </div>
                @php
                    $remaining = max(0, $invoice->net_amount - $invoice->paid_amount);
                @endphp
                <div class="flex justify-between pt-2 border-t border-slate-100 text-sm font-bold {{ $remaining > 0 ? 'text-red-600' : 'text-slate-400' }}">
                    <span>Remaining Balance</span>
                    <span>{{ number_format($remaining, 2) }} EGP</span>
                </div>
            </div>
        </div>

        <!-- Payment Settlement Form (if not paid) -->
        @if($invoice->payment_status !== 'paid')
            <div class="pt-6 border-t border-slate-200">
                <h3 class="text-sm font-bold text-slate-900 mb-3">Process Payment Settlement</h3>
                <form method="POST" action="{{ route('reception.invoices.pay', $invoice) }}" class="flex flex-col sm:flex-row items-end gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    @csrf
                    @method('PATCH')

                    <div class="w-full sm:w-1/3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Payment Method</label>
                        <select name="payment_method" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-blue-500">
                            <option value="cash" selected>Cash</option>
                            <option value="card">Credit / Debit Card</option>
                            <option value="online">Online Transfer / Digital Wallet</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-1/3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Discount (EGP)</label>
                        <input type="number" step="0.01" min="0" name="discount" value="{{ old('discount', $invoice->discount) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit"
                        class="w-full sm:w-1/3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-xs transition-colors h-10">
                        Mark as PAID ({{ number_format($invoice->net_amount, 2) }} EGP)
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
