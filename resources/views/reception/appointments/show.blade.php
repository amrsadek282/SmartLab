@extends('layouts.app')

@section('title', "Appointment #{$appointment->id}")
@section('page-title', "Appointment Details — #{$appointment->id}")

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="{{ route('reception.appointments.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Appointments
        </a>
    </div>

    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Appointment</span>
                <h1 class="text-2xl font-black text-slate-900">#{{ $appointment->id }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Scheduled for <span class="font-bold text-slate-800">{{ $appointment->appointment_date->format('l, d F Y') }}</span> at <span class="font-bold text-slate-800">{{ $appointment->appointment_time }}</span></p>
            </div>

            <div>
                @php
                    $apptBadges = [
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                    ];
                @endphp
                <span class="inline-flex items-center {{ $apptBadges[$appointment->status] ?? 'bg-slate-100' }} border text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Patient Details</p>
                <p class="text-sm font-bold text-slate-900">{{ $appointment->patient?->name ?? 'Guest / Unlinked' }}</p>
                <p class="text-slate-600 mt-1 font-mono">Phone: {{ $appointment->patient?->phone ?? '—' }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Booking Notes</p>
                <p class="text-slate-700">{{ $appointment->notes ?: 'No special notes provided.' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-3 pt-4 border-t border-slate-100">
            @if($appointment->status === 'pending')
                <form method="POST" action="{{ route('reception.appointments.confirm', $appointment) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-colors">
                        Confirm Appointment
                    </button>
                </form>
            @endif

            @if($appointment->patient)
                <a href="{{ route('reception.orders.create', ['patient_id' => $appointment->patient->id, 'appointment_id' => $appointment->id]) }}"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-colors">
                    Create Lab Order &rarr;
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
