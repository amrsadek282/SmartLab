@extends('layouts.app')

@section('title', 'My Profile & Account Settings')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Profile Header Banner -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-2xl flex items-center justify-center shadow-md shadow-blue-500/20 shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $user->name }}</h2>
                        <x-badge :role="$user->role" size="sm">
                            {{ $user->role }}
                        </x-badge>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">SmartLab staff account active since {{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>

            <!-- Role Badge Information -->
            <div class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-950 p-3 rounded-2xl border border-slate-100 dark:border-slate-800">
                <span class="font-bold text-slate-700 dark:text-slate-300 block">Access Level:</span>
                <span class="capitalize">{{ $user->role }} &bull; Full Departmental Access</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 1. Personal Information Form -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col justify-between">
            <div class="space-y-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Personal Details</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Update your display name, contact email, and phone number.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="profile_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="profile_name" required value="{{ old('name', $user->name) }}"
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="profile_email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="profile_email" required value="{{ old('email', $user->email) }}"
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="profile_phone" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Phone Number
                        </label>
                        <input type="text" name="phone" id="profile_phone" value="{{ old('phone', $user->phone) }}"
                            placeholder="01012345678"
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold py-2.5 rounded-xl shadow-sm shadow-blue-600/20 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Change Password Form -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col justify-between">
            <div class="space-y-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Security &amp; Password</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ensure your account is using a secure password (minimum 8 characters).</p>
                </div>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Current Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="current_password" id="current_password" required
                            placeholder="Enter current password"
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="new_password" required minlength="8"
                            placeholder="Minimum 8 characters"
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Confirm New Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="new_password_confirmation" required minlength="8"
                            placeholder="Re-enter new password"
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>

                    <div class="pt-3">
                        <button type="submit"
                            class="w-full bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white text-sm font-semibold py-2.5 rounded-xl shadow-xs transition-colors cursor-pointer">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
