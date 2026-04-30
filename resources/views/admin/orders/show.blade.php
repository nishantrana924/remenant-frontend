@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex items-center gap-8">
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2.5 text-slate-400 hover:text-orange-500 transition-all font-bold text-sm group">
                <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </div>
                Back
            </a>
            <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
            <div class="flex flex-col justify-center">
                <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Order #{{ $item->id }}</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="h-1 w-1 bg-orange-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Placed {{ $item->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button onclick="window.print()" class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all shadow-sm border border-slate-100" title="Print Invoice">
                <i data-lucide="printer" class="h-5 w-5"></i>
            </button>
            <form action="{{ route('admin.orders.update', $item->id) }}" method="POST" class="flex items-center gap-3">
                @csrf @method('PUT')
                <select name="status" class="bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-black border border-slate-100 focus:ring-2 focus:ring-orange-500 outline-none">
                    <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $item->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $item->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="bg-orange-500 text-white px-6 py-2.5 rounded-xl text-[11px] font-black shadow-lg shadow-orange-500/20 hover:scale-105 transition-all uppercase tracking-widest">
                    Update
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="page-enter grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Order Items -->
        <div class="lg:col-span-2 space-y-8">
            <div class="premium-card overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-orange-500"></i>
                        Order Items ({{ $item->orderItems->count() }})
                    </h3>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                <th class="px-8 py-4">Product</th>
                                <th class="px-6 py-4 text-center">Price</th>
                                <th class="px-6 py-4 text-center">Qty</th>
                                <th class="px-8 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($item->orderItems as $oi)
                                <tr class="hover:bg-gray-50/30 transition">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-4">
                                            <img class="w-12 h-12 rounded-xl object-cover border border-gray-100" 
                                                 src="{{ asset('storage/' . ($oi->product->image ?? '')) }}" 
                                                 onerror="this.src='https://ui-avatars.com/api/?name=Product&color=ea5f06&background=fff1e8'"
                                                 alt="">
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">{{ $oi->product->title ?? 'Deleted Product' }}</p>
                                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">ID: {{ $oi->product_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-700">₹{{ number_format($oi->price) }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-500">x{{ $oi->quantity }}</td>
                                    <td class="px-8 py-4 text-right text-sm font-bold text-gray-800">₹{{ number_format($oi->price * $oi->quantity) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-8 bg-gray-50/50 flex flex-col items-end space-y-3">
                    <div class="flex justify-between w-full max-w-xs text-sm">
                        <span class="text-gray-500 font-bold uppercase text-[10px] tracking-widest">Subtotal</span>
                        <span class="font-bold text-gray-800">₹{{ number_format($item->total_amount) }}</span>
                    </div>
                    <div class="flex justify-between w-full max-w-xs text-sm">
                        <span class="text-gray-500 font-bold uppercase text-[10px] tracking-widest">Shipping</span>
                        <span class="font-bold text-green-600 uppercase text-[10px]">Free</span>
                    </div>
                    <div class="h-px bg-gray-200 w-full max-w-xs my-2"></div>
                    <div class="flex justify-between w-full max-w-xs">
                        <span class="text-gray-800 font-bold uppercase text-xs tracking-widest">Grand Total</span>
                        <span class="font-extrabold text-2xl text-[#ea5f06]">₹{{ number_format($item->total_amount) }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping/Billing Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="premium-card p-6">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-4">
                        <i data-lucide="map-pin" class="w-5 h-5 text-blue-500"></i>
                        Shipping Address
                    </h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p class="font-bold text-gray-800">{{ $item->user->name ?? 'Guest' }}</p>
                        <p>{{ $item->address ?? 'No address provided' }}</p>
                        <p>{{ $item->city ?? '' }}, {{ $item->state ?? '' }} - {{ $item->pincode ?? '' }}</p>
                        <p class="pt-2 flex items-center gap-2 font-bold text-gray-800">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                            {{ $item->phone ?? 'No phone' }}
                        </p>
                    </div>
                </div>
                <div class="premium-card p-6">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-4">
                        <i data-lucide="credit-card" class="w-5 h-5 text-green-500"></i>
                        Payment Details
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Status</span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $item->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $item->payment_status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center px-3">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Method</span>
                            <span class="text-sm font-bold text-gray-800 uppercase">{{ $item->payment_method ?? 'COD' }}</span>
                        </div>
                        <div class="flex justify-between items-center px-3">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Transaction ID</span>
                            <span class="text-sm font-mono text-gray-400">{{ $item->transaction_id ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Info -->
        <div class="space-y-8">
            <!-- Customer Card -->
            <div class="premium-card p-6">
                <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-6">
                    <i data-lucide="user" class="w-5 h-5 text-orange-500"></i>
                    Customer Profile
                </h3>
                <div class="flex flex-col items-center text-center pb-6 border-b border-gray-50 mb-6">
                    <div class="w-20 h-20 rounded-3xl bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-3xl mb-4">
                        {{ strtoupper(substr($item->user->name ?? 'G', 0, 1)) }}
                    </div>
                    <h4 class="font-bold text-gray-800 text-lg">{{ $item->user->name ?? 'Guest' }}</h4>
                    <p class="text-sm text-gray-400">{{ $item->user->email ?? 'No email' }}</p>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Orders</span>
                        <span class="text-sm font-bold text-gray-800">{{ $item->user ? $item->user->orders()->count() : 1 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Join Date</span>
                        <span class="text-sm font-bold text-gray-800">{{ $item->user ? $item->user->created_at->format('M Y') : 'N/A' }}</span>
                    </div>
                </div>
                <a href="#" class="mt-6 w-full py-3 border border-gray-100 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    <i data-lucide="history" class="w-4 h-4"></i>
                    Order History
                </a>
            </div>

            <!-- Order Timeline -->
            <div class="premium-card p-6">
                <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-6">
                    <i data-lucide="activity" class="w-5 h-5 text-blue-500"></i>
                    Order Activity
                </h3>
                <div class="space-y-6 relative before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-px before:bg-gray-100">
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-1 w-5 h-5 rounded-full bg-green-500 border-4 border-white shadow-sm z-10"></div>
                        <p class="text-xs font-bold text-gray-800">Order Placed</p>
                        <p class="text-[10px] text-gray-400">{{ $item->created_at->format('d M, Y - h:i A') }}</p>
                    </div>
                    @if($item->status != 'pending')
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-1 w-5 h-5 rounded-full bg-blue-500 border-4 border-white shadow-sm z-10"></div>
                        <p class="text-xs font-bold text-gray-800">Payment Verified</p>
                        <p class="text-[10px] text-gray-400">{{ $item->updated_at->format('d M, Y - h:i A') }}</p>
                    </div>
                    @endif
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-1 w-5 h-5 rounded-full bg-gray-200 border-4 border-white shadow-sm z-10"></div>
                        <p class="text-xs font-bold text-gray-400">Current Status: <span class="text-[#ea5f06] uppercase">{{ $item->status }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
