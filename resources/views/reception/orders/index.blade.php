@extends('layouts.app')

@section('title', 'Lab Orders')
@section('page-title', 'Laboratory Orders')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Laboratory Orders</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage patient test orders, sample collection, results, and WhatsApp delivery.</p>
        </div>

        <a href="{{ route('reception.orders.create') }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-all hover:scale-[1.02] self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create New Order
        </a>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <a href="{{ route('reception.orders.index') }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors whitespace-nowrap {{ empty($status) ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                All Orders
            </a>
            <a href="{{ route('reception.orders.index', ['status' => 'pending']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors whitespace-nowrap {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Pending
            </a>
            <a href="{{ route('reception.orders.index', ['status' => 'in_progress']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors whitespace-nowrap {{ $status === 'in_progress' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                In Progress
            </a>
            <a href="{{ route('reception.orders.index', ['status' => 'completed']) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors whitespace-nowrap {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Completed
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('reception.orders.index') }}" class="w-full md:w-80 flex items-center gap-2">
            @if(!empty($status))
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="relative w-full">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all"
                    placeholder="Search by order #, patient name, phone...">
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-colors shrink-0">
                Search
            </button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3.5">Order #</th>
                        <th class="px-5 py-3.5">Patient</th>
                        <th class="px-5 py-3.5">Tests</th>
                        <th class="px-5 py-3.5">Sample Status</th>
                        <th class="px-5 py-3.5">Lab Status</th>
                        <th class="px-5 py-3.5">Payment</th>
                        <th class="px-5 py-3.5">Created</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <a href="{{ route('reception.orders.show', $order) }}" class="font-mono text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $order->patient->name }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $order->getFormattedPatientPhone() }}</p>
                            </td>
                            <td class="px-5 py-4 text-xs font-medium text-slate-600">
                                {{ $order->order_items_count }} test(s)
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $sampleBadges = [
                                        'pending_collection' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'collected' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'received_in_lab' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center {{ $sampleBadges[$order->sample_status] ?? 'bg-slate-100 text-slate-600' }} border text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                    {{ str_replace('_', ' ', $order->sample_status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusBadges = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center {{ $statusBadges[$order->status] ?? 'bg-slate-100 text-slate-600' }} border text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($order->invoice)
                                    @php
                                        $payBadges = [
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'partially_paid' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'unpaid' => 'bg-red-50 text-red-700 border-red-200',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center {{ $payBadges[$order->invoice->payment_status] ?? 'bg-slate-100' }} border text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                                        {{ str_replace('_', ' ', $order->invoice->payment_status) }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $order->created_at->format('d M, h:i A') }}
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('reception.orders.show', $order) }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                        View
                                    </a>

                                    @if($order->status === 'completed' && $order->hasValidWhatsappPhone())
                                        <a href="{{ route('reports.whatsapp', $order) }}" target="_blank" title="Send Report via WhatsApp"
                                            class="p-1.5 rounded-lg text-[#25D366] hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <p class="text-sm font-medium text-slate-600">No laboratory orders found.</p>
                                <p class="text-xs text-slate-400 mt-0.5">Try adjusting your search criteria or create a new order.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
