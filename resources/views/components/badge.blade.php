@props([
    'status' => null,
    'role' => null,
    'variant' => null,
    'size' => 'md',
    'dot' => false,
])

@php
    $key = strtolower((string) ($status ?? $role ?? $variant ?? 'default'));

    $styles = [
        // Statuses
        'pending' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/50 ring-amber-500/20',
        'confirmed' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200/80 dark:border-blue-800/50 ring-blue-500/20',
        'in_progress' => 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border-purple-200/80 dark:border-purple-800/50 ring-purple-500/20',
        'completed' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/50 ring-emerald-500/20',
        'cancelled' => 'bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-300 border-red-200/80 dark:border-red-800/50 ring-red-500/20',
        'paid' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/50 ring-emerald-500/20',
        'unpaid' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-800/50 ring-rose-500/20',
        'partial' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/50 ring-amber-500/20',

        // Roles
        'admin' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200/80 dark:border-indigo-800/50 ring-indigo-500/20',
        'receptionist' => 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border-sky-200/80 dark:border-sky-800/50 ring-sky-500/20',
        'technician' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200/80 dark:border-teal-800/50 ring-teal-500/20',

        // Generic Variants
        'primary' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200/80 dark:border-blue-800/50 ring-blue-500/20',
        'success' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/50 ring-emerald-500/20',
        'warning' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-800/50 ring-amber-500/20',
        'danger' => 'bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-300 border-red-200/80 dark:border-red-800/50 ring-red-500/20',
        'info' => 'bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 border-cyan-200/80 dark:border-cyan-800/50 ring-cyan-500/20',
        'slate' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 ring-slate-500/20',
        'default' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 ring-slate-500/20',
    ];

    $dotColors = [
        'pending' => 'bg-amber-500',
        'confirmed' => 'bg-blue-500',
        'in_progress' => 'bg-purple-500',
        'completed' => 'bg-emerald-500',
        'cancelled' => 'bg-red-500',
        'paid' => 'bg-emerald-500',
        'unpaid' => 'bg-rose-500',
        'partial' => 'bg-amber-500',
        'admin' => 'bg-indigo-500',
        'receptionist' => 'bg-sky-500',
        'technician' => 'bg-teal-500',
        'default' => 'bg-slate-400',
    ];

    $sizeClasses = [
        'sm' => 'text-[11px] px-2 py-0.5 rounded-md gap-1 font-medium',
        'md' => 'text-xs px-2.5 py-1 rounded-lg gap-1.5 font-semibold',
        'lg' => 'text-sm px-3 py-1.5 rounded-xl gap-2 font-semibold',
    ];

    $styleClass = $styles[$key] ?? $styles['default'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $dotColor = $dotColors[$key] ?? $dotColors['default'];

    $defaultLabel = str_replace('_', ' ', Str::title($key));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center border shadow-xs transition-colors {$styleClass} {$sizeClass}"]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
    @endif
    {{ $slot->isNotEmpty() ? $slot : $defaultLabel }}
</span>
