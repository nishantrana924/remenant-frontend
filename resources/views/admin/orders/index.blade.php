@extends('admin.layouts.app')

@section('content')
<div class="page-enter space-y-8" x-data="{ 
    search: '',
    selectedOrder: null,
    showDrawer: false,
    showShippingModal: false,
    shippingData: { id: null, tracking_id: '', courier_name: 'BlueDart' },
    
    statusColors: {
        'pending': 'bg-orange-50 text-orange-600 border-orange-100',
        'processing': 'bg-blue-50 text-blue-600 border-blue-100',
        'packed': 'bg-purple-50 text-purple-600 border-purple-100',
        'shipped': 'bg-indigo-50 text-indigo-600 border-indigo-100',
        'delivered': 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'cancelled': 'bg-rose-50 text-rose-600 border-rose-100'
    },

    updateStatus(id, data, reload = true) {
        fastSubmit(`/admin/orders/${id}/status`, {
            data: data,
            success: (res) => {
                toast(res.message);
                if (reload) setTimeout(() => location.reload(), 1000);
            }
        });
    },

    openShipping(order) {
        this.shippingData = { id: order.id, tracking_id: order.tracking_id || '', courier_name: order.courier_name || 'BlueDart' };
        this.showShippingModal = true;
    },

    saveShipping() {
        this.updateStatus(this.shippingData.id, { 
            delivery_status: 'shipped', 
            status: 'shipped',
            tracking_id: this.shippingData.tracking_id, 
            courier_name: this.shippingData.courier_name 
        });
        this.showShippingModal = false;
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Order Command Center</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Manage fulfillment, logistics, and customer success</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                <span class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-600">{{ $items->where('status', 'pending')->count() }} Awaiting Approval</span>
            </div>
        </div>
    </div>

    <!-- Filters & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="saas-card p-4 flex items-center justify-between bg-white border-l-4 border-orange-500">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pending</p>
                <h4 class="text-xl font-black text-slate-900">{{ $items->where('status', 'pending')->count() }}</h4>
            </div>
            <i data-lucide="clock" class="w-5 h-5 text-orange-200"></i>
        </div>
        <div class="saas-card p-4 flex items-center justify-between bg-white border-l-4 border-blue-500">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Processing</p>
                <h4 class="text-xl font-black text-slate-900">{{ $items->where('status', 'processing')->count() }}</h4>
            </div>
            <i data-lucide="package" class="w-5 h-5 text-blue-200"></i>
        </div>
        <div class="saas-card p-4 flex items-center justify-between bg-white border-l-4 border-indigo-500">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Shipped</p>
                <h4 class="text-xl font-black text-slate-900">{{ $items->where('status', 'shipped')->count() }}</h4>
            </div>
            <i data-lucide="truck" class="w-5 h-5 text-indigo-200"></i>
        </div>
        <div class="saas-card p-4 flex items-center justify-between bg-white border-l-4 border-emerald-500">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Completed</p>
                <h4 class="text-xl font-black text-slate-900">{{ $items->where('status', 'completed')->count() }}</h4>
            </div>
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-200"></i>
        </div>
    </div>

    <!-- Command Table -->
    <div class="saas-card p-0 overflow-hidden relative">
        <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/30">
            <div class="relative w-full md:w-96">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Search Order #, Name, or Email..." class="saas-input pl-12">
            </div>
            <div class="flex items-center gap-2">
                <button class="saas-btn-secondary px-4 py-2 text-xs flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i> Status
                </button>
                <button class="saas-btn-secondary px-4 py-2 text-xs flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i> Date
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th class="w-10"></th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Fulfillment</th>
                        <th>Payment</th>
                        <th class="text-right">Quick Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr class="hover:bg-orange-50/30 transition-all cursor-pointer group" 
                        x-show="!search || '{{ strtolower($item->order_number ?? $item->id) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->customer_name ?? $item->user->name ?? 'Guest') }}'.includes(search.toLowerCase())"
                        @click.self="selectedOrder = {{ $item->toJson() }}; showDrawer = true">
                        <td class="text-center">
                            <input type="checkbox" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </td>
                        <td @click="selectedOrder = {{ $item->toJson() }}; showDrawer = true">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-900 text-sm">#{{ $item->order_number ?? $item->id }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->created_at->format('M d, h:i A') }}</span>
                            </div>
                        </td>
                        <td @click="selectedOrder = {{ $item->toJson() }}; showDrawer = true">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500 uppercase">
                                    {{ substr($item->customer_name ?? $item->user->name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $item->customer_name ?? $item->user->name ?? 'Guest Customer' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $item->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td @click="selectedOrder = {{ $item->toJson() }}; showDrawer = true">
                            <span class="font-black text-slate-900 text-sm">₹{{ number_format($item->total_amount) }}</span>
                        </td>
                        <td>
                            <span :class="statusColors['{{ $item->status }}']" class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-1.5 rounded-full {{ $item->payment_status === 'paid' ? 'bg-emerald-500' : 'bg-orange-500' }}"></div>
                                <span class="text-[10px] font-black uppercase {{ $item->payment_status === 'paid' ? 'text-emerald-600' : 'text-orange-600' }}">
                                    {{ $item->payment_status }}
                                </span>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2" @click.stop>
                                @if($item->status === 'pending')
                                <button @click="updateStatus({{ $item->id }}, { status: 'processing', delivery_status: 'packed' })" class="h-8 px-3 rounded-lg bg-orange-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 transition-all">
                                    Approve
                                </button>
                                @elseif($item->status === 'processing' || $item->status === 'packed')
                                <button @click="openShipping({{ $item->toJson() }})" class="h-8 px-3 rounded-lg bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all">
                                    Ship Order
                                </button>
                                @endif
                                <a href="{{ route('admin.orders.show', $item->id) }}" class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Shopify-style Shipping Modal -->
    <template x-if="showShippingModal">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showShippingModal = false"></div>
            <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Shipment Logistics</h3>
                    <button @click="showShippingModal = false" class="text-slate-400 hover:text-rose-500"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Courier Partner</label>
                        <select x-model="shippingData.courier_name" class="saas-input">
                            <option value="BlueDart">BlueDart</option>
                            <option value="Delhivery">Delhivery</option>
                            <option value="DTDC">DTDC</option>
                            <option value="FedEx">FedEx</option>
                            <option value="XpressBees">XpressBees</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tracking ID / AWB Number</label>
                        <input type="text" x-model="shippingData.tracking_id" placeholder="e.g. 1234567890" class="saas-input font-bold text-lg">
                    </div>
                    <button @click="saveShipping()" class="saas-btn-primary w-full py-4 text-sm font-black uppercase tracking-widest mt-4">
                        Confirm & Dispatch
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Side Drawer Quick Preview -->
    <div x-show="showDrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full md:w-[450px] bg-white shadow-2xl z-[150] border-l border-slate-100 flex flex-col"
         x-cloak>
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-black text-slate-900" x-text="'Order #' + (selectedOrder ? (selectedOrder.order_number || selectedOrder.id) : '')"></h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1" x-text="selectedOrder ? new Date(selectedOrder.created_at).toLocaleString() : ''"></p>
            </div>
            <button @click="showDrawer = false" class="h-10 w-10 rounded-full hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-8 space-y-8">
            <!-- Customer Card -->
            <div class="space-y-4">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Customer Intelligence</h4>
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-orange-500 font-black">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-900" x-text="selectedOrder ? selectedOrder.customer_name : ''"></p>
                            <p class="text-xs text-slate-500" x-text="selectedOrder ? selectedOrder.email : ''"></p>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase">Phone</p>
                            <p class="text-xs font-bold text-slate-800" x-text="selectedOrder ? selectedOrder.phone : '-'"></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase">Pincode</p>
                            <p class="text-xs font-bold text-slate-800" x-text="selectedOrder ? selectedOrder.pincode : '-'"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logistics Card -->
            <div class="space-y-4">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Logistics Status</h4>
                <div class="space-y-3">
                    <template x-if="selectedOrder && selectedOrder.status === 'pending'">
                        <button @click="updateStatus(selectedOrder.id, { status: 'processing', delivery_status: 'packed' })" class="saas-btn-primary w-full py-4 text-xs font-black tracking-[0.2em] uppercase">Approve Transaction</button>
                    </template>
                    <template x-if="selectedOrder && (selectedOrder.status === 'processing' || selectedOrder.status === 'packed')">
                        <button @click="openShipping(selectedOrder)" class="w-full py-4 bg-indigo-500 text-white rounded-2xl text-xs font-black tracking-[0.2em] uppercase shadow-lg shadow-indigo-100">Dispatch Package</button>
                    </template>
                    <template x-if="selectedOrder && selectedOrder.status === 'shipped'">
                        <button @click="updateStatus(selectedOrder.id, { status: 'delivered', delivery_status: 'delivered' })" class="w-full py-4 bg-emerald-500 text-white rounded-2xl text-xs font-black tracking-[0.2em] uppercase shadow-lg shadow-emerald-100">Confirm Delivery</button>
                    </template>
                </div>
            </div>

            <!-- Detailed Link -->
            <a :href="'/admin/orders/' + (selectedOrder ? selectedOrder.id : '')" class="block w-full py-4 border-2 border-slate-100 rounded-2xl text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:bg-slate-50 transition-all">Deep Audit Record</a>
        </div>
    </div>
    
    <!-- Drawer Overlay -->
    <div x-show="showDrawer" @click="showDrawer = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[140]" x-cloak></div>
</div>
@endsection
