@extends('layouts.app')

@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Appointment Management</h2>
            <p class="text-sm text-slate-500 mt-0.5">Review, confirm, and manage incoming lab appointment requests.</p>
        </div>

        <x-button href="{{ route('booking.create') }}" target="_blank" variant="outline" size="md">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            Public Booking Page
        </x-button>
    </div>

    <!-- Status Filter Tabs -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-1.5 inline-flex flex-wrap gap-1">
        @php
            $tabs = [
                null => 'All',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'cancelled' => 'Cancelled',
                'completed' => 'Completed',
            ];
        @endphp

        @foreach ($tabs as $value => $label)
            <a
                href="{{ route('reception.appointments.index', $value ? ['status' => $value] : []) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ (string) $status === (string) $value ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'text-slate-600 hover:bg-slate-100' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Appointments Table -->
    <x-table
        :headers="['Patient', 'Phone', 'Date', 'Time', 'Test', 'Status', 'Notes', 'Actions']"
        :empty="$appointments->isEmpty()"
        :empty-message="$status ? 'No '.$status.' appointments found.' : 'No appointments found.'"
    >
        @foreach ($appointments as $appointment)
            <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($appointment->patient_name, 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-800 block">
                                @if ($appointment->patient)
                                    <a href="{{ route('reception.patients.show', $appointment->patient) }}" class="hover:text-blue-600 transition-colors">{{ $appointment->patient_name }}</a>
                                @else
                                    {{ $appointment->patient_name }}
                                @endif
                            </span>
                            <span class="text-[11px] text-slate-400">Appt #{{ $appointment->id }}</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $appointment->phone }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $appointment->appointment_date->format('M d, Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $appointment->appointment_date->format('h:i A') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $appointment->test?->name ?? 'Not specified' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-badge :status="$appointment->status" size="sm" />
                </td>
                <td class="px-6 py-4 text-sm text-slate-500 max-w-[14rem] truncate" title="{{ $appointment->status === 'cancelled' ? $appointment->cancellation_reason : $appointment->notes }}">
                    @if ($appointment->status === 'cancelled' && $appointment->cancellation_reason)
                        <span class="text-red-600">Cancelled: {{ $appointment->cancellation_reason }}</span>
                    @else
                        {{ $appointment->notes ?? '—' }}
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        @if ($appointment->status === 'pending')
                            <form method="POST" action="{{ route('reception.appointments.confirm', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <x-button type="submit" variant="success" size="sm">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    Confirm
                                </x-button>
                            </form>
                        @endif

                        @if ($appointment->status === 'confirmed')
                            <x-button href="{{ route('reception.orders.create', ['appointment_id' => $appointment->id]) }}" variant="primary" size="sm">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Create Lab Order
                            </x-button>
                        @endif

                        @if (in_array($appointment->status, ['pending', 'confirmed']))
                            <x-button
                                type="button"
                                variant="outline"
                                size="sm"
                                onclick="window.openModal('cancel-appointment-{{ $appointment->id }}')"
                            >
                                Cancel
                            </x-button>

                            <x-modal
                                id="cancel-appointment-{{ $appointment->id }}"
                                title="Cancel Appointment #{{ $appointment->id }}"
                                subtitle="This will notify reception that the appointment was cancelled."
                            >
                                <form method="POST" action="{{ route('reception.appointments.cancel', $appointment) }}" id="cancel-form-{{ $appointment->id }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm space-y-1">
                                        <p><span class="font-semibold text-slate-700">Patient:</span> {{ $appointment->patient_name }}</p>
                                        <p><span class="font-semibold text-slate-700">Date &amp; Time:</span> {{ $appointment->appointment_date->format('M d, Y \a\t h:i A') }}</p>
                                        <p><span class="font-semibold text-slate-700">Test:</span> {{ $appointment->test?->name ?? 'Not specified' }}</p>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label for="cancellation_reason_{{ $appointment->id }}" class="block text-sm font-medium text-slate-700">
                                            Cancellation Reason <span class="text-red-500 ml-0.5">*</span>
                                        </label>
                                        <textarea
                                            id="cancellation_reason_{{ $appointment->id }}"
                                            name="cancellation_reason"
                                            rows="3"
                                            required
                                            placeholder="Explain why this appointment is being cancelled..."
                                            class="w-full px-3.5 py-2.5 text-sm bg-white text-slate-900 rounded-xl border border-slate-300 transition-colors duration-150 outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500/20"
                                        ></textarea>
                                    </div>
                                </form>

                                <x-slot:footer>
                                    <x-button type="button" variant="secondary" onclick="window.closeModal('cancel-appointment-{{ $appointment->id }}')">
                                        Close
                                    </x-button>
                                    <x-button type="submit" form="cancel-form-{{ $appointment->id }}" variant="danger">
                                        Confirm Cancellation
                                    </x-button>
                                </x-slot:footer>
                            </x-modal>
                        @endif

                        <a href="{{ route('reception.appointments.show', $appointment) }}" title="View details" class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach

        <x-slot:pagination>
            {{ $appointments->links() }}
        </x-slot:pagination>
    </x-table>
</div>
@endsection