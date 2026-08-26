@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => true,
])

@php
    $normalizedType = match ($type) {
        'danger', 'error' => 'error',
        'success' => 'success',
        'warning' => 'warning',
        default => 'info',
    };

    $config = [
        'success' => [
            'wrapper' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/60 text-emerald-900 dark:text-emerald-200',
            'iconBg' => 'text-emerald-600 dark:text-emerald-400',
            'dismiss' => 'text-emerald-500 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 hover:text-emerald-800 dark:hover:text-emerald-200',
        ],
        'error' => [
            'wrapper' => 'bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800/60 text-red-900 dark:text-red-200',
            'iconBg' => 'text-red-600 dark:text-red-400',
            'dismiss' => 'text-red-500 hover:bg-red-100 dark:hover:bg-red-900/50 hover:text-red-800 dark:hover:text-red-200',
        ],
        'warning' => [
            'wrapper' => 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800/60 text-amber-900 dark:text-amber-200',
            'iconBg' => 'text-amber-600 dark:text-amber-400',
            'dismiss' => 'text-amber-500 hover:bg-amber-100 dark:hover:bg-amber-900/50 hover:text-amber-800 dark:hover:text-amber-200',
        ],
        'info' => [
            'wrapper' => 'bg-blue-50 dark:bg-blue-950/40 border-blue-200 dark:border-blue-800/60 text-blue-900 dark:text-blue-200',
            'iconBg' => 'text-blue-600 dark:text-blue-400',
            'dismiss' => 'text-blue-500 hover:bg-blue-100 dark:hover:bg-blue-900/50 hover:text-blue-800 dark:hover:text-blue-200',
        ],
    ][$normalizedType];
@endphp

<div {{ $attributes->merge(['class' => "alert-container flex items-start gap-3 p-4 rounded-xl border text-sm shadow-xs transition-all duration-200 {$config['wrapper']}"]) }} role="alert">
    <div class="shrink-0 mt-0.5 {{ $config['iconBg'] }}">
        @if ($normalizedType === 'success')
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        @elseif ($normalizedType === 'error')
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
        @elseif ($normalizedType === 'warning')
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        @else
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
        @endif
    </div>

    <div class="flex-1">
        @if ($title)
            <h4 class="font-semibold mb-0.5 text-sm">{{ $title }}</h4>
        @endif
        <div class="text-sm leading-relaxed">
            {{ $slot }}
        </div>
    </div>

    @if ($dismissible)
        <button
            type="button"
            onclick="this.closest('.alert-container').remove()"
            class="shrink-0 -mr-1 -mt-1 p-1.5 rounded-lg transition-colors cursor-pointer {{ $config['dismiss'] }}"
            aria-label="Dismiss alert"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
