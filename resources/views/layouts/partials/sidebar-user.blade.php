@auth
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 min-w-0 group" title="View & Edit Profile">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 group-hover:from-blue-500 group-hover:to-indigo-500 text-white font-bold text-sm flex items-center justify-center shrink-0 ring-2 ring-slate-800 shadow-sm transition-all">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-white group-hover:text-blue-300 transition-colors truncate">{{ auth()->user()->name }}</p>
                <div class="mt-0.5">
                    <x-badge :role="auth()->user()->role" size="sm">
                        {{ auth()->user()->role }}
                    </x-badge>
                </div>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="shrink-0">
            @csrf
            <button
                type="submit"
                title="Sign out"
                class="p-2 rounded-xl text-slate-400 hover:text-red-400 hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 cursor-pointer"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
            </button>
        </form>
    </div>
@endauth
