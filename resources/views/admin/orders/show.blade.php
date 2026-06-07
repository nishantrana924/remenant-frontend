@extends('admin.layouts.app')

@section('content')
<div class="pb-24" x-data="{ 
    showStatusModal: false,
    newStatus: '{{ $item->status }}',
    statusMsg: '',
    showCourierModal: false,
    shippingConfig: { id: {{ $item->id }}, weight: 250, length: 17, breadth: 10, height: 5, courier_id: '' },
    couriers: [],
    fetchingRates: false,
    
    init() {
        this.$watch('showCourierModal', value => this.toggleScroll());
        this.$watch('showStatusModal', value => this.toggleScroll());
    },

    toggleScroll() {
        const mc = document.getElementById('main-content');
        if (mc) {
            mc.style.overflow = (this.showCourierModal || this.showStatusModal) ? 'hidden' : '';
        }
    },

    updateStatus() {
        fastSubmit('{{ route('admin.orders.update-status', $item->id) }}', {
            data: { status: this.newStatus, message: this.statusMsg },
            success: (res) => {
                toast(res.message);
                setTimeout(() => location.reload(), 1000);
            }
        });
    },

    openCourierSelection() {
        this.shippingConfig.weight = 250;
        this.shippingConfig.length = 17;
        this.shippingConfig.breadth = 10;
        this.shippingConfig.height = 5;
        this.shippingConfig.courier_id = '';
        this.couriers = [];
        this.showCourierModal = true;
    },

    closeCourierModal() {
        this.showCourierModal = false;
    },

    fetchRates() {
        if (!this.shippingConfig.weight) return window.toast('Please enter weight', 'error');
        this.fetchingRates = true;
        
        window.fastSubmit(`/admin/orders/${this.shippingConfig.id}/nimbuspost-rates`, {
            method: 'POST',
            data: {
                weight: this.shippingConfig.weight,
                length: this.shippingConfig.length,
                breadth: this.shippingConfig.breadth,
                height: this.shippingConfig.height
            },
            success: (res) => {
                this.couriers = res.data;
                if(this.couriers.length > 0) {
                    this.shippingConfig.courier_id = this.couriers[0].id;
                    console.log('Auto-selected courier:', this.couriers[0]);
                    console.log('courier_id set to:', this.shippingConfig.courier_id);
                }
                this.fetchingRates = false;
            },
            error: (err) => {
                const msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : (err.message || 'Failed to fetch rates');
                window.toast(msg, 'error');
                this.fetchingRates = false;
            }
        });
    },

    confirmShipment(btn) {
        console.log('Dispatching with shippingConfig:', JSON.parse(JSON.stringify(this.shippingConfig)));
        if(!this.shippingConfig.courier_id) return window.toast('Please select a courier', 'error');
        
        window.fastSubmit(`/admin/orders/${this.shippingConfig.id}/ship-to-nimbuspost`, {
            method: 'POST',
            data: this.shippingConfig,
            button: btn,
            success: (res) => {
                window.toast(res.message);
                this.closeCourierModal();
                setTimeout(() => location.reload(), 1000);
            },
            error: (err) => {
                console.error('Shipment Error:', err);
                const msg = err.response?.data?.message || err.response?.data?.error || 'Failed to push to NimbusPost';
                window.toast(typeof msg === 'string' ? msg : 'Failed to push shipment', 'error');
            }
        });
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
            <a href="{{ ($item->shipment && $item->shipment->nimbus_shipment_id) ? route('admin.orders.nimbus-label', $item->id) : route('admin.orders.packing-slip', $item->id) }}" target="_blank" class="saas-btn-secondary py-2.5">
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
                            <h4 class="font-bold text-slate-900 text-sm uppercase tracking-tight">{{ $product->product->title ?? $product->product->name }}</h4>
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
                        @if(isset($shippingCharges) && $shippingCharges !== 'N/A')
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Billed Courier Charge</p>
                            <p class="text-sm font-black text-emerald-600 uppercase tracking-tight flex items-center gap-2">
                                ₹{{ is_numeric($shippingCharges) ? number_format($shippingCharges, 2) : $shippingCharges }}
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                            </p>
                        </div>
                        @endif
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
                            @if($item->shipment && $item->shipment->nimbus_shipment_id)
                                <a href="{{ route('admin.orders.nimbus-label', $item->id) }}" target="_blank" class="flex-1 saas-btn-secondary py-3 text-[9px] font-bold uppercase tracking-widest text-blue-600 border-blue-100 hover:bg-blue-50">
                                    <i data-lucide="printer" class="w-3.5 h-3.5 mr-1"></i>
                                    Print Label
                                </a>
                                <button @click="fastSubmit('{{ route('admin.orders.cancel-nimbuspost', $item->id) }}', { method: 'POST', success: (res) => { toast(res.message); setTimeout(()=>location.reload(), 1000); } })" 
                                        class="flex-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl py-3 text-[9px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-rose-100 transition-all">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                                    Cancel
                                </button>
                            @else
                                <button @click="openCourierSelection()" class="w-full bg-orange-600 text-white rounded-xl py-3 text-[9px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-orange-700 transition-all shadow-lg shadow-orange-100">
                                    <i data-lucide="rocket" class="w-3.5 h-3.5"></i>
                                    Select Courier & Ship
                                </button>
                            @endif
                        </div>

                        {{-- Direct NimbusPost Label PDF Download --}}
                        @if($item->shipment && $item->shipment->label_url)
                        <a href="{{ $item->shipment->label_url }}" target="_blank"
                           class="w-full flex items-center justify-center gap-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl py-3 text-[9px] font-bold uppercase tracking-widest hover:bg-emerald-100 transition-all">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            Download NimbusPost Label PDF
                        </a>
                        @endif

                        {{-- Tax Invoice Download --}}
                        <a href="{{ route('admin.orders.invoice', $item->id) }}" target="_blank"
                           class="w-full flex items-center justify-center gap-2 bg-orange-50 text-orange-600 border border-orange-100 rounded-xl py-3 text-[9px] font-bold uppercase tracking-widest hover:bg-orange-100 transition-all">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                            Download Tax Invoice
                        </a>

                        {{-- Packing Slip Download --}}
                        <a href="{{ route('admin.orders.packing-slip', $item->id) }}" target="_blank"
                           class="w-full flex items-center justify-center gap-2 bg-slate-50 text-slate-600 border border-slate-200 rounded-xl py-3 text-[9px] font-bold uppercase tracking-widest hover:bg-slate-100 transition-all">
                            <i data-lucide="package" class="w-3.5 h-3.5"></i>
                            Download Packing Slip
                        </a>
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
                            <div class="relative h-6 w-6 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center">
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
                            <div class="relative h-6 w-6 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center">
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
    <template x-teleport="body">
        <div x-show="showStatusModal" x-cloak class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
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

    <!-- Courier Selection Modal -->
    <template x-teleport="body">
        <div x-show="showCourierModal" x-cloak class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeCourierModal()"></div>
            <div class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl p-8 flex flex-col" style="max-height: 90vh; overflow-y: auto;">
                <div class="flex items-center justify-between mb-5 shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Ship Order</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Select Courier & Generate Label</p>
                    </div>
                    <button @click="closeCourierModal()" class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
                
                <div class="space-y-6 flex-1">
                    <!-- Step 1: Package Details -->
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                                Step 1: Package Dimensions
                            </h4>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mb-1 block">Weight (g)</label>
                                <input type="number" x-model="shippingConfig.weight" class="w-full rounded-xl bg-white border border-slate-200 px-4 py-2.5 text-sm font-bold focus:ring-orange-500 focus:border-orange-500 text-center">
                            </div>
                            <div>
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mb-1 block">L (cm)</label>
                                <input type="number" x-model="shippingConfig.length" class="w-full rounded-xl bg-white border border-slate-200 px-4 py-2.5 text-sm font-bold focus:ring-orange-500 focus:border-orange-500 text-center">
                            </div>
                            <div>
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mb-1 block">W (cm)</label>
                                <input type="number" x-model="shippingConfig.breadth" class="w-full rounded-xl bg-white border border-slate-200 px-4 py-2.5 text-sm font-bold focus:ring-orange-500 focus:border-orange-500 text-center">
                            </div>
                            <div>
                                <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest ml-1 mb-1 block">H (cm)</label>
                                <input type="number" x-model="shippingConfig.height" class="w-full rounded-xl bg-white border border-slate-200 px-4 py-2.5 text-sm font-bold focus:ring-orange-500 focus:border-orange-500 text-center">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button @click="fetchRates()" :disabled="fetchingRates" class="bg-slate-900 text-white rounded-xl px-6 py-2.5 text-[10px] font-black uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all disabled:opacity-50 flex items-center gap-2 shadow-lg shadow-slate-200">
                                <span x-show="!fetchingRates">Fetch Courier Rates</span>
                                <span x-show="fetchingRates" class="flex items-center gap-2"><i class="animate-spin h-3 w-3 border-2 border-white/20 border-t-white rounded-full"></i> Loading...</span>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Select Courier -->
                    <div x-show="couriers.length > 0" x-transition class="space-y-4">
                        <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                            Step 2: Select Courier Partner
                        </h4>
                        
                        <div class="space-y-2">
                            <template x-for="c in couriers" :key="c.id">
                                <label class="relative flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition-all"
                                       :class="shippingConfig.courier_id == c.id ? 'border-orange-500 bg-orange-50/50' : 'border-slate-100 hover:border-orange-200 bg-white'">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="courier" :value="c.id" x-model="shippingConfig.courier_id" class="h-4 w-4 text-orange-500 border-slate-300 focus:ring-orange-500">
                                        <div>
                                            <p class="text-xs font-black text-slate-900 uppercase" x-text="c.name"></p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">ETA: <span x-text="c.edd || 'N/A'"></span></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-black text-emerald-600">₹<span x-text="c.total_charges"></span></p>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase mt-0.5">Shipping Charge</p>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100 shrink-0">
                    <button @click="confirmShipment($event.target)" :disabled="!shippingConfig.courier_id" class="w-full bg-orange-600 text-white rounded-2xl py-4 text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-orange-200 hover:brightness-110 active:scale-[0.98] transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                        <i data-lucide="rocket" class="w-4 h-4"></i>
                        Dispatch Shipment
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
