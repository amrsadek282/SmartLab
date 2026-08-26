@extends('layouts.guest')

@section('content')
<div class="bg-white dark:bg-slate-900 py-8 px-6 shadow-xl shadow-slate-200/50 dark:shadow-black/40 rounded-3xl sm:px-10 border border-slate-200/80 dark:border-slate-800 transition-colors duration-150">
    <!-- Brand Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30 mb-3">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 1-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Sign in to SmartLab</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Smart Laboratory Information &amp; Workflow System</p>
    </div>

    <!-- Error Alert Display -->
    @if ($errors->any())
        <x-alert type="error" class="mb-6">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Email address</label>
            <div class="relative">
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    required
                    autofocus
                    value="{{ old('email') }}"
                    placeholder="staff@smartlab.com"
                    class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl border border-slate-300 dark:border-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-colors duration-150 placeholder-slate-400 dark:placeholder-slate-500 @error('email') border-red-300 dark:border-red-800 focus:border-red-500 focus:ring-red-500/20 @enderror"
                >
            </div>
            @error('email')
                <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
            </div>
            <div class="relative">
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                    placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 pr-10 text-sm bg-white dark:bg-slate-950 text-slate-900 dark:text-white rounded-xl border border-slate-300 dark:border-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-colors duration-150 placeholder-slate-400 dark:placeholder-slate-500 @error('password') border-red-300 dark:border-red-800 focus:border-red-500 focus:ring-red-500/20 @enderror"
                >
                <button
                    type="button"
                    onclick="togglePasswordVisibility()"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 focus:outline-none cursor-pointer"
                    aria-label="Toggle password visibility"
                >
                    <!-- Eye Icon -->
                    <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <!-- Eye Slash Icon (Hidden by default) -->
                    <svg id="eye-slash-icon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500 cursor-pointer"
                >
                <label for="remember" class="ml-2 block text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                    Remember this device
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <x-button type="submit" variant="primary" size="lg" class="w-full">
            Sign In to Account
        </x-button>
    </form>

    <!-- Public Booking Link -->
    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            Need to schedule a medical test as a patient?
            <a href="{{ route('booking.create') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline ml-1 inline-flex items-center gap-0.5">
                Book Online Here &rarr;
            </a>
        </p>
    </div>

    <!-- Demo Credentials Helper (For Fast Testing) -->
    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/60 -mx-6 -mb-8 p-5 rounded-b-3xl">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
                Demo Fast Login (Click to Fill)
            </span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">pwd: password</span>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <button
                type="button"
                onclick="fillCredentials('admin@minilis.com', 'password')"
                class="px-2 py-1.5 bg-white dark:bg-slate-900 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 hover:border-indigo-300 dark:hover:border-indigo-700 border border-slate-200 dark:border-slate-800 rounded-xl text-center transition-all group cursor-pointer shadow-2xs"
            >
                <span class="block text-[11px] font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Admin</span>
                <span class="block text-[9px] text-slate-400 dark:text-slate-500 truncate">admin@minilis.com</span>
            </button>

            <button
                type="button"
                onclick="fillCredentials('receptionist@minilis.com', 'password')"
                class="px-2 py-1.5 bg-white dark:bg-slate-900 hover:bg-sky-50 dark:hover:bg-sky-950/40 hover:border-sky-300 dark:hover:border-sky-700 border border-slate-200 dark:border-slate-800 rounded-xl text-center transition-all group cursor-pointer shadow-2xs"
            >
                <span class="block text-[11px] font-bold text-slate-800 dark:text-slate-200 group-hover:text-sky-600 dark:group-hover:text-sky-400">Receptionist</span>
                <span class="block text-[9px] text-slate-400 dark:text-slate-500 truncate">receptionist@minilis.com</span>
            </button>

            <button
                type="button"
                onclick="fillCredentials('technician@minilis.com', 'password')"
                class="px-2 py-1.5 bg-white dark:bg-slate-900 hover:bg-teal-50 dark:hover:bg-teal-950/40 hover:border-teal-300 dark:hover:border-teal-700 border border-slate-200 dark:border-slate-800 rounded-xl text-center transition-all group cursor-pointer shadow-2xs"
            >
                <span class="block text-[11px] font-bold text-slate-800 dark:text-slate-200 group-hover:text-teal-600 dark:group-hover:text-teal-400">Technician</span>
                <span class="block text-[9px] text-slate-400 dark:text-slate-500 truncate">technician@minilis.com</span>
            </button>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeSlashIcon = document.getElementById('eye-slash-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        }
    }

    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
