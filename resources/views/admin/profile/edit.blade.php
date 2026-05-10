@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Admin Profile</h1>
        <p class="text-sm font-medium text-gray-500 mt-2">Manage your administrative credentials and security settings.</p>
    </div>

    <!-- Profile Information Card -->
    <div class="saas-card">
        <div class="flex items-center gap-4 mb-8">
            <div class="h-16 w-16 rounded-3xl bg-orange-500 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-orange-100">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 leading-none">{{ $user->name }}</h2>
                <p class="text-sm font-medium text-gray-400 mt-1">Administrator Access</p>
            </div>
        </div>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="saas-label">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="saas-input focus:ring-2 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="saas-label">Email Address</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="saas-input focus:ring-2 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-gray-900 text-white px-8 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-800 hover:-translate-y-0.5 active:translate-y-0 transition-all shadow-lg shadow-gray-200">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Security & Password Card -->
    <div class="saas-card">
        <div class="flex items-center gap-3 mb-8">
            <div class="h-10 w-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400">
                <i data-lucide="shield-check" class="h-5 w-5"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Security Credentials</h2>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            <div class="space-y-1.5 max-w-md">
                <label class="saas-label">Current Password</label>
                <input type="password" name="current_password" required class="saas-input focus:ring-2 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="saas-label">New Password</label>
                    <input type="password" name="password" required class="saas-input focus:ring-2 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="saas-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required class="saas-input focus:ring-2 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-between">
                <button type="submit" class="bg-orange-500 text-white px-8 py-2.5 rounded-xl text-sm font-bold hover:bg-orange-600 hover:-translate-y-0.5 active:translate-y-0 transition-all shadow-lg shadow-orange-100">
                    Secure Change Password
                </button>
                <a href="{{ route('password.request') }}" class="text-sm font-bold text-orange-500 hover:text-orange-600">Forgot Password?</a>
            </div>
        </form>
    </div>

    <!-- System Status (Admin Only Info) -->
    <div class="bg-slate-50 rounded-[var(--radius)] p-8 border border-slate-100">
        <div class="flex items-start gap-4">
            <div class="h-12 w-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                <i data-lucide="info" class="h-6 w-6"></i>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-2">Administrative Note</h3>
                <p class="text-sm font-medium text-slate-500 leading-relaxed">As an administrator, your security settings are critical to the system's integrity. Ensure your email is always verified and your password follows high-complexity standards.</p>
            </div>
        </div>
    </div>
</div>
@endsection
