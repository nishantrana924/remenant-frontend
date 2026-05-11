@extends('admin.layouts.app')

@section('content')
<div class="page-enter space-y-8" x-data="{ 
    search: '',
    selectedOrder: null,
    showDrawer: false,
    showShippingModal: false,
    shippingData: { id: null, tracking_id: '', courier_name: 'BlueDart' },
    selectedItems: [],
    allItems: @js($items->pluck('id')),
    activeStatus: 'all',
    dateFilter: '',

    toggleAll() {
        if (this.selectedItems.length === this.allItems.length) {
            this.selectedItems = [];
        } else {
            this.selectedItems = [...this.allItems];
        }
    },

    async bulkAction(action, data = {}) {
        const title = action === 'delete' ? 'Delete Selected?' : 'Update Selected?';
        const msg = `Are you sure you want to ${action} ${this.selectedItems.length} orders?`;
        const url = action === 'delete' ? '{{ route("admin.orders.bulk-delete") }}' : '{{ route("admin.orders.bulk-update-status") }}';

        window.confirmAction(title, msg, async () => {
            window.fastSubmit(url, {
                data: { ids: this.selectedItems, ...data },
                success: (res) => {
                    window.toast(res.message);
                    this.selectedItems = [];
                    setTimeout(() => { if(window.up) up.reload(); else location.reload(); }, 500);
                }
            });
        });
    },
    
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
    },

    async pushToShiprocket(id) {
        window.confirmAction('Push to Shiprocket?', 'This will sync order details with Shiprocket and generate a shipment.', async () => {
            window.fastSubmit(`/admin/orders/${id}/ship-to-shiprocket`, {
                method: 'POST',
                success: (res) => {
                    window.toast(res.message);
                    setTimeout(() => location.reload(), 1000);
                },
                error: (err) => {
                    window.toast(err.message || 'Failed to push to Shiprocket', 'error');
                }
            });
        });
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Order Management</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Manage fulfillment, logistics, and customer success</p>
        </div>
        <div class="flex items-center gap-3">
            <template x-if="selectedItems.length > 0">
                <div class="flex items-center gap-2">
                    <button @click="bulkAction('approve', { status: 'processing', delivery_status: 'packed' })" class="saas-btn-secondary !text-orange-600 !border-orange-100 hover:!bg-orange-50 py-2 px-4 text-xs font-bold">
                        Approve Selected
                    </button>
                    <button @click="bulkAction('delete')" class="saas-btn-secondary !text-rose-500 !border-rose-100 hover:!bg-rose-50 py-2 px-4 text-xs font-bold">
                        Delete
                    </button>
                </div>
            </template>
            <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                <span class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">{{ $items->where('status', 'pending')->count() }} Awaiting Approval</span>
            </div>
        </div>
    </div>

    <!-- Filters & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="saas-card p-4 flex items-center justify-between bg-white border-l-4 border-orange-500">
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pending</p>
                <h4 class="text-xl font-bold text-slate-900">{{ $items->where('status', 'pending')->count() }}</h4>
            </div>
            <i data-lucide="clock" class="w-5 h-5 text-orange-200"></i>
        </div>
        <div class="saas-card p-4 flex items-center justify-between bg-white border-l-4 border-blue-500">
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Processing</p>
                <h4 class="text-xl font-bold text-slate-900">{{ $items->where('status', 'processing')->count() }}</h4>
            </div>
            <i data-lucide="package" class="w-5 h-5 text-blue-200"></i>
        </div>
        <div class="saas-card p-4 flex items-center justify-between bg-white border-l-4 border-indigo-500">
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Shipped</p>
                <h4 class="text-xl font-bold text-slate-900">{{ $items->where('status', 'shipped')->count() }}</h4>
            </div>
            <i data-lucide="truck" class="w-5 h-5 text-indigo-200"></i>
        </div>
        <div class="saas-card p-4 flex items-center justify-between bg-white border-l-4 border-emerald-500">
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Completed</p>
                <h4 class="text-xl font-bold text-slate-900">{{ $items->where('status', 'completed')->count() }}</h4>
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
                <div class="flex items-center gap-1 bg-white p-1 rounded-xl border border-slate-200 shadow-sm">
                    <button @click="activeStatus = 'all'" :class="activeStatus === 'all' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-3 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all">All</button>
                    <button @click="activeStatus = 'pending'" :class="activeStatus === 'pending' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-3 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all">Pending</button>
                    <button @click="activeStatus = 'shipped'" :class="activeStatus === 'shipped' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-3 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all">Shipped</button>
                </div>
                <div class="relative">
                    <i data-lucide="calendar" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                    <input type="date" x-model="dateFilter" class="saas-input pl-9 py-1.5 text-[10px] font-bold uppercase tracking-widest border-slate-200 w-40">
                    <template x-if="dateFilter">
                        <button @click="dateFilter = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-rose-500">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th class="w-10">
                            <input type="checkbox" @change="toggleAll()" :checked="selectedItems.length === allItems.length && allItems.length > 0" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </th>
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
                    <tr class="transition-all cursor-pointer group" 
                        :class="selectedItems.includes({{ $item->id }}) ? 'bg-orange-50/70' : 'hover:bg-orange-50/30'"
                        x-show="(!search || '{{ strtolower($item->order_number ?? $item->id) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->customer_name ?? $item->user->name ?? 'Guest') }}'.includes(search.toLowerCase())) && (activeStatus === 'all' || '{{ $item->status }}' === activeStatus) && (!dateFilter || '{{ $item->created_at->format('Y-m-d') }}' === dateFilter)"
                        @click.self="selectedOrder = {{ $item->toJson() }}; showDrawer = true">
                        <td class="text-center" @click.stop>
                            <input type="checkbox" x-model="selectedItems" value="{{ $item->id }}" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </td>
                        <td @click="selectedOrder = {{ $item->toJson() }}; showDrawer = true">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 text-sm">#{{ $item->order_number ?? $item->id }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->created_at->format('M d, h:i A') }}</span>
                            </div>
                        </td>
                        <td @click="selectedOrder = {{ $item->toJson() }}; showDrawer = true">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 uppercase">
                                    {{ substr($item->customer_name ?? $item->user->name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $item->customer_name ?? $item->user->name ?? 'Guest Customer' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $item->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td @click="selectedOrder = {{ $item->toJson() }}; showDrawer = true">
                            <span class="font-bold text-slate-900 text-sm">₹{{ number_format($item->total_amount) }}</span>
                        </td>
                        <td>
                            <span :class="statusColors['{{ $item->status }}']" class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-1.5 rounded-full {{ $item->payment_status === 'paid' ? 'bg-emerald-500' : 'bg-orange-500' }}"></div>
                                <span class="text-[10px] font-bold uppercase {{ $item->payment_status === 'paid' ? 'text-emerald-600' : 'text-orange-600' }}">
                                    {{ $item->payment_status }}
                                </span>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2" @click.stop>
                                @if($item->status === 'pending')
                                <button @click="updateStatus({{ $item->id }}, { status: 'processing', delivery_status: 'packed' })" class="h-8 px-3 rounded-lg bg-orange-600 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-orange-700 transition-all">
                                    Approve
                                </button>
                                @elseif($item->status === 'processing' || $item->status === 'packed')
                                <div class="flex items-center gap-1">
                                    <button @click="pushToShiprocket({{ $item->id }})" class="h-8 px-3 rounded-lg bg-orange-600 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-orange-700 transition-all flex items-center gap-2" title="Auto-generate shipment via Shiprocket">
                                        <i data-lucide="rocket" class="w-3 h-3"></i>
                                        Shiprocket
                                    </button>
                                    <button @click="openShipping({{ $item->toJson() }})" class="h-8 px-3 rounded-lg bg-slate-800 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-slate-900 transition-all" title="Enter manual tracking details">
                                        <i data-lucide="user-cog" class="w-3 h-3 mr-1"></i>
                                        Manual
                                    </button>
                                </div>
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
                    <h3 class="text-xl font-bold text-slate-900 uppercase tracking-tight">Shipment Details</h3>
                    <button @click="showShippingModal = false" class="text-slate-400 hover:text-rose-500"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Courier Partner</label>
                        <select x-model="shippingData.courier_name" class="saas-input">
                            <option value="BlueDart">BlueDart</option>
                            <option value="Delhivery">Delhivery</option>
                            <option value="DTDC">DTDC</option>
                            <option value="FedEx">FedEx</option>
                            <option value="XpressBees">XpressBees</option>
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
                <h3 class="text-lg font-bold text-slate-900" x-text="'Order #' + (selectedOrder ? (selectedOrder.order_number || selectedOrder.id) : '')"></h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1" x-text="selectedOrder ? new Date(selectedOrder.created_at).toLocaleString() : ''"></p>
            </div>
            <button @click="showDrawer = false" class="h-10 w-10 rounded-full hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-8 space-y-8">
            <!-- Customer Card -->
            <div class="space-y-4">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Customer Info</h4>
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-orange-500 font-bold">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900" x-text="selectedOrder ? selectedOrder.customer_name : ''"></p>
                            <p class="text-xs text-slate-500" x-text="selectedOrder ? selectedOrder.email : ''"></p>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">Phone</p>
                            <p class="text-xs font-bold text-slate-800" x-text="selectedOrder ? selectedOrder.phone : '-'"></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">Pincode</p>
                            <p class="text-xs font-bold text-slate-800" x-text="selectedOrder ? selectedOrder.pincode : '-'"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logistics Card -->
            <div class="space-y-4">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Shipping Status</h4>
                <div class="space-y-3">
                    <template x-if="selectedOrder && selectedOrder.status === 'pending'">
                        <button @click="updateStatus(selectedOrder.id, { status: 'processing', delivery_status: 'packed' })" class="saas-btn-primary w-full py-4 text-xs font-bold tracking-[0.2em] uppercase">Approve Order</button>
                    </template>
                    <template x-if="selectedOrder && (selectedOrder.status === 'processing' || selectedOrder.status === 'packed')">
                        <button @click="openShipping(selectedOrder)" class="w-full py-4 bg-orange-600 text-white rounded-2xl text-xs font-bold tracking-[0.2em] uppercase shadow-lg shadow-orange-100">Dispatch Order</button>
                    </template>
                    <template x-if="selectedOrder && selectedOrder.status === 'shipped'">
                        <button @click="updateStatus(selectedOrder.id, { status: 'delivered', delivery_status: 'delivered' })" class="w-full py-4 bg-emerald-500 text-white rounded-2xl text-xs font-bold tracking-[0.2em] uppercase shadow-lg shadow-emerald-100">Confirm Delivery</button>
                    </template>
                </div>
            </div>

            <!-- Detailed Link -->
            <a :href="'/admin/orders/' + (selectedOrder ? selectedOrder.id : '')" class="block w-full py-4 border-2 border-slate-100 rounded-2xl text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] hover:bg-slate-50 transition-all">View Full Details</a>
        </div>
    </div>
    
    <!-- Drawer Overlay -->
    <div x-show="showDrawer" @click="showDrawer = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[140]" x-cloak></div>
</div>
@endsection
