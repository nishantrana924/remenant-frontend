@extends('admin.layouts.app')

@section('content')
<div class="pb-24" x-data="{ 
    showStatusModal: false,
    newStatus: '{{ $item->status }}',
    statusMsg: '',
    showShippingModal: false,
    shippingData: { id: {{ $item->id }}, tracking_id: '{{ $item->tracking_id }}', courier_name: '{{ $item->courier_name ?? "BlueDart" }}' },
    
    updateStatus() {
        fastSubmit('{{ route('admin.orders.update-status', $item->id) }}', {
            data: { status: this.newStatus, message: this.statusMsg },
            success: (res) => {
                toast(res.message);
                setTimeout(() => location.reload(), 1000);
            }
        });
    },

    openShipping() {
        this.showShippingModal = true;
    },

    saveShipping() {
        fastSubmit(`/admin/orders/${this.shippingData.id}/status`, {
            data: { 
                delivery_status: 'shipped', 
                status: 'shipped',
                tracking_id: this.shippingData.tracking_id, 
                courier_name: this.shippingData.courier_name 
            },
            success: (res) => {
                toast(res.message);
                setTimeout(() => location.reload(), 1000);
            }
        });
        this.showShippingModal = false;
    }
}">
    <!-- Top Header & Navigation -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" class="h-10 w-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all shadow-sm">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">#{{ $item->order_number ?? $item->id }}</h1>
                    <span class="px-3 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest bg-{{ $item->status_color }}-50 text-{{ $item->status_color }}-600 border border-{{ $item->status_color }}-100">
                        {{ str_replace('_', ' ', $item->status) }}
                    </span>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Order Placed: {{ $item->created_at->format('d M Y • h:i A') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.packing-slip', $item->id) }}" target="_blank" class="saas-btn-secondary py-2.5">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                Packing Slip
            </a>
            <a href="{{ route('admin.orders.invoice', $item->id) }}" target="_blank" class="saas-btn-primary py-2.5 px-6 shadow-xl shadow-orange-200">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                Tax Invoice
            </a>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Order Details & Products -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Progression Pulse -->
            <div class="saas-card p-8 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
                <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em] mb-8">Order Status</h3>
                
                <div class="relative flex items-center justify-between">
                    @php
                        $steps = ['pending', 'processing', 'packed', 'shipped', 'delivered'];
                        $currentIdx = array_search($item->status, $steps);
                        if($currentIdx === false) $currentIdx = 0;
                    @endphp
                    
                    <!-- Line -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-50 rounded-full">
                        <div class="h-full bg-orange-500 transition-all duration-1000" style="width: {{ ($currentIdx / (count($steps)-1)) * 100 }}%"></div>
                    </div>
                    
                    @foreach($steps as $index => $step)
                    <div class="relative z-10 flex flex-col items-center gap-3">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center border-4 transition-all duration-500
                            {{ $index <= $currentIdx ? 'bg-orange-500 border-orange-100 text-white' : 'bg-white border-slate-50 text-slate-200' }}">
                            <i data-lucide="{{ $index <= $currentIdx ? 'check' : 'circle' }}" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[8px] font-bold uppercase tracking-widest {{ $index <= $currentIdx ? 'text-slate-900' : 'text-slate-300' }}">
                            {{ $step }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Items Deck -->
            <div class="saas-card p-0 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Product Details</h3>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ count($item->orderItems) }} Items</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($item->orderItems as $product)
                    <div class="px-8 py-6 flex items-center gap-6 hover:bg-slate-50/50 transition-all">
                        <div class="h-20 w-16 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 group-hover:scale-105 transition-transform">
                            <img src="{{ $product->product->image_url }}" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-900 text-sm uppercase tracking-tight">{{ $product->product->name }}</h4>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-white border border-slate-100 px-2 py-0.5 rounded-lg">SKU: {{ $product->sku ?? $product->product->sku }}</span>
                                @if($product->variant_size)
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Size: {{ $product->variant_size }}</span>
                                @endif
                                @if($product->variant_color)
                                <div class="flex items-center gap-1">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Color:</span>
                                    <div class="h-2 w-2 rounded-full border border-slate-200" style="background: {{ $product->variant_color }}"></div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">₹{{ number_format($product->price) }} × {{ $product->quantity }}</p>
                            <h5 class="text-lg font-bold text-slate-900 tracking-tighter">₹{{ number_format($product->price * $product->quantity) }}</h5>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Financials Footer -->
                <div class="bg-orange-600 p-8 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <p class="text-[9px] font-bold text-orange-100 uppercase tracking-[0.2em] mb-1">Subtotal</p>
                        <p class="text-xl font-bold text-white tracking-tighter">₹{{ number_format($item->total_amount - $item->shipping_charge + $item->discount_amount) }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-orange-100 uppercase tracking-[0.2em] mb-1">Shipping</p>
                        <p class="text-xl font-bold text-white tracking-tighter">₹{{ number_format($item->shipping_charge) }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-orange-100 uppercase tracking-[0.2em] mb-1">Discount</p>
                        <p class="text-xl font-bold text-white tracking-tighter">-₹{{ number_format($item->discount_amount) }}</p>
                    </div>
                    <div class="text-right md:text-left">
                        <p class="text-[9px] font-bold text-white uppercase tracking-[0.2em] mb-1">Total Amount</p>
                        <p class="text-3xl font-bold text-white tracking-tighter">₹{{ number_format($item->total_amount) }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping & Logistics Control -->
            <div class="saas-card p-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Shipping Info</h3>
                    <i data-lucide="truck" class="w-4 h-4 text-slate-300"></i>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Courier Partner</p>
                            <p class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ $item->courier_name ?? 'Not Assigned' }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tracking ID</p>
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-bold text-slate-900 tracking-tighter uppercase">{{ $item->tracking_id ?? '---' }}</p>
                                @if($item->tracking_id)
                                <button class="text-orange-500 hover:scale-110 transition-transform"><i data-lucide="external-link" class="w-4 h-4"></i></button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col justify-center gap-3">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">Ready for fulfillment?</p>
                        <div class="flex gap-2">
                            <button @click="openShipping()" class="flex-1 saas-btn-secondary py-3 text-[9px] font-bold uppercase tracking-widest">
                                <i data-lucide="user-cog" class="w-3.5 h-3.5 mr-1"></i>
                                Manual
                            </button>
                            <button @click="fastSubmit('{{ route('admin.orders.ship-to-shiprocket', $item->id) }}', { method: 'POST', success: (res) => { toast(res.message); setTimeout(()=>location.reload(), 1000); } })" 
                                    class="flex-1 bg-orange-600 text-white rounded-xl py-3 text-[9px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-orange-700 transition-all">
                                <i data-lucide="rocket" class="w-3.5 h-3.5"></i>
                                Shiprocket
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Customer & Timeline -->
        <div class="space-y-8">
            
            <!-- Customer Identity -->
            <div class="saas-card p-0 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Customer Registry</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-orange-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($item->customer_name ?? $item->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm uppercase tracking-tight">{{ $item->customer_name }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-4 pt-4 border-t border-slate-50">
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Primary Mobile</p>
                            <p class="text-xs font-bold text-slate-900">{{ $item->phone }}</p>
                        </div>
                        @if($item->alternate_phone)
                        <div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Alternate Mobile</p>
                            <p class="text-xs font-bold text-slate-900">{{ $item->alternate_phone }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Full Address</p>
                            <p class="text-xs font-bold text-slate-600 leading-relaxed uppercase">
                                {{ $item->address }}<br>
                                @if($item->landmark) Landmark: {{ $item->landmark }}<br> @endif
                                {{ $item->city }}, {{ $item->state }} - {{ $item->pincode }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="saas-card p-6 bg-white border border-slate-100 shadow-sm">
                <h3 class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.25em] mb-4">Payment Details</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Method</span>
                        <span class="text-[10px] font-bold text-slate-900 uppercase tracking-widest">{{ $item->payment_method }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</span>
                        <span class="text-[10px] font-bold {{ $item->payment_status === 'paid' ? 'text-emerald-500' : 'text-orange-500' }} uppercase tracking-widest">{{ $item->payment_status }}</span>
                    </div>
                    @if($item->payment_transaction_id)
                    <div class="pt-4 border-t border-slate-50">
                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Transaction ID</p>
                        <p class="text-[10px] font-bold text-slate-600 tracking-tighter">{{ $item->payment_transaction_id }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="saas-card p-0 overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Order History</h3>
                    <button @click="showStatusModal = true" class="text-[9px] font-bold text-orange-500 uppercase tracking-widest hover:underline">+ Update</button>
                </div>
                <div class="p-8 relative">
                    <!-- Line -->
                    <div class="absolute left-[2.25rem] top-8 bottom-8 w-px bg-slate-100"></div>
                    
                    <div class="space-y-8 relative">
                        @foreach($item->timelines as $log)
                        <div class="flex items-start gap-4">
                            <div class="h-6 w-6 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center z-10">
                                <div class="h-2 w-2 rounded-full {{ $loop->first ? 'bg-orange-500 animate-pulse' : 'bg-slate-300' }}"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-bold text-slate-900 uppercase tracking-widest">{{ $log->status }}</p>
                                    <span class="text-[8px] font-bold text-slate-300 uppercase">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 leading-relaxed">{{ $log->message }}</p>
                                @if($log->user)
                                <p class="text-[8px] font-black text-slate-400 mt-1 uppercase tracking-tighter">By: {{ $log->user->name }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="flex items-start gap-4">
                            <div class="h-6 w-6 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center z-10">
                                <div class="h-2 w-2 rounded-full bg-slate-300"></div>
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-bold text-slate-900 uppercase tracking-widest">Order Placed</p>
                                <p class="text-[10px] text-slate-500 mt-1">Transaction initialized by customer.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <template x-if="showStatusModal">
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showStatusModal = false"></div>
            <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-slate-900 uppercase tracking-tight">Update Status</h3>
                    <button @click="showStatusModal = false" class="text-slate-400 hover:text-rose-500"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">New Status</label>
                        <select x-model="newStatus" class="saas-input uppercase text-[10px] font-bold tracking-widest">
                            <option value="pending">Pending</option>
                            <option value="processing">Confirmed / Processing</option>
                            <option value="packed">Packed</option>
                            <option value="shipped">Shipped</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Audit Note (Internal)</label>
                        <textarea x-model="statusMsg" placeholder="Describe the reason for change..." class="saas-input min-h-[100px] text-xs"></textarea>
                    </div>
                    <button @click="updateStatus()" class="saas-btn-primary w-full py-4 text-sm font-bold uppercase tracking-widest mt-4">
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Manual Shipping Modal -->
    <template x-if="showShippingModal">
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showShippingModal = false"></div>
            <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-slate-900 uppercase tracking-tight">Shipment Details</h3>
                    <button @click="showShippingModal = false" class="text-slate-400 hover:text-rose-500"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Courier Partner</label>
                        <select x-model="shippingData.courier_name" class="saas-input uppercase text-[10px] font-bold tracking-widest">
                            <option value="BlueDart">BlueDart</option>
                            <option value="Delhivery">Delhivery</option>
                            <option value="DTDC">DTDC</option>
                            <option value="FedEx">FedEx</option>
                            <option value="XpressBees">XpressBees</option>
                            <option value="Other">Other / Local</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tracking ID / AWB Number</label>
                        <input type="text" x-model="shippingData.tracking_id" placeholder="e.g. 1234567890" class="saas-input font-bold text-lg">
                    </div>
                    <button @click="saveShipping()" class="saas-btn-primary w-full py-4 text-sm font-bold uppercase tracking-widest mt-4">
                        Confirm & Dispatch
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
