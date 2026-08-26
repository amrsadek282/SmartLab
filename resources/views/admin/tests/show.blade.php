@extends('layouts.app')

@section('title', "Test: {$test->name}")
@section('page-title', "Test Catalog — {$test->name}")

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.tests.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Test Catalog
        </a>
    </div>

    <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-mono">{{ $test->code }}</span>
                <h1 class="text-2xl font-black text-slate-900">{{ $test->name }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Category: <span class="font-bold text-slate-800">{{ $test->category ?? 'General' }}</span></p>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-lg font-black text-blue-700 font-mono">{{ number_format($test->price, 2) }} EGP</span>
                <span class="inline-flex items-center {{ $test->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} border text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                    {{ $test->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Standard Reference Range</p>
                <p class="text-sm font-bold text-slate-900">{{ $test->reference_range ?? 'Not specified' }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Measurement Unit</p>
                <p class="text-sm font-bold text-slate-900">{{ $test->unit ?? 'N/A' }}</p>
            </div>
        </div>

        @if($test->description)
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Description &amp; Guidelines</p>
                <p class="text-slate-700 leading-relaxed">{{ $test->description }}</p>
            </div>
        @endif

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.tests.edit', $test) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-xs transition-colors">
                Edit Test
            </a>
        </div>
    </div>
</div>
@endsection
