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
                <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row items-center gap-6 justify-between bg-white sticky top-[72px] lg:top-[80px] z-10">
                    <h3 class="text-lg font-black uppercase tracking-tighter text-slate-900">Order Intelligence</h3>
                    <div class="relative w-full md:w-72">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-300"></i>
                        <input type="text" placeholder="Search orders..." class="w-full bg-slate-50 border border-slate-100 pl-10 pr-4 py-2 text-[10px] font-bold outline-none rounded-sm">
                    </div>
                </div>
                <div class="divide-y divide-slate-50 bg-white">
                    @forelse($orders as $order)
                    <div class="px-4 sm:px-6 py-6 md:px-10 hover:bg-slate-50 transition-all group">
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

                <div class="h-px bg-slate-50 my-12"></div>                <!-- SUBSECTION: DEACTIVATE ACCOUNT -->
                <div class="mt-12 bg-rose-50/30 border border-rose-100 p-8 rounded-2xl relative overflow-hidden group">
                    <div class="absolute -right-8 -bottom-8 opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
                        <i data-lucide="user-x" class="h-48 w-48 text-rose-900"></i>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-lg font-black uppercase tracking-tight text-rose-600 mb-2">Deactivate Account</h3>
                        <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-8 leading-relaxed max-w-md">Your account will be hidden and you will be logged out. All your data and order history is preserved. You can reactivate instantly by logging back in.</p>
                        
                        <form method="post" action="{{ route('profile.destroy') }}" class="flex flex-col md:flex-row items-end gap-4">
                            @csrf @method('delete')
                            <div class="w-full md:w-80 space-y-2">
                                <label class="text-[9px] font-black text-rose-300 uppercase tracking-widest ml-1">Confirm with Password</label>
                                <input type="password" name="password" placeholder="••••••••" class="password-input w-full bg-white border border-rose-100 px-6 py-3 text-xs font-bold outline-none rounded-xl focus:ring-2 focus:ring-rose-500/10">
                            </div>
                            <button type="submit" class="w-full md:w-auto bg-rose-600 text-white px-8 py-3.5 text-[10px] font-black uppercase tracking-widest hover:bg-rose-700 transition-all rounded-xl shadow-lg shadow-rose-100">Deactivate Now</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SECTION: ADDRESSES -->
            <div id="section-addresses" class="tab-section {{ $activeTab !== 'addresses' ? 'hidden' : '' }} p-6 md:p-10 transition-all duration-300">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900">Manage Addresses</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Primary delivery locations</p>
                    </div>
                    <button onclick="openAddressModal()" class="bg-slate-900 text-white px-6 py-3 text-[9px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all rounded-xl flex items-center gap-2">
                        <i data-lucide="plus" class="h-3 w-3"></i> Add New Address
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="addresses-container">
                    @forelse($addresses as $address)
                    <div class="border-2 {{ $address->is_default ? 'border-orange-500 shadow-xl shadow-orange-50' : 'border-slate-100 hover:border-slate-200' }} bg-white p-8 rounded-[2rem] relative group transition-all">
                        @if($address->is_default)
                        <div class="absolute top-6 right-6">
                            <span class="bg-orange-500 text-white text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Default</span>
                        </div>
                        @endif
                        <div class="h-10 w-10 {{ $address->is_default ? 'bg-orange-50 text-orange-500' : 'bg-slate-50 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500' }} rounded-xl flex items-center justify-center mb-6 transition-all">
                            <i data-lucide="{{ $address->type === 'Work' ? 'briefcase' : 'home' }}" class="h-5 w-5"></i>
                        </div>
                        <h4 class="font-black text-slate-900 uppercase text-xs mb-2">{{ $address->type }} Address</h4>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium mb-6">
                            <strong>{{ $address->full_name }}</strong><br>
                            {{ $address->address_line1 }}@if($address->address_line2), {{ $address->address_line2 }}@endif<br>
                            {{ $address->city }}, {{ $address->state }}, {{ $address->pincode }}<br>
                            Phone: {{ $address->phone }}
                        </p>
                        <div class="flex items-center gap-4 pt-6 border-t border-slate-50">
                            <button onclick="editAddress({{ json_encode($address) }})" class="text-[9px] font-black text-slate-400 hover:text-orange-500 uppercase tracking-widest">Edit Address</button>
                            @if(!$address->is_default)
                            <span class="h-3 w-px bg-slate-100"></span>
                            <button onclick="setDefaultAddress({{ $address->id }})" class="text-[9px] font-black text-slate-400 hover:text-indigo-500 uppercase tracking-widest">Set Default</button>
                            @endif
                            <span class="h-3 w-px bg-slate-100"></span>
                            <button onclick="deleteAddress({{ $address->id }})" class="text-[9px] font-black text-slate-400 hover:text-rose-500 uppercase tracking-widest">Remove</button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-100 rounded-[2.5rem]">
                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="map-pin" class="h-6 w-6 text-slate-200"></i>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No addresses saved yet</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- SECTION: GIFTCARDS -->
            <div id="section-giftcards" class="tab-section {{ $activeTab !== 'giftcards' ? 'hidden' : '' }} p-6 md:p-10 transition-all duration-300">
                <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900 mb-8">Gift Card Portfolio</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                    <!-- Balance Card -->
                    <div class="lg:col-span-2 bg-slate-900 p-10 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden group">
                        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-orange-500/20 to-transparent"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/40 mb-2">Available Balance</p>
                                <h4 class="text-5xl font-black tracking-tight">₹0.00</h4>
                            </div>
                            <div class="flex items-center gap-6 mt-12">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-white/60">Active Wallet</span>
                                </div>
                                <button class="text-[9px] font-black uppercase tracking-widest text-orange-400 hover:text-orange-300 transition-all">Add Credits</button>
                            </div>
                        </div>
                        <i data-lucide="gift" class="absolute -right-8 -bottom-8 h-48 w-48 text-white/5 rotate-12 group-hover:scale-110 transition-transform duration-700"></i>
                    </div>

                    <!-- Redemption Form -->
                    <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100">
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i data-lucide="ticket" class="h-4 w-4 text-orange-500"></i>
                            Redeem Code
                        </h4>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <input type="text" placeholder="XXXX-XXXX-XXXX" class="w-full bg-white border border-slate-200 px-6 py-4 text-xs font-black outline-none rounded-xl focus:ring-2 focus:ring-orange-500/10 text-center tracking-[0.2em] uppercase">
                            </div>
                            <button class="w-full bg-slate-900 text-white py-4 text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all rounded-xl shadow-lg shadow-slate-100">Apply to Balance</button>
                        </div>
                    </div>
                </div>

                <!-- Transaction Table -->
                <div class="border border-slate-50 rounded-2xl overflow-hidden">
                    <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Transaction History</h4>
                        <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">No recent activity</span>
                    </div>
                    <div class="p-12 text-center bg-white">
                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="refresh-ccw" class="h-6 w-6 text-slate-200"></i>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Your transactions will appear here</p>
                    </div>
                </div>
            </div>

            <!-- SECTION: SAVED UPI -->
            <div id="section-payments" class="tab-section {{ $activeTab !== 'payments' ? 'hidden' : '' }} p-6 md:p-10 transition-all duration-300">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900">Saved UPI Methods</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">One-click secure payments</p>
                    </div>
                    <button class="bg-indigo-600 text-white px-6 py-3 text-[9px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all rounded-xl flex items-center gap-2 shadow-lg shadow-indigo-100">
                        <i data-lucide="plus" class="h-3 w-3"></i> Add New VPA
                    </button>
                </div>

                <div class="space-y-4 max-w-2xl">
                    <!-- UPI Item: Google Pay -->
                    <div class="flex items-center gap-6 p-6 bg-white border border-slate-100 rounded-[2rem] hover:border-indigo-100 transition-all group">
                        <div class="h-14 w-14 bg-slate-50 rounded-2xl flex items-center justify-center shrink-0">
                            <i data-lucide="smartphone" class="h-6 w-6 text-indigo-500"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-tight">Google Pay / GPay</h4>
                                <span class="bg-emerald-50 text-emerald-600 text-[8px] font-black px-2 py-0.5 rounded uppercase tracking-widest">Verified</span>
                            </div>
                            <p class="text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">prashant@okaxis</p>
                        </div>
                        <button class="text-slate-300 hover:text-rose-500 transition-colors">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </div>

                    <!-- UPI Item: PhonePe -->
                    <div class="flex items-center gap-6 p-6 bg-white border border-slate-100 rounded-[2rem] hover:border-indigo-100 transition-all group">
                        <div class="h-14 w-14 bg-slate-50 rounded-2xl flex items-center justify-center shrink-0">
                            <i data-lucide="zap" class="h-6 w-6 text-purple-500"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h4 class="text-xs font-black text-slate-800 uppercase tracking-tight">PhonePe</h4>
                                <span class="bg-emerald-50 text-emerald-600 text-[8px] font-black px-2 py-0.5 rounded uppercase tracking-widest">Verified</span>
                            </div>
                            <p class="text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">9876543210@ybl</p>
                        </div>
                        <button class="text-slate-300 hover:text-rose-500 transition-colors">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </div>

                    <!-- Add New UI Suggestion -->
                    <div class="p-12 border-2 border-dashed border-slate-100 rounded-[2.5rem] text-center flex flex-col items-center justify-center bg-slate-50/20 mt-8">
                        <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                            <i data-lucide="shield-check" class="h-6 w-6 text-slate-200"></i>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Secure UPI Storage</p>
                        <p class="text-[9px] font-medium text-slate-300 uppercase tracking-[0.2em]">All payment methods are encrypted and 100% secure</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Address Modal -->
<div id="address-modal" class="fixed inset-0 z-[10000] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-500 opacity-0" id="address-modal-overlay" onclick="closeAddressModal()"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-500" id="address-modal-content">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between shrink-0">
            <div>
                <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900" id="address-modal-title">Add Address</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Fill in the delivery details</p>
            </div>
            <button onclick="closeAddressModal()" class="h-10 w-10 rounded-full hover:bg-slate-50 flex items-center justify-center text-slate-400 transition-all">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        
        <form id="address-form" class="flex-1 overflow-y-auto p-8 space-y-6" onsubmit="saveAddress(event)">
            @csrf
            <input type="hidden" id="address-id" name="id">
            
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Type</label>
                    <select name="type" id="address-type" class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                        <option value="Home">Home</option>
                        <option value="Work">Work</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="space-y-2 flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer group pb-3.5 pl-1">
                        <input type="checkbox" name="is_default" id="address-default" value="1" class="rounded border-slate-200 text-orange-500 focus:ring-orange-500/20">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Set Default</span>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                <input type="text" name="full_name" id="address-name" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
            </div>

            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                <input type="text" name="phone" id="address-phone" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
            </div>

            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 1</label>
                <input type="text" name="address_line1" id="address-line1" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
            </div>

            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 2 (Optional)</label>
                <input type="text" name="address_line2" id="address-line2" class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                    <input type="text" name="city" id="address-city" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">State</label>
                    <input type="text" name="state" id="address-state" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pincode</label>
                    <input type="text" name="pincode" id="address-pincode" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Country</label>
                    <input type="text" name="country" value="India" readonly class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl opacity-60">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" id="address-submit-btn" class="w-full bg-slate-900 text-white py-4 text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all rounded-xl shadow-lg shadow-slate-100 flex items-center justify-center gap-2">
                    <span>Save Address</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    main { padding: 0 !important; margin: 0 !important; max-width: none !important; }
    .nav-link.active { background: #FFF7ED; color: #F97316; border-right: 4px solid #F97316; }
    .nav-link:not(.active) { color: #64748B; border-right: 4px solid transparent; }
</style>

    // Address Management
    function openAddressModal(address = null) {
        const modal = document.getElementById('address-modal');
        const content = document.getElementById('address-modal-content');
        const overlay = document.getElementById('address-modal-overlay');
        const form = document.getElementById('address-form');
        const title = document.getElementById('address-modal-title');
        
        form.reset();
        document.getElementById('address-id').value = '';
        
        if (address) {
            title.innerText = 'Edit Address';
            document.getElementById('address-id').value = address.id;
            document.getElementById('address-type').value = address.type;
            document.getElementById('address-name').value = address.full_name;
            document.getElementById('address-phone').value = address.phone;
            document.getElementById('address-line1').value = address.address_line1;
            document.getElementById('address-line2').value = address.address_line2 || '';
            document.getElementById('address-city').value = address.city;
            document.getElementById('address-state').value = address.state;
            document.getElementById('address-pincode').value = address.pincode;
            document.getElementById('address-default').checked = address.is_default;
        } else {
            title.innerText = 'Add Address';
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            content.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeAddressModal() {
        const content = document.getElementById('address-modal-content');
        const overlay = document.getElementById('address-modal-overlay');
        const modal = document.getElementById('address-modal');

        content.classList.add('translate-x-full');
        overlay.classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 500);
    }

    window.saveAddress = async function(e) {
        e.preventDefault();
        const form = e.target;
        const id = document.getElementById('address-id').value;
        const btn = document.getElementById('address-submit-btn');
        const originalHtml = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Processing...';
        if(window.lucide) lucide.createIcons();

        const url = id ? `/addresses/${id}` : '/addresses';
        const method = id ? 'PUT' : 'POST';
        
        const formData = new FormData(form);
        if (id) formData.append('_method', 'PUT');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            
            if (response.ok) {
                showToast(data.message, 'success');
                closeAddressModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Validation failed', 'error');
            }
        } catch (error) {
            showToast('Something went wrong', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if(window.lucide) lucide.createIcons();
        }
    }

    window.editAddress = function(address) {
        openAddressModal(address);
    }

    window.deleteAddress = async function(id) {
        if (!confirm('Are you sure you want to remove this address?')) return;
        
        try {
            const response = await fetch(`/addresses/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (response.ok) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        } catch (error) {
            showToast('Failed to delete address', 'error');
        }
    }

    window.setDefaultAddress = async function(id) {
        try {
            const response = await fetch(`/addresses/${id}/default`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (response.ok) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        } catch (error) {
            showToast('Failed to update default address', 'error');
        }
    }
@endsection
