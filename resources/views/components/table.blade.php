@props([
    'headers' => [],
    'striped' => false,
    'hoverable' => true,
    'empty' => false,
    'emptyMessage' => 'No records found.',
])

<div {{ $attributes->merge(['class' => 'w-full overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs']) }}>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            @if (isset($header) || count($headers) > 0)
                <thead class="bg-slate-50/80 dark:bg-slate-950/60 border-b border-slate-200/80 dark:border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    @if (isset($header))
                        {{ $header }}
                    @else
                        <tr>
                            @foreach ($headers as $headerText)
                                <th scope="col" class="px-6 py-3.5 whitespace-nowrap">
                                    {{ $headerText }}
                                </th>
                            @endforeach
                        </tr>
                    @endif
                </thead>
            @endif

            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-slate-700 dark:text-slate-300">
                @if ($empty)
                    <tr>
                        <td colspan="{{ count($headers) > 0 ? count($headers) : 10 }}" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 mb-2 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $emptyMessage }}</span>
                            </div>
                        </td>
                    </tr>
                @else
                    {{ $slot }}
                @endif
            </tbody>
        </table>
    </div>

    @if (isset($pagination))
        <div class="px-6 py-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40">
            {{ $pagination }}
        </div>
    @endif
</div>
