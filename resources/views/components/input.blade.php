@props([
    'name' => '',
    'type' => 'text',
    'label' => null,
    'placeholder' => '',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'hint' => null,
    'id' => null,
])

@php
    $inputId = $id ?? $name ?? 'input-' . Str::random(6);
    $hasError = $name && $errors->has($name);
    $inputValue = $value ?? ($name ? old($name) : null);

    $inputClasses = 'w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl border transition-colors duration-150 outline-none focus:ring-2 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500 disabled:cursor-not-allowed ' .
        ($hasError
            ? 'border-red-300 dark:border-red-800 focus:border-red-500 focus:ring-red-500/20 text-red-900 dark:text-red-300 placeholder-red-300 dark:placeholder-red-700'
            : 'border-slate-300 dark:border-slate-800 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500/20 placeholder-slate-400 dark:placeholder-slate-500');
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ $inputValue }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => $inputClasses]) }}
        >

        @if ($hasError)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-red-500">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        @endif
    </div>

    @if ($hasError)
        <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">{{ $errors->first($name) }}</p>
    @elseif ($hint)
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $hint }}</p>
    @endif
</div>
