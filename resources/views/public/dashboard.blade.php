@extends('public.layouts.app')

@section('title', 'My Account - Remenant')

@section('content')
<!-- Flush Edge-to-Edge Container -->
<div class="w-full bg-[#F1F3F6] pt-0 dashboard-root">
    <div class="w-full flex flex-col md:flex-row min-h-screen pt-0">
        
        <!-- Sidebar (Fixed Style) -->
        <div class="w-full md:w-[260px] bg-white border-r border-slate-200 shrink-0 min-h-[calc(100vh-80px)]">
            <!-- Profile Block -->
            <div class="p-6 border-b border-slate-100 flex items-center gap-3 bg-slate-50/20">
                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                    <i data-lucide="user" class="h-5 w-5"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] text-slate-400 uppercase font-black tracking-widest">Logged in as</p>
                    <h2 class="text-xs font-black text-slate-900 truncate uppercase">{{ auth()->user()->name }}</h2>
                </div>
            </div>

            <!-- Navigation Switcher -->
            <div class="flex flex-col" id="dashboard-nav">
                <button onclick="switchTab('orders', this)" class="nav-link px-6 py-4 flex items-center gap-4 {{ $activeTab === 'orders' ? 'active bg-orange-50 text-orange-600 border-r-4 border-orange-500' : '' }} transition-all">
                    <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                    <span class="text-[11px] font-black uppercase tracking-widest text-left">My Orders</span>
                </button>
                
                <div class="border-b border-slate-50">
                    <div class="px-6 py-4 flex items-center gap-4 text-slate-400">
                        <i data-lucide="user-cog" class="h-4 w-4"></i>
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-900">Settings</span>
                    </div>
                    <div class="pb-2">
                        <button onclick="switchTab('profile', this)" class="nav-link block w-full text-left px-14 py-2 text-[10px] font-bold {{ $activeTab === 'profile' ? 'active text-orange-500' : 'text-slate-500' }} hover:text-orange-500 transition-all uppercase">Profile Information</button>
                        <button onclick="switchTab('addresses', this)" class="nav-link block w-full text-left px-14 py-2 text-[10px] font-bold {{ $activeTab === 'addresses' ? 'active text-orange-500' : 'text-slate-500' }} hover:text-orange-500 transition-all uppercase">My Addresses</button>
                    </div>
                </div>

                <div class="border-b border-slate-50">
                    <div class="px-6 py-4 flex items-center gap-4 text-slate-400">
                        <i data-lucide="wallet" class="h-4 w-4"></i>
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-900">Wallet</span>
                    </div>
                    <div class="pb-2">
                        <button onclick="switchTab('giftcards', this)" class="nav-link block w-full text-left px-14 py-2 text-[10px] font-bold {{ $activeTab === 'giftcards' ? 'active text-orange-500' : 'text-slate-500' }} hover:text-orange-500 transition-all uppercase">Gift Cards</button>
                        <button onclick="switchTab('payments', this)" class="nav-link block w-full text-left px-14 py-2 text-[10px] font-bold {{ $activeTab === 'payments' ? 'active text-orange-500' : 'text-slate-500' }} hover:text-orange-500 transition-all uppercase">Saved UPI</button>
                    </div>
                </div>

                <div class="p-6 mt-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-4 text-rose-500 hover:opacity-80 transition-all w-full text-left">
                            <i data-lucide="log-out" class="h-4 w-4"></i>
                            <span class="text-[11px] font-black uppercase tracking-widest">Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 bg-white min-h-[calc(100vh-80px)] relative overflow-hidden">
            
            <!-- SECTION: ORDERS -->
            <div id="section-orders" class="tab-section {{ $activeTab !== 'orders' ? 'hidden' : '' }} transition-all duration-300">
                <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row items-center gap-6 justify-between bg-white sticky top-0 z-10">
                    <h3 class="text-lg font-black uppercase tracking-tighter text-slate-900">Order Intelligence</h3>
                    <div class="relative w-full md:w-72">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-300"></i>
                        <input type="text" placeholder="Search orders..." class="w-full bg-slate-50 border border-slate-100 pl-10 pr-4 py-2 text-[10px] font-bold outline-none rounded-sm">
                    </div>
                </div>
                <div class="divide-y divide-slate-50 bg-white">
                    @forelse($orders as $order)
                    <div class="px-6 py-6 md:px-10 hover:bg-slate-50 transition-all group">
                        <div class="flex flex-col md:flex-row items-center gap-6 md:gap-10">
                            <div class="h-14 w-14 bg-white p-2 flex items-center justify-center border border-slate-100 rounded-lg shrink-0">
                                <i data-lucide="package" class="h-7 w-7 text-slate-200"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-4 mb-1">
                                    <h4 class="text-[12px] font-black text-slate-900 uppercase">Order #{{ $order->order_number }}</h4>
                                    <span class="text-xs font-black text-slate-900">₹{{ number_format($order->total_amount) }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                                    <span class="h-3 w-px bg-slate-200"></span>
                                    <span class="text-emerald-600 font-black">{{ strtoupper($order->payment_status) }}</span>
                                </div>
                            </div>
                            <div class="w-full md:w-48 flex flex-col md:items-end gap-1.5 shrink-0 pt-4 md:pt-0 border-t md:border-t-0 border-slate-50">
                                <span class="text-[9px] font-black text-orange-500 bg-orange-50 px-3 py-1 rounded-full uppercase">
                                    {{ $order->delivery_status ?? 'Processing' }}
                                </span>
                                <div class="flex items-center gap-3 mt-1">
                                    <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="text-[9px] font-black text-slate-400 hover:text-orange-500 uppercase flex items-center gap-1">
                                        <i data-lucide="navigation" class="h-2.5 w-2.5"></i> Track
                                    </a>
                                    <span class="h-2 w-px bg-slate-200"></span>
                                    <a href="#" class="text-[9px] font-black text-slate-400 hover:text-slate-900 uppercase flex items-center gap-1">
                                        <i data-lucide="download" class="h-2.5 w-2.5"></i> Invoice
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-32 text-center"><i data-lucide="package-x" class="h-10 w-10 text-slate-100 mx-auto mb-4"></i><p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">No history found</p></div>
                    @endforelse
                </div>
            </div>

            <!-- SECTION: PROFILE -->
            <div id="section-profile" class="tab-section {{ $activeTab !== 'profile' ? 'hidden' : '' }} p-10 transition-all duration-300">
                <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900 mb-8">Profile Intelligence</h3>
                <form method="post" action="{{ route('profile.update') }}" class="max-w-xl space-y-6">
                    @csrf @method('patch')
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email Address</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                    </div>
                    <div class="pt-4 flex items-center justify-between">
                        <button type="submit" class="bg-slate-900 text-white px-10 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all">Save Changes</button>
                    </div>
                </form>

                <div class="h-px bg-slate-50 my-12"></div>

                <!-- SUBSECTION: PASSWORD -->
                <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900 mb-8">Security & Password</h3>
                <form method="post" action="{{ route('password.update') }}" class="max-w-xl space-y-6">
                    @csrf @method('put')
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" class="password-input w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                            <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i data-lucide="eye" class="h-4 w-4 eye-icon"></i>
                                <i data-lucide="eye-off" class="h-4 w-4 eye-off-icon hidden"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">New Password</label>
                            <button type="button" onclick="openForgotPasswordFlow('{{ auth()->user()->email }}')" class="text-[9px] font-black text-orange-500 uppercase tracking-widest hover:underline">Forgot Password?</button>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" class="password-input w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                            <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i data-lucide="eye" class="h-4 w-4 eye-icon"></i>
                                <i data-lucide="eye-off" class="h-4 w-4 eye-off-icon hidden"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" class="password-input w-full bg-[#F1F3F6] border-none px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-orange-500/20">
                            <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i data-lucide="eye" class="h-4 w-4 eye-icon"></i>
                                <i data-lucide="eye-off" class="h-4 w-4 eye-off-icon hidden"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-slate-900 text-white px-10 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all">Update Password</button>
                    </div>
                </form>

                <div class="h-px bg-slate-50 my-12"></div>

                <!-- SUBSECTION: DELETE ACCOUNT -->
                <div class="bg-rose-50/50 border border-rose-100 p-8 rounded-sm">
                    <h3 class="text-lg font-black uppercase tracking-tighter text-rose-600 mb-2">Delete Account</h3>
                    <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-6 leading-relaxed">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                    
                    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                        @csrf @method('delete')
                        <div class="max-w-md space-y-2">
                            <input type="password" name="password" placeholder="Enter password to confirm" class="password-input w-full bg-white border border-rose-100 px-6 py-3 text-xs font-bold outline-none rounded-sm focus:ring-1 focus:ring-rose-500/20">
                        </div>
                        <button type="submit" class="bg-rose-600 text-white px-10 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-rose-700 transition-all">Delete Account Permanently</button>
                    </form>
                </div>
            </div>
            </div>

            <!-- SECTION: ADDRESSES -->
            <div id="section-addresses" class="tab-section {{ $activeTab !== 'addresses' ? 'hidden' : '' }} p-10 transition-all duration-300">
                <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900 mb-8">Manage Addresses</h3>
                <div class="bg-slate-50 border border-dashed border-slate-200 p-12 text-center rounded-sm">
                    <i data-lucide="map-pin" class="h-10 w-10 text-slate-200 mx-auto mb-4"></i>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">No addresses saved yet.</p>
                </div>
            </div>

            <!-- SECTION: GIFTCARDS -->
            <div id="section-giftcards" class="tab-section {{ $activeTab !== 'giftcards' ? 'hidden' : '' }} p-10 transition-all duration-300">
                <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900 mb-8">Gift Cards</h3>
                <div class="bg-orange-500 p-8 rounded-2xl text-white shadow-xl shadow-orange-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Current Balance</p>
                        <h4 class="text-4xl font-black mt-2">₹0.00</h4>
                    </div>
                    <i data-lucide="gift" class="h-16 w-16 opacity-20"></i>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
    main { padding: 0 !important; margin: 0 !important; max-width: none !important; }
    .nav-link.active { background: #FFF7ED; color: #F97316; border-right: 4px solid #F97316; }
    .nav-link:not(.active) { color: #64748B; border-right: 4px solid transparent; }
</style>

<script>
    function switchTab(tabId, el) {
        // Hide all sections
        document.querySelectorAll('.tab-section').forEach(s => s.classList.add('hidden'));
        // Show target section
        document.getElementById('section-' + tabId).classList.remove('hidden');
        
        // Update nav links
        document.querySelectorAll('.nav-link').forEach(l => {
            l.classList.remove('active', 'bg-orange-50', 'text-orange-600', 'border-r-4', 'border-orange-500');
        });
        el.classList.add('active', 'bg-orange-50', 'text-orange-600', 'border-r-4', 'border-orange-500');
        
        // Update URL without refresh
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.pushState({}, '', url);
        
        if(window.lucide) lucide.createIcons();
    }

    // Toggle Password Visibility
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-password')) {
            const btn = e.target.closest('.toggle-password');
            const input = btn.parentElement.querySelector('.password-input');
            const eye = btn.querySelector('.eye-icon');
            const eyeOff = btn.querySelector('.eye-off-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }
    });
</script>
@endsection
