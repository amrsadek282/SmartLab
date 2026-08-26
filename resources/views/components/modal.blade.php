@props([
    'id' => 'modal-' . Str::random(6),
    'title' => null,
    'subtitle' => null,
    'size' => 'md',
    'footer' => null,
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-y-auto bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
>
    <div
        class="relative w-full {{ $sizeClass }} bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all duration-200 scale-95 opacity-0 modal-content my-8 mx-auto"
        onclick="event.stopPropagation()"
    >
        @if ($title)
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-950/40">
                <div>
                    <h3 id="{{ $id }}-title" class="text-base font-semibold text-slate-800 dark:text-white">{{ $title }}</h3>
                    @if ($subtitle)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
                <button
                    type="button"
                    onclick="window.closeModal('{{ $id }}')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 p-1.5 rounded-lg transition-colors cursor-pointer"
                    aria-label="Close modal"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <div class="p-6 text-sm text-slate-700 dark:text-slate-300">
            {{ $slot }}
        </div>

        @if ($footer)
            <div class="px-6 py-4 bg-slate-50/80 dark:bg-slate-950/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

<script>
    if (typeof window.openModal === 'undefined') {
        window.openModal = function(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                const content = modal.querySelector('.modal-content');
                if (content) {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }
            }, 10);
        };

        window.closeModal = function(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            const content = modal.querySelector('.modal-content');
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 150);
        };

        // Close on backdrop click
        document.addEventListener('click', function(e) {
            if (e.target.hasAttribute('aria-modal') && !e.target.classList.contains('hidden')) {
                window.closeModal(e.target.id);
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('[aria-modal="true"]:not(.hidden)');
                openModals.forEach(modal => window.closeModal(modal.id));
            }
        });
    }
</script>
