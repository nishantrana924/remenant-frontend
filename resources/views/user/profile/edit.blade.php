@extends('public.layouts.app')

@section('title', 'Profile Settings - Remenant')

@section('content')
<div class="min-h-screen bg-[#F1F3F6] pt-24 pb-12 px-2 md:px-4">
    <div class="max-w-[1360px] mx-auto">
        <div class="flex flex-col md:flex-row gap-3">
            
            <!-- Sidebar (Same as Dashboard for consistency) -->
            <div class="w-full md:w-[280px] shrink-0 space-y-3">
                <div class="bg-white p-3 shadow-sm flex items-center gap-3 border border-slate-100">
                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                        <i data-lucide="user" class="h-5 w-5"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[9px] text-slate-400 uppercase font-black tracking-tighter">Account of</p>
                        <h2 class="text-xs font-black text-slate-900 truncate uppercase">{{ auth()->user()->name }}</h2>
                    </div>
                </div>

                <div class="bg-white shadow-sm border border-slate-100 divide-y divide-slate-50">
                    <a href="{{ route('my-orders') }}" class="p-4 flex items-center justify-between group hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shopping-bag" class="h-4 w-4 text-slate-400"></i>
                            <span class="text-[11px] font-black uppercase tracking-widest text-slate-900">My Orders</span>
                        </div>
                        <i data-lucide="chevron-right" class="h-3 w-3 text-slate-300 group-hover:text-orange-500 transition-colors"></i>
                    </a>
                    
                    <div class="p-0">
                        <div class="p-4 flex items-center gap-3 bg-slate-50/50">
                            <i data-lucide="user-cog" class="h-4 w-4 text-orange-500"></i>
                            <span class="text-[11px] font-black uppercase tracking-widest text-slate-900">Account Settings</span>
                        </div>
                        <div class="py-2">
                            <a href="{{ route('profile.edit') }}" class="block px-12 py-2 text-[10px] font-black text-orange-500 bg-slate-50 uppercase">Profile Information</a>
                            <a href="#" class="block px-12 py-2 text-[10px] font-bold text-slate-500 hover:bg-slate-50 hover:text-orange-500 transition-all uppercase">Manage Addresses</a>
                        </div>
                    </div>

                    <div class="p-4 border-t border-slate-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 text-rose-500 hover:opacity-80 transition-all w-full text-left">
                                <i data-lucide="log-out" class="h-4 w-4"></i>
                                <span class="text-[11px] font-black uppercase tracking-widest">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content Area (Profile Edit) -->
            <div class="flex-1 space-y-4">
                <div class="bg-white p-6 md:p-10 shadow-sm border border-slate-100">
                    <h3 class="text-xl font-black italic uppercase tracking-tighter text-slate-900 mb-8">Profile Information</h3>
                    
                    <form method="post" action="{{ route('profile.update') }}" class="max-w-xl space-y-6">
                        @csrf
                        @method('patch')

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                            @error('name') <p class="text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                            @error('email') <p class="text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                            @error('phone') <p class="text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" class="bg-slate-900 text-white px-10 py-3 text-[10px] font-black uppercase tracking-widest hover:brightness-110 transition-all shadow-lg">
                                Save Changes
                            </button>
                            @if (session('status') === 'profile-updated')
                                <p class="text-[10px] font-black text-emerald-500 uppercase">Saved Successfully</p>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Password Update Section -->
                <div class="bg-white p-6 md:p-10 shadow-sm border border-slate-100">
                    <h3 class="text-xl font-black italic uppercase tracking-tighter text-slate-900 mb-8">Security & Password</h3>
                    
                    <form method="post" action="{{ route('password.update') }}" class="max-w-xl space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Password</label>
                            <input type="password" name="current_password"
                                class="w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                            @error('current_password') <p class="text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">New Password</label>
                            <input type="password" name="password"
                                class="w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                            @error('password') <p class="text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="bg-slate-900 text-white px-10 py-3 text-[10px] font-black uppercase tracking-widest hover:brightness-110 transition-all shadow-lg">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Danger Zone: Delete Account -->
                <div class="bg-white p-6 md:p-10 shadow-sm border border-rose-100">
                    <div class="flex items-center gap-3 mb-8">
                        <i data-lucide="alert-triangle" class="h-5 w-5 text-rose-500"></i>
                        <h3 class="text-xl font-black italic uppercase tracking-tighter text-slate-900">Danger Zone</h3>
                    </div>
                    
                    <p class="text-[11px] text-slate-500 font-bold uppercase mb-8">Once you delete your account, all of your resources and data will be permanently deleted.</p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="max-w-xl">
                        @csrf
                        @method('delete')

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Type your password to confirm deletion</label>
                            <input type="password" name="password" required
                                class="w-full bg-rose-50 border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-rose-500/20">
                            @error('password', 'userDeletion') <p class="text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-8">
                            <button type="submit" class="bg-rose-500 text-white px-10 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-100">
                                Delete Permanently
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
