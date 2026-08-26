@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'footer' => null,
    'header' => null,
    'padding' => 'p-6',
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md hover:border-slate-300/80 dark:hover:border-slate-700']) }}>
    @if ($header || $title)
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-4 bg-slate-50/40 dark:bg-slate-950/40">
            @if ($header)
                {{ $header }}
            @else
                <div>
                    <h3 class="text-base font-semibold text-slate-800 dark:text-white leading-snug">{{ $title }}</h3>
                    @if ($subtitle)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
                @if ($actions)
                    <div class="flex items-center gap-2">
                        {{ $actions }}
                    </div>
                @endif
            @endif
        </div>
    @endif

    <div class="{{ $noPadding ? '' : $padding }} text-slate-800 dark:text-slate-200">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-3.5 bg-slate-50/70 dark:bg-slate-950/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-sm text-slate-600 dark:text-slate-400">
            {{ $footer }}
        </div>
    @endif
</div>
