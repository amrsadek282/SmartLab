@extends('layouts.app')

@section('title', 'Add New Staff Member')
@section('page-title', 'Add New Staff Member')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb & Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Staff List
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Create Staff Account</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Register a new laboratory staff member with appropriate system permissions.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                    placeholder="e.g. Dr. Youssef Mohamed"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email & Phone Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        placeholder="staff@smartlab.com"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Phone Number
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        placeholder="01012345678"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                    System Role <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all {{ old('role', 'receptionist') === 'receptionist' ? 'border-blue-600 bg-blue-50/40 dark:bg-blue-950/30' : 'border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/40 hover:border-slate-300 dark:hover:border-slate-700' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">Receptionist</span>
                            <input type="radio" name="role" value="receptionist" class="h-4 w-4 text-blue-600 focus:ring-blue-500" @checked(old('role', 'receptionist') === 'receptionist')>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Patients, orders, appointments &amp; billing</span>
                    </label>

                    <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all {{ old('role') === 'technician' ? 'border-teal-600 bg-teal-50/40 dark:bg-teal-950/30' : 'border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/40 hover:border-slate-300 dark:hover:border-slate-700' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">Lab Technician</span>
                            <input type="radio" name="role" value="technician" class="h-4 w-4 text-teal-600 focus:ring-teal-500" @checked(old('role') === 'technician')>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Lab queue, test results entry &amp; reports</span>
                    </label>

                    <label class="relative flex flex-col p-4 rounded-2xl border cursor-pointer transition-all {{ old('role') === 'admin' ? 'border-indigo-600 bg-indigo-50/40 dark:bg-indigo-950/30' : 'border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/40 hover:border-slate-300 dark:hover:border-slate-700' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">Administrator</span>
                            <input type="radio" name="role" value="admin" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" @checked(old('role') === 'admin')>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Full system access, staff, catalog &amp; settings</span>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password & Confirmation -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100 dark:border-slate-800">
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required minlength="8"
                        placeholder="Minimum 8 characters"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                        placeholder="Re-enter password"
                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <!-- Active Status Checkbox -->
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', true))
                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <label for="is_active" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                    Account is Active (Allow login and system access)
                </label>
            </div>

            <!-- Submit Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.users.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                    Save Staff Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
