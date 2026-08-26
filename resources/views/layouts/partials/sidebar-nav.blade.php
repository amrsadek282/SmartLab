@php
    $user = auth()->user();
    $isAdmin = $user && $user->isAdmin();
    $isReceptionist = $user && $user->isReceptionist();
    $isTechnician = $user && $user->isTechnician();

    $itemClass = function(bool $active) {
        return $active
            ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium bg-blue-600 text-white shadow-sm shadow-blue-600/30 transition-colors'
            : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-colors';
    };
@endphp

<!-- Main Section -->
<div class="space-y-1">
    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Core</p>

    <!-- Dashboard (All Roles) -->
    <a href="{{ route('dashboard') }}" class="{{ $itemClass(request()->routeIs('dashboard*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
        </svg>
        <span>Dashboard</span>
    </a>
</div>

<!-- Reception & Front Desk (Admin & Receptionist) -->
@if ($isAdmin || $isReceptionist)
    <div class="space-y-1">
        <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Reception</p>

        <!-- Patients -->
        <a href="{{ route('reception.patients.index') }}" class="{{ $itemClass(request()->routeIs('reception.patients*')) }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span>Patients</span>
        </a>

        <!-- Appointments -->
        <a href="{{ route('reception.appointments.index') }}" class="{{ $itemClass(request()->routeIs('reception.appointments*')) }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
            </svg>
            <span>Appointments</span>
        </a>

        <!-- Orders -->
        <a href="{{ route('reception.orders.index') }}" class="{{ $itemClass(request()->routeIs('reception.orders*')) }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span>Lab Orders</span>
        </a>

        <!-- Invoices -->
        <a href="{{ route('reception.invoices.index') }}" class="{{ $itemClass(request()->routeIs('reception.invoices*')) }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6H2.25m0 0v10.5m0-10.5h19.5m0 0v10.5m0-10.5a.75.75 0 0 1 .75.75v.75m0 9a.75.75 0 0 1-.75.75H2.25M9 12h6m-6 3h6" />
            </svg>
            <span>Invoices</span>
        </a>
    </div>
@endif

<!-- Laboratory & Testing (Admin & Technician) -->
@if ($isAdmin || $isTechnician)
    <div class="space-y-1">
        <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Laboratory</p>

        <!-- Lab Work Queue -->
        <a href="{{ route('lab.orders.index') }}" class="{{ $itemClass(request()->routeIs('lab.orders*')) }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
            </svg>
            <span>Lab Queue</span>
        </a>
    </div>
@endif

<!-- Administration (Admin Only) -->
@if ($isAdmin)
    <div class="space-y-1">
        <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Admin</p>

        <!-- Staff & Users Management -->
        <a href="{{ route('admin.users.index') }}" class="{{ $itemClass(request()->routeIs('admin.users*')) }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>
            <span>Staff &amp; Users</span>
        </a>

        <!-- Lab Tests Catalog -->
        <a href="{{ route('admin.tests.index') }}" class="{{ $itemClass(request()->routeIs('admin.tests*')) }}">
            <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <span>Test Catalog</span>
        </a>
    </div>
@endif

<!-- My Account -->
<div class="space-y-1">
    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Settings</p>

    <!-- My Profile -->
    <a href="{{ route('profile.show') }}" class="{{ $itemClass(request()->routeIs('profile*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        <span>My Profile</span>
    </a>
</div>

<!-- Public & Quick Links -->
<div class="space-y-1 pt-2 border-t border-slate-800">
    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Portals</p>

    <!-- Online Booking Link -->
    <a href="{{ route('booking.create') }}" target="_blank" class="{{ $itemClass(request()->routeIs('booking*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
        </svg>
        <span class="flex-1">Online Booking</span>
        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
        </svg>
    </a>
</div>
