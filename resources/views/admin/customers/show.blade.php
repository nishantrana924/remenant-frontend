@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex items-center gap-8">
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-2.5 text-slate-400 hover:text-orange-500 transition-all font-bold text-sm group">
                <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </div>
                Back
            </a>
            <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
            <div class="flex flex-col justify-center">
                <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Customer Profile</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="h-1 w-1 bg-indigo-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Identity: {{ $item->name }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
    <!-- Left Column: Stats & Profile -->
    <div class="space-y-8">
        <!-- Main Profile Card -->
        <div class="premium-card p-8 bg-white border border-slate-100 shadow-sm text-center">
            <div class="relative inline-block mb-6">
                <div class="h-28 w-28 rounded-[2.5rem] bg-indigo-50 flex items-center justify-center text-indigo-500 font-black text-5xl shadow-inner">
                    {{ strtoupper(substr($item->name, 0, 1)) }}
                </div>
                <div class="absolute -bottom-2 -right-2 h-10 w-10 bg-white rounded-2xl shadow-lg flex items-center justify-center border border-slate-50">
                    <i data-lucide="shield-check" class="h-5 w-5 text-emerald-500"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-800">{{ $item->name }}</h3>
            <p class="text-sm font-bold text-slate-400 mt-1">{{ $item->email }}</p>
            <div class="mt-6 flex flex-wrap justify-center gap-2">
                <span class="px-3 py-1 bg-slate-100 rounded-full text-[9px] font-black text-slate-500 uppercase tracking-widest">ID: #{{ $item->id }}</span>
                <span class="px-3 py-1 bg-indigo-50 text-indigo-500 rounded-full text-[9px] font-black uppercase tracking-widest">Premium Member</span>
            </div>
        </div>

        <!-- Lifetime Value Stats -->
        <div class="premium-card p-0 overflow-hidden bg-slate-900 shadow-2xl">
            <div class="p-8 space-y-6">
                <h4 class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">Monetary Intelligence</h4>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-white/20 uppercase tracking-widest mb-1">Lifetime Value (LTV)</p>
                        <h4 class="text-3xl font-black text-white">₹{{ number_format($stats['total_spent']) }}</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                            <p class="text-[8px] font-black text-white/20 uppercase tracking-widest mb-1">Orders</p>
                            <h4 class="text-xl font-black text-white">{{ $stats['total_orders'] }}</h4>
                        </div>
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                            <p class="text-[8px] font-black text-white/20 uppercase tracking-widest mb-1">AOV</p>
                            <h4 class="text-xl font-black text-white">₹{{ number_format($stats['avg_order_value']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-8 py-4 bg-white/5 flex items-center justify-between">
                <span class="text-[9px] font-black text-white/20 uppercase tracking-widest">Joined On</span>
                <span class="text-xs font-bold text-white/60">{{ $item->created_at->format('d M, Y') }}</span>
            </div>
        </div>

        <!-- Contact Intelligence -->
        <div class="premium-card p-8 bg-white border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 uppercase tracking-widest text-[10px] mb-6">Contact Channels</h3>
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                        <i data-lucide="phone" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mobile Number</p>
                        <p class="text-xs font-bold text-slate-800">{{ $item->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                        <i data-lucide="mail" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Primary Email</p>
                        <p class="text-xs font-bold text-slate-800">{{ $item->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Order History & Preferences -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Order History -->
        <div class="premium-card p-0 overflow-hidden bg-white shadow-sm border border-slate-100">
            <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="h-4 w-4 text-orange-500"></i>
                    Purchase History
                </h3>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $item->orders->count() }} Orders Found</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-slate-300 uppercase tracking-widest border-b border-slate-50">
                            <th class="px-8 py-4">Order</th>
                            <th class="px-8 py-4">Date</th>
                            <th class="px-8 py-4">Fulfillment</th>
                            <th class="px-8 py-4">Total</th>
                            <th class="px-8 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($item->orders as $order)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <span class="text-xs font-black text-slate-800">#{{ $order->id }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-xs font-bold text-slate-500">{{ $order->created_at->format('d M, Y') }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $order->status == 'completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-orange-50 text-orange-600' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-xs font-black text-slate-800">₹{{ number_format($order->total_amount) }}</span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-orange-500 transition-colors uppercase tracking-widest">
                                    Details <i data-lucide="arrow-right" class="h-3 w-3"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic">No orders recorded for this customer</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Purchased Products Summary -->
        <div class="premium-card p-8 bg-white border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 uppercase tracking-widest text-[10px] mb-8">Recently Purchased Products</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($item->orders->flatMap->orderItems->unique('product_id')->take(4) as $oi)
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl group border border-transparent hover:border-orange-100 transition-all">
                    <div class="h-14 w-14 rounded-xl bg-white p-2 shadow-sm">
                        <img src="{{ asset('images/products/' . ($oi->product->image ?? '')) }}" class="h-full w-full object-contain group-hover:scale-110 transition-transform" onerror="this.src='https://ui-avatars.com/api/?name=P&background=ea5f06&color=fff'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-black text-slate-800 truncate">{{ $oi->product->title ?? 'Deleted Product' }}</h4>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5 tracking-wider">{{ $oi->product->tagline ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
