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
                <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Order #{{ $item->order_number ?? $item->id }}</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="h-1 w-1 bg-{{ $item->status_color }}-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">System Status: {{ $item->status }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button onclick="window.print()" class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all shadow-sm border border-slate-100">
                <i data-lucide="printer" class="h-5 w-5"></i>
            </button>
            <form action="{{ route('admin.orders.update', $item->id) }}" method="POST" class="flex items-center gap-3">
                @csrf @method('PUT')
                <div class="flex items-center bg-slate-100 rounded-2xl p-1 gap-1">
                    <select name="status" class="bg-transparent border-0 text-[10px] font-black uppercase tracking-widest text-slate-600 focus:ring-0 cursor-pointer">
                        <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $item->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $item->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="bg-slate-800 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all">
                    Sync Status
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
    <!-- Left Column: Items & Timeline -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Order Items -->
        <div class="premium-card p-0 overflow-hidden bg-white shadow-sm border border-slate-100">
            <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30">
                <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="h-4 w-4 text-orange-500"></i>
                    Fulfillment Line Items
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-slate-300 uppercase tracking-widest border-b border-slate-50">
                            <th class="px-8 py-4">Product Details</th>
                            <th class="px-8 py-4 text-center">Price</th>
                            <th class="px-8 py-4 text-center">Qty</th>
                            <th class="px-8 py-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($item->orderItems as $oi)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 p-1 flex-shrink-0">
                                        <img src="{{ asset('images/products/' . ($oi->product->image ?? '')) }}" class="h-full w-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=P&background=ea5f06&color=fff'">
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-800">{{ $oi->product->title ?? 'Deleted Product' }}</h4>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $oi->variant_name ?? 'Standard' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center text-xs font-black text-slate-600">₹{{ number_format($oi->price) }}</td>
                            <td class="px-8 py-4 text-center">
                                <span class="px-2 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-500 uppercase">x{{ $oi->quantity }}</span>
                            </td>
                            <td class="px-8 py-4 text-right text-xs font-black text-slate-800">₹{{ number_format($oi->price * $oi->quantity) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-8 bg-slate-50/50 flex flex-col items-end space-y-3">
                <div class="flex justify-between w-64 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <span>Subtotal</span>
                    <span class="text-slate-800">₹{{ number_format($item->total_amount - $item->shipping_charge + $item->discount_amount) }}</span>
                </div>
                <div class="flex justify-between w-64 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <span>Shipping Charge</span>
                    <span class="text-emerald-500">+₹{{ number_format($item->shipping_charge) }}</span>
                </div>
                @if($item->discount_amount > 0)
                <div class="flex justify-between w-64 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <span>Discount</span>
                    <span class="text-rose-500">-₹{{ number_format($item->discount_amount) }}</span>
                </div>
                @endif
                <div class="h-px w-64 bg-slate-200 my-2"></div>
                <div class="flex justify-between w-64">
                    <span class="text-xs font-black text-slate-800 uppercase tracking-widest">Grand Total</span>
                    <span class="text-2xl font-black text-orange-500">₹{{ number_format($item->total_amount) }}</span>
                </div>
            </div>
        </div>

        <!-- Shipment Tracking & Timeline -->
        <div class="premium-card p-0 overflow-hidden bg-white shadow-sm border border-slate-100">
            <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <i data-lucide="truck" class="h-4 w-4 text-blue-500"></i>
                    Logistics & Delivery Timeline
                </h3>
                <span class="text-[9px] font-black text-blue-600 uppercase bg-blue-50 px-2 py-1 rounded-lg">{{ $item->delivery_status }}</span>
            </div>
            <div class="p-8">
                <!-- Status Stepper -->
                <div class="relative flex items-center justify-between mb-12">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-100 z-0"></div>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-blue-500 z-0 transition-all duration-1000" 
                         style="width: {{ match($item->delivery_status) { 'pending' => '0%', 'packed' => '33%', 'shipped' => '66%', 'delivered' => '100%', 'returned' => '0%', default => '0%' } }}"></div>
                    
                    @foreach(['pending', 'packed', 'shipped', 'delivered'] as $step)
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center shadow-lg transition-all {{ $item->delivery_status == $step || (isset($past_steps) && in_array($step, $past_steps)) ? 'bg-blue-500 text-white scale-110' : 'bg-white text-slate-300 border-2 border-slate-100' }}">
                            <i data-lucide="{{ match($step) { 'pending' => 'clock', 'packed' => 'package', 'shipped' => 'send', 'delivered' => 'check-circle' } }}" class="h-5 w-5"></i>
                        </div>
                        <span class="absolute -bottom-6 text-[8px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">{{ $step }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Logistics Form -->
                <form action="{{ route('admin.orders.update', $item->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-50">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-[8px] font-black uppercase text-slate-400 mb-2">Delivery Status</label>
                        <select name="delivery_status" class="w-full premium-input px-4 py-3 text-xs font-black uppercase tracking-widest">
                            <option value="pending" {{ $item->delivery_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="packed" {{ $item->delivery_status == 'packed' ? 'selected' : '' }}>Packed</option>
                            <option value="shipped" {{ $item->delivery_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $item->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="returned" {{ $item->delivery_status == 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[8px] font-black uppercase text-slate-400 mb-2">Courier Name</label>
                        <input type="text" name="courier_name" value="{{ $item->courier_name }}" class="w-full premium-input px-4 py-3 text-xs font-bold" placeholder="e.g. BlueDart, Delhivery">
                    </div>
                    <div>
                        <label class="block text-[8px] font-black uppercase text-slate-400 mb-2">Tracking ID</label>
                        <div class="flex gap-2">
                            <input type="text" name="tracking_id" value="{{ $item->tracking_id }}" class="flex-1 premium-input px-4 py-3 text-xs font-bold font-mono" placeholder="AWB12345678">
                            <button type="submit" class="bg-blue-500 text-white px-4 rounded-xl hover:bg-blue-600 transition-all">
                                <i data-lucide="save" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Sidebar Insights -->
    <div class="space-y-8">
        <!-- Customer Intelligence -->
        <div class="premium-card p-8 bg-white border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 uppercase tracking-widest text-[10px] mb-8">Customer Intelligence</h3>
            <div class="flex flex-col items-center text-center pb-8 border-b border-slate-50 mb-8">
                <div class="h-24 w-24 rounded-[2.5rem] bg-indigo-50 flex items-center justify-center text-indigo-500 font-black text-4xl mb-6 shadow-inner">
                    {{ strtoupper(substr($item->customer_name ?? $item->user->name ?? 'G', 0, 1)) }}
                </div>
                <h4 class="text-lg font-black text-slate-800 leading-none">{{ $item->customer_name ?? $item->user->name ?? 'Guest Customer' }}</h4>
                <p class="text-xs font-bold text-slate-400 mt-2 uppercase tracking-tighter">{{ $item->email }}</p>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between p-4 bg-slate-50 rounded-2xl">
                    <span class="text-[9px] font-black text-slate-400 uppercase">Total Spent</span>
                    <span class="text-sm font-black text-slate-800">₹{{ number_format($item->user ? $item->user->orders()->where('payment_status', 'paid')->sum('total_amount') : $item->total_amount) }}</span>
                </div>
                <div class="flex justify-between p-4 bg-slate-50 rounded-2xl">
                    <span class="text-[9px] font-black text-slate-400 uppercase">Lifetime Orders</span>
                    <span class="text-sm font-black text-slate-800">{{ $item->user ? $item->user->orders()->count() : 1 }}</span>
                </div>
            </div>
            
            <button class="w-full mt-8 py-4 rounded-2xl border-2 border-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-500 hover:border-indigo-500 transition-all">View Full Profile</button>
        </div>

        <!-- Contact & Shipping -->
        <div class="premium-card p-8 bg-white border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 uppercase tracking-widest text-[10px] mb-6">Fulfillment Destination</h3>
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                        <i data-lucide="map-pin" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-800">Shipping Address</p>
                        <p class="text-xs text-slate-500 leading-relaxed mt-1">
                            {{ $item->address }}<br>
                            {{ $item->city }}, {{ $item->state }} - {{ $item->pincode }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                        <i data-lucide="phone" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-800">Contact Number</p>
                        <p class="text-xs text-slate-500 font-bold mt-1">{{ $item->phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Intelligence -->
        <div class="premium-card p-8 bg-slate-900 text-white border-0 shadow-2xl">
            <h3 class="font-black uppercase tracking-widest text-[10px] mb-8 text-white/50">Payment Integrity</h3>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-black uppercase tracking-widest text-white/30">Payment Status</span>
                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-emerald-500/20 text-emerald-400">{{ $item->payment_status }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-black uppercase tracking-widest text-white/30">Payment Method</span>
                    <span class="text-xs font-black uppercase tracking-widest">{{ $item->payment_method ?? 'Prepaid / Razorpay' }}</span>
                </div>
                <div class="pt-6 border-t border-white/5">
                    <p class="text-[8px] font-black uppercase tracking-[0.2em] text-white/20 mb-2">Transaction Fingerprint</p>
                    <p class="text-[10px] font-mono text-white/60 truncate">{{ $item->transaction_id ?? 'TXN_MODULAR_'.Str::random(12) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
