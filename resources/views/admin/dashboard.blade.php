@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex items-center gap-8">
            <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
            <div class="flex flex-col justify-center">
                <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Admin Dashboard</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="h-1 w-1 bg-emerald-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Overview & Analytics</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-8">
        <!-- Welcome Section -->
        <div class="premium-card p-8 bg-white border-0 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="h-20 w-20 rounded-[2rem] bg-orange-50 flex items-center justify-center text-orange-500 shadow-inner">
                    <i data-lucide="sparkles" class="h-10 w-10"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Welcome back, {{ Auth::user()->name }}!</h2>
                    <p class="text-slate-500 font-medium mt-1">Here's what's happening with your brand today.</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Server Status</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-sm font-bold text-slate-700 uppercase">Live & Healthy</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="premium-card p-6 border-b-4 border-orange-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500">
                        <i data-lucide="users" class="h-6 w-6"></i>
                    </div>
                    <span class="text-[10px] bg-orange-100 text-orange-700 px-2 py-1 rounded-lg font-black uppercase">+12.5%</span>
                </div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Customers</p>
                <h4 class="text-3xl font-black text-slate-800 mt-1">{{ number_format($stats['total_users']) }}</h4>
            </div>

            <div class="premium-card p-6 border-b-4 border-indigo-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500">
                        <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                    </div>
                    <span class="text-[10px] bg-indigo-100 text-indigo-700 px-2 py-1 rounded-lg font-black uppercase">Pending</span>
                </div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Store Orders</p>
                <h4 class="text-3xl font-black text-slate-800 mt-1">{{ number_format($stats['total_orders']) }}</h4>
            </div>

            <div class="premium-card p-6 border-b-4 border-emerald-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <i data-lucide="package" class="h-6 w-6"></i>
                    </div>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-1 rounded-lg font-black uppercase">Active</span>
                </div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Products</p>
                <h4 class="text-3xl font-black text-slate-800 mt-1">{{ number_format($stats['total_products']) }}</h4>
            </div>

            <div class="premium-card p-6 border-b-4 border-rose-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500">
                        <i data-lucide="trending-up" class="h-6 w-6"></i>
                    </div>
                    <span class="text-[10px] bg-rose-100 text-rose-700 px-2 py-1 rounded-lg font-black uppercase">Live</span>
                </div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Sales</p>
                <h4 class="text-3xl font-black text-slate-800 mt-1">₹{{ number_format($stats['total_revenue']) }}</h4>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Orders -->
            <div class="premium-card bg-white p-0 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="font-black text-slate-800 flex items-center gap-2">
                        <i data-lucide="history" class="h-5 w-5 text-orange-500"></i>
                        Recent Orders
                    </h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black text-orange-500 uppercase tracking-widest hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Customer</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recent_orders as $order)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">#{{ $order->id }}</td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-slate-700 leading-none">{{ $order->user->name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-black text-slate-800">₹{{ number_format($order->total_amount) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $order->status == 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">No orders yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Customers -->
            <div class="premium-card bg-white p-0 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <h3 class="font-black text-slate-800 flex items-center gap-2">
                        <i data-lucide="user-plus" class="h-5 w-5 text-indigo-500"></i>
                        New Customers
                    </h3>
                    <button class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline">Manage All</button>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($recent_users as $user)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center text-indigo-500 font-black shadow-sm group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-800">{{ $user->name }}</h4>
                                    <p class="text-[11px] text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-black text-slate-300 uppercase block mb-1">Joined</span>
                                <span class="text-xs font-bold text-slate-600">{{ $user->created_at->format('d M') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-400 italic">No new users</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
