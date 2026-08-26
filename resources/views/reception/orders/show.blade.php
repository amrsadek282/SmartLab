@extends('layouts.app')

@section('title', "Order {$order->order_number}")
@section('page-title', "Order Details — {$order->order_number}")

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Top Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('reception.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to Lab Orders
            </a>
            <div class="flex items-center gap-3 mt-1.5">
                <h1 class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $order->order_number }}</h1>
                <div class="flex items-center gap-2">
                    @php
                        $statusBadges = [
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200/80',
                            'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200/80',
                            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                            'cancelled' => 'bg-red-50 text-red-700 border-red-200/80',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 {{ $statusBadges[$order->status] ?? 'bg-slate-100 text-slate-700' }} border text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full {{ $order->status === 'completed' ? 'bg-emerald-500' : ($order->status === 'in_progress' ? 'bg-blue-500' : 'bg-amber-500') }}"></span>
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Ordered on <span class="font-medium text-slate-700">{{ $order->created_at->format('d M Y, h:i A') }}</span> &middot; Created by <span class="font-medium text-slate-700">{{ $order->creator?->name ?? 'Staff' }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            @if($order->status === 'completed')
                <a href="{{ route('reports.pdf', $order) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    View Report PDF
                </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
                <a href="{{ route('lab.orders.results.edit', $order) }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                    </svg>
                    {{ $order->status === 'completed' ? 'Edit Lab Results' : 'Enter Lab Results' }}
                </a>
            @endif
        </div>
    </div>

    <!-- ========================================== -->
    <!-- PROFESSIONAL ORDER & REPORT TIMELINE       -->
    <!-- ========================================== -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Order Processing & Delivery Timeline
            </h2>
            <span class="text-xs text-slate-400 font-medium">Real-time status tracking</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 relative">
            <!-- Stage 1: Order Created -->
            <div class="relative flex flex-col items-start md:items-center text-left md:text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold mb-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-800">1. Order Created</p>
                <p class="text-[11px] text-emerald-600 font-medium mt-0.5">Completed</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->format('d M, h:i A') }}</p>
            </div>

            <!-- Stage 2: Sample Tracking -->
            @php
                $sampleStage = match($order->sample_status) {
                    'received_in_lab' => ['status' => 'completed', 'label' => 'In Lab', 'color' => 'bg-emerald-500', 'time' => $order->sample_received_at?->format('d M, h:i A')],
                    'collected' => ['status' => 'in_progress', 'label' => 'Collected', 'color' => 'bg-blue-600', 'time' => $order->sample_collected_at?->format('d M, h:i A')],
                    default => ['status' => 'pending', 'label' => 'Pending Collection', 'color' => 'bg-slate-300', 'time' => 'Awaiting collection'],
                };
            @endphp
            <div class="relative flex flex-col items-start md:items-center text-left md:text-center p-3 rounded-xl {{ $sampleStage['status'] === 'completed' ? 'bg-emerald-50/50 border border-emerald-100' : ($sampleStage['status'] === 'in_progress' ? 'bg-blue-50/50 border border-blue-100' : 'bg-slate-50 border border-slate-100') }}">
                <div class="w-8 h-8 rounded-full {{ $sampleStage['color'] }} text-white flex items-center justify-center text-xs font-bold mb-2 shadow-xs">
                    @if($sampleStage['status'] === 'completed')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    @else
                        2
                    @endif
                </div>
                <p class="text-xs font-bold text-slate-800">2. Sample</p>
                <p class="text-[11px] {{ $sampleStage['status'] === 'completed' ? 'text-emerald-600' : ($sampleStage['status'] === 'in_progress' ? 'text-blue-600' : 'text-slate-400') }} font-semibold mt-0.5">{{ $sampleStage['label'] }}</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ $sampleStage['time'] }}</p>
            </div>

            <!-- Stage 3: Lab Analysis -->
            @php
                $labStage = match($order->status) {
                    'completed' => ['status' => 'completed', 'label' => 'All Results Entered', 'color' => 'bg-emerald-500'],
                    'in_progress' => ['status' => 'in_progress', 'label' => 'Testing in Progress', 'color' => 'bg-blue-600'],
                    default => ['status' => 'pending', 'label' => 'Pending Results', 'color' => 'bg-slate-300'],
                };
            @endphp
            <div class="relative flex flex-col items-start md:items-center text-left md:text-center p-3 rounded-xl {{ $labStage['status'] === 'completed' ? 'bg-emerald-50/50 border border-emerald-100' : ($labStage['status'] === 'in_progress' ? 'bg-blue-50/50 border border-blue-100' : 'bg-slate-50 border border-slate-100') }}">
                <div class="w-8 h-8 rounded-full {{ $labStage['color'] }} text-white flex items-center justify-center text-xs font-bold mb-2 shadow-xs">
                    @if($labStage['status'] === 'completed')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    @else
                        3
                    @endif
                </div>
                <p class="text-xs font-bold text-slate-800">3. Lab Results</p>
                <p class="text-[11px] {{ $labStage['status'] === 'completed' ? 'text-emerald-600' : ($labStage['status'] === 'in_progress' ? 'text-blue-600' : 'text-slate-400') }} font-semibold mt-0.5">{{ $labStage['label'] }}</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ $order->orderItems->count() }} Test(s)</p>
            </div>

            <!-- Stage 4: Financial Payment -->
            @php
                $payStatus = $order->invoice?->payment_status ?? 'unpaid';
                $payStage = match($payStatus) {
                    'paid' => ['status' => 'completed', 'label' => 'Fully Paid', 'color' => 'bg-emerald-500'],
                    'partially_paid' => ['status' => 'in_progress', 'label' => 'Partially Paid', 'color' => 'bg-amber-500'],
                    default => ['status' => 'pending', 'label' => 'Unpaid', 'color' => 'bg-slate-300'],
                };
            @endphp
            <div class="relative flex flex-col items-start md:items-center text-left md:text-center p-3 rounded-xl {{ $payStage['status'] === 'completed' ? 'bg-emerald-50/50 border border-emerald-100' : ($payStage['status'] === 'in_progress' ? 'bg-amber-50/50 border border-amber-100' : 'bg-slate-50 border border-slate-100') }}">
                <div class="w-8 h-8 rounded-full {{ $payStage['color'] }} text-white flex items-center justify-center text-xs font-bold mb-2 shadow-xs">
                    @if($payStage['status'] === 'completed')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    @else
                        4
                    @endif
                </div>
                <p class="text-xs font-bold text-slate-800">4. Payment</p>
                <p class="text-[11px] {{ $payStage['status'] === 'completed' ? 'text-emerald-600' : ($payStage['status'] === 'in_progress' ? 'text-amber-600' : 'text-slate-400') }} font-semibold mt-0.5">{{ $payStage['label'] }}</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ number_format($order->invoice?->net_amount ?? 0, 2) }} EGP</p>
            </div>

            <!-- Stage 5: Report Generated -->
            @php
                $hasReport = (bool) $order->report;
                $reportReady = $order->status === 'completed';
            @endphp
            <div class="relative flex flex-col items-start md:items-center text-left md:text-center p-3 rounded-xl {{ $reportReady ? 'bg-emerald-50/50 border border-emerald-100' : 'bg-slate-50 border border-slate-100' }}">
                <div class="w-8 h-8 rounded-full {{ $reportReady ? 'bg-emerald-500' : 'bg-slate-300' }} text-white flex items-center justify-center text-xs font-bold mb-2 shadow-xs">
                    @if($reportReady)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    @else
                        5
                    @endif
                </div>
                <p class="text-xs font-bold text-slate-800">5. Report PDF</p>
                <p class="text-[11px] {{ $reportReady ? 'text-emerald-600' : 'text-slate-400' }} font-semibold mt-0.5">{{ $reportReady ? 'Ready' : 'Not Ready' }}</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ $order->report?->report_number ?? 'Auto-generates' }}</p>
            </div>

            <!-- Stage 6: WhatsApp Delivery -->
            @php
                $whatsappOpened = ! empty($order->report?->whatsapp_opened_at);
            @endphp
            <div class="relative flex flex-col items-start md:items-center text-left md:text-center p-3 rounded-xl {{ $whatsappOpened ? 'bg-emerald-50/50 border border-emerald-100' : ($reportReady ? 'bg-emerald-50/20 border border-emerald-200' : 'bg-slate-50 border border-slate-100') }}">
                <div class="w-8 h-8 rounded-full {{ $whatsappOpened ? 'bg-emerald-500' : ($reportReady ? 'bg-emerald-600' : 'bg-slate-300') }} text-white flex items-center justify-center text-xs font-bold mb-2 shadow-xs">
                    @if($whatsappOpened)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    @else
                        6
                    @endif
                </div>
                <p class="text-xs font-bold text-slate-800">6. WhatsApp</p>
                <p class="text-[11px] {{ $whatsappOpened ? 'text-emerald-600 font-bold' : ($reportReady ? 'text-emerald-700 font-semibold' : 'text-slate-400 font-medium') }} mt-0.5">
                    {{ $whatsappOpened ? 'Link Opened' : ($reportReady ? 'Ready to Send' : 'Pending Results') }}
                </p>
                <p class="text-[10px] text-slate-400 mt-1">{{ $whatsappOpened ? $order->report->whatsapp_opened_at->format('d M, h:i A') : 'Click-to-Chat' }}</p>
            </div>
        </div>
    </div>

    <!-- Main 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ========================================== -->
        <!-- LEFT COLUMN: WHATSAPP, TESTS & RESULTS     -->
        <!-- ========================================== -->
        <div class="lg:col-span-2 space-y-6">

            <!-- ========================================== -->
            <!-- PRIMARY: WHATSAPP REPORT DELIVERY CARD     -->
            <!-- ========================================== -->
            <div class="bg-white border-2 {{ $order->status === 'completed' ? 'border-emerald-200 shadow-sm shadow-emerald-500/5' : 'border-slate-200' }} rounded-2xl p-6 relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 shrink-0">
                            <!-- WhatsApp SVG Icon -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">WhatsApp Report Delivery</h3>
                            <p class="text-xs text-slate-500">Send prefilled medical report notification &amp; secure download link directly to patient</p>
                        </div>
                    </div>

                    @if($order->report?->whatsapp_opened_at)
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200/60 text-xs font-semibold px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            WhatsApp Link Opened ({{ $order->report->whatsapp_open_count }}x)
                        </span>
                    @elseif($order->status === 'completed')
                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200/60 text-xs font-semibold px-3 py-1 rounded-full">
                            Ready to Send
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1 rounded-full">
                            Awaiting Results
                        </span>
                    @endif
                </div>

                <div class="mt-4 space-y-4">
                    <!-- Phone Validation Check & Info -->
                    @php
                        $patientPhone = $order->patient->phone;
                        $normalizedPhone = $order->getNormalizedPatientPhone();
                        $isValidPhone = $order->hasValidWhatsappPhone();
                        $operator = $order->patient->operator_name;
                    @endphp

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            <div>
                                <span class="text-slate-500 font-medium">Patient Phone:</span>
                                <span class="font-bold text-slate-800 ml-1">{{ $order->getFormattedPatientPhone() }}</span>
                                @if($operator)
                                    <span class="ml-1.5 px-2 py-0.5 bg-slate-200 text-slate-700 rounded-md font-semibold text-[10px]">{{ $operator }}</span>
                                @endif
                                @if($normalizedPhone)
                                    <span class="text-[10px] text-slate-400 font-mono ml-1.5">({{ $normalizedPhone }})</span>
                                @endif
                            </div>
                        </div>

                        <button type="button" onclick="document.getElementById('edit-phone-modal').classList.remove('hidden')"
                            class="text-blue-600 hover:text-blue-700 font-bold hover:underline self-start sm:self-auto">
                            Update Phone Number
                        </button>
                    </div>

                    @if(! $isValidPhone)
                        <div class="p-3.5 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            <div>
                                <p class="font-bold">Missing or Invalid Phone Number</p>
                                <p class="mt-0.5">The patient's phone number is either missing or not a valid mobile number. Please update it before sending the WhatsApp report.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons Row -->
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        @if($order->status === 'completed')
                            @if($isValidPhone)
                                <a href="{{ route('reports.whatsapp', $order) }}" target="_blank"
                                    class="inline-flex items-center gap-2.5 bg-[#25D366] hover:bg-[#20bd5a] text-white text-sm font-bold px-6 py-3 rounded-xl shadow-md shadow-emerald-500/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    Send via WhatsApp
                                </a>
                            @else
                                <button type="button" onclick="document.getElementById('edit-phone-modal').classList.remove('hidden')"
                                    class="inline-flex items-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-bold px-5 py-3 rounded-xl transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Add Valid Phone to Send
                                </button>
                            @endif

                            <button type="button" onclick="document.getElementById('preview-message-modal').classList.remove('hidden')"
                                class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-4 py-3 rounded-xl transition-colors">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Preview Message &amp; Link
                            </button>
                        @else
                            <div class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 bg-slate-100 px-4 py-2.5 rounded-xl cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                WhatsApp sending becomes active once all test results are entered.
                            </div>
                        @endif
                    </div>

                    @if($order->report?->whatsapp_opened_at)
                        <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            WhatsApp delivery initiated on <span class="font-semibold text-slate-700">{{ $order->report->whatsapp_opened_at->format('d M Y, h:i A') }}</span>
                            @if($order->report->whatsappOpenedBy)
                                by <span class="font-semibold text-slate-700">{{ $order->report->whatsappOpenedBy->name }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tests & Results Table Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Laboratory Tests &amp; Results</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $order->orderItems->count() }} test item(s) in this order</p>
                    </div>
                    @if($order->status !== 'completed' && (auth()->user()->isAdmin() || auth()->user()->isTechnician()))
                        <a href="{{ route('lab.orders.results.edit', $order) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                            Enter Results &rarr;
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-200/80">
                                <th class="px-6 py-3">Test</th>
                                <th class="px-6 py-3">Result</th>
                                <th class="px-6 py-3">Reference Range</th>
                                <th class="px-6 py-3">Technician</th>
                                <th class="px-6 py-3 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800">{{ $item->test->name }}</p>
                                        <p class="text-xs text-slate-400 font-mono">{{ $item->test->code }} @if($item->test->unit) &middot; {{ $item->test->unit }} @endif</p>
                                    </td>
                                    <td class="px-6 py-4 text-slate-800">
                                        @if($item->result?->result_text)
                                            <span class="font-semibold text-slate-900 whitespace-pre-line">{{ $item->result->result_text }}</span>
                                            @if($item->result->notes)
                                                <p class="text-xs text-slate-400 mt-1 italic">{{ $item->result->notes }}</p>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic">Not entered yet</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 text-xs">
                                        {{ $item->result?->reference_range ?? $item->test->reference_range ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs">
                                        {{ $item->result?->technician?->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(($item->result?->status ?? 'pending') === 'entered')
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                                Entered
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes Card (if any) -->
            @if($order->notes)
                <div class="bg-white border border-slate-200 rounded-2xl p-5 text-sm">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Clinical Notes</h3>
                    <p class="text-slate-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- RIGHT COLUMN: PATIENT, INVOICE, SAMPLE     -->
        <!-- ========================================== -->
        <div class="space-y-6">

            <!-- Patient Profile Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-800">Patient Details</h2>
                    <a href="{{ route('reception.patients.show', $order->patient) }}" class="text-xs text-blue-600 font-bold hover:underline">
                        Profile &rarr;
                    </a>
                </div>

                <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                    <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold text-lg flex items-center justify-center shrink-0 shadow-xs">
                        {{ strtoupper(substr($order->patient->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-900 truncate">{{ $order->patient->name }}</h3>
                        <p class="text-xs text-slate-500">{{ ucfirst($order->patient->gender ?? '—') }} &middot; {{ $order->patient->age ?? '—' }} years</p>
                    </div>
                </div>

                <div class="space-y-3 pt-4 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Phone</span>
                        <span class="font-bold text-slate-800">{{ $order->getFormattedPatientPhone() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">National ID</span>
                        <span class="font-medium text-slate-700">{{ $order->patient->national_id ?? 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Address</span>
                        <span class="font-medium text-slate-700 truncate max-w-[160px]">{{ $order->patient->address ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>

            <!-- Sample Tracking Control Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-800">Sample Tracking</h2>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        {{ str_replace('_', ' ', $order->sample_status) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('reception.orders.update-sample-status', $order) }}" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    @if($order->sample_status === 'pending_collection')
                        <p class="text-xs text-slate-500 mb-2">Sample has not yet been collected from the patient.</p>
                        <input type="hidden" name="sample_status" value="collected">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-colors">
                            Mark Sample as Collected
                        </button>
                    @elseif($order->sample_status === 'collected')
                        <p class="text-xs text-slate-500 mb-2">
                            Collected at <span class="font-semibold text-slate-700">{{ $order->sample_collected_at?->format('h:i A') }}</span>. Awaiting delivery to the lab.
                        </p>
                        <input type="hidden" name="sample_status" value="received_in_lab">
                        <button type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-colors">
                            Mark as Received in Lab
                        </button>
                    @else
                        <div class="p-3 bg-emerald-50 border border-emerald-200/80 rounded-xl text-xs text-emerald-800">
                            <p class="font-bold flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Received in Laboratory
                            </p>
                            <p class="mt-0.5 text-[11px] text-emerald-700">Timestamp: {{ $order->sample_received_at?->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Financial Invoice Summary Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-800">Invoice {{ $order->invoice?->invoice_number }}</h2>
                    @if($order->invoice)
                        @php
                            $invoiceBadge = [
                                'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'partially_paid' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'unpaid' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                        @endphp
                        <span class="inline-flex items-center {{ $invoiceBadge[$order->invoice->payment_status] ?? 'bg-slate-100' }} border text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full">
                            {{ str_replace('_', ' ', $order->invoice->payment_status) }}
                        </span>
                    @endif
                </div>

                @if($order->invoice)
                    @php
                        $remaining = max(0, $order->invoice->net_amount - $order->invoice->paid_amount);
                    @endphp
                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Total Price</span>
                            <span class="font-medium text-slate-800">{{ number_format($order->invoice->total_amount, 2) }} EGP</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Discount</span>
                            <span class="font-medium text-slate-800">- {{ number_format($order->invoice->discount, 2) }} EGP</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-slate-100 text-sm">
                            <span class="text-slate-700 font-bold">Net Total</span>
                            <span class="font-black text-slate-900">{{ number_format($order->invoice->net_amount, 2) }} EGP</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Paid Amount</span>
                            <span class="font-bold text-emerald-600">{{ number_format($order->invoice->paid_amount, 2) }} EGP</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Remaining Due</span>
                            <span class="font-bold text-red-600">{{ number_format($remaining, 2) }} EGP</span>
                        </div>
                    </div>

                    @if($order->invoice->payment_status !== 'paid')
                        <form method="POST" action="{{ route('reception.invoices.pay', $order->invoice) }}" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="payment_method" value="cash">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-colors">
                                Settle Payment (Mark Paid)
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            <!-- Order Status Manual Transition (if needed) -->
            @if(in_array($order->status, ['pending', 'in_progress']))
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-6">
                    <h2 class="text-sm font-bold text-slate-800 mb-3">Order Status Action</h2>
                    <form method="POST" action="{{ route('reception.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')

                        @if($order->status === 'pending')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors">
                                Start Lab Processing
                            </button>
                        @elseif($order->status === 'in_progress')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors">
                                Finalize &amp; Mark as Completed
                            </button>
                        @endif
                    </form>
                </div>
            @endif

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: UPDATE PATIENT PHONE NUMBER         -->
<!-- ========================================== -->
<div id="edit-phone-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Update Patient Phone Number</h3>
            <button type="button" onclick="document.getElementById('edit-phone-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('reception.orders.update-patient-phone', $order) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Egyptian Mobile Number <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone', $order->patient->phone) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono"
                    placeholder="e.g. 01012345678 or +201012345678">
                <p class="text-[11px] text-slate-400 mt-1">Supports Vodafone (010), Etisalat (011), Orange (012), and WE (015).</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('edit-phone-modal').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-xs transition-colors">
                    Save Phone Number
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: PREVIEW WHATSAPP MESSAGE & LINK     -->
<!-- ========================================== -->
@if($order->status === 'completed')
    @php
        $prefilledMessage = \App\Services\WhatsAppReportService::buildMessage($order);
        $publicReportUrl = \App\Services\WhatsAppReportService::getReportUrl($order);
    @endphp
    <div id="preview-message-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl space-y-5 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">WhatsApp Prefilled Message</h3>
                </div>
                <button type="button" onclick="document.getElementById('preview-message-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Message Preview Box -->
            <div class="p-4 bg-emerald-50/50 border border-emerald-200/80 rounded-2xl text-xs font-sans text-slate-800 whitespace-pre-line leading-relaxed shadow-inner">
                {{ $prefilledMessage }}
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Secure Public Download Link</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly id="public-report-url-input" value="{{ $publicReportUrl }}"
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 font-mono">
                    <button type="button" onclick="copyReportUrl()"
                        class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shrink-0 transition-colors">
                        <span id="copy-url-btn-text">Copy Link</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <button type="button" onclick="copyFullMessage()"
                    class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                    <span id="copy-msg-btn-text">Copy Entire Message</span>
                </button>

                @if($isValidPhone)
                    <a href="{{ route('reports.whatsapp', $order) }}" target="_blank"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md shadow-emerald-500/20 transition-all">
                        Launch in WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script>
        function copyReportUrl() {
            const input = document.getElementById('public-report-url-input');
            input.select();
            navigator.clipboard.writeText(input.value);
            const btn = document.getElementById('copy-url-btn-text');
            btn.innerText = 'Copied!';
            setTimeout(() => btn.innerText = 'Copy Link', 2000);
        }

        function copyFullMessage() {
            const message = @json($prefilledMessage);
            navigator.clipboard.writeText(message);
            const btn = document.getElementById('copy-msg-btn-text');
            btn.innerText = 'Message Copied!';
            setTimeout(() => btn.innerText = 'Copy Entire Message', 2000);
        }
    </script>
@endif

@endsection