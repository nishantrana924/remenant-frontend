@extends('admin.layouts.app')

@section('content')
<div class="page-enter space-y-8" x-data="{ 
    search: '',
    selectedOrder: null,
    showDrawer: false,
    selectedItems: [],
    maxBulk: 20,
    allItems: @js($items->pluck('id')),
    activeStatus: 'all',
    dateFilter: '',

    showBulkShipModal: false,
    bulkCouriers: [],
    bulkPackagingWeight: 30,
    bulkSelectedCourier: '',
    showBulkSummaryModal: false,
    bulkSummary: {},

    init() {
        this.$watch('showCourierModal', value => this.toggleScroll());
    },

    toggleScroll() {
        const mc = document.getElementById('main-content');
        if (mc) {
            mc.style.overflow = this.showCourierModal ? 'hidden' : '';
        }
    },

    toggleAll() {
        if (this.selectedItems.length === Math.min(this.allItems.length, this.maxBulk)) {
            this.selectedItems = [];
        } else {
            this.selectedItems = this.allItems.slice(0, this.maxBulk);
            if (this.allItems.length > this.maxBulk) {
                window.toast(`Only first ${this.maxBulk} orders selected. Max bulk limit is ${this.maxBulk}.`, 'info');
            }
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
        'pending':              'bg-orange-50 text-orange-600 border-orange-100',
        'processing':           'bg-blue-50 text-blue-600 border-blue-100',
        'packed':               'bg-purple-50 text-purple-600 border-purple-100',
        'shipped':              'bg-indigo-50 text-indigo-600 border-indigo-100',
        'out_for_delivery':     'bg-sky-50 text-sky-600 border-sky-100',
        'delivered':            'bg-emerald-50 text-emerald-600 border-emerald-100',
        'failed_delivery':      'bg-amber-50 text-amber-600 border-amber-100',
        'returned':             'bg-rose-50 text-rose-600 border-rose-100',
        'cancelled':            'bg-red-50 text-red-600 border-red-100',
        'cancellation_requested': 'bg-rose-50 text-rose-500 border-rose-100',
        'lost':                 'bg-gray-50 text-gray-600 border-gray-100',
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

    showCourierModal: false,
    shippingConfig: { id: null, weight: 250, length: 17, breadth: 10, height: 5, courier_id: '' },
    couriers: [],
    fetchingRates: false,

    openCourierSelection(orderId, dims = null) {
        this.shippingConfig.id = orderId;
        this.shippingConfig.weight = dims && dims.weight ? dims.weight : 250;
        this.shippingConfig.length = dims && dims.length ? dims.length : 17;
        this.shippingConfig.breadth = dims && dims.breadth ? dims.breadth : 10;
        this.shippingConfig.height = dims && dims.height ? dims.height : 5;
        this.shippingConfig.courier_id = '';
        this.couriers = [];
        this.showCourierModal = true;
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

    confirmShipment() {
        if(!this.shippingConfig.courier_id) return window.toast('Please select a courier', 'error');
        
        window.fastSubmit(`/admin/orders/${this.shippingConfig.id}/ship-to-nimbuspost`, {
            method: 'POST',
            data: this.shippingConfig,
            success: (res) => {
                window.toast(res.message);
                this.showCourierModal = false;
                setTimeout(() => location.reload(), 1000);
            },
            error: (err) => {
                window.toast(err.message || 'Failed to push to NimbusPost', 'error');
            }
        });
    },

    async cancelNimbusPost(id) {
        window.confirmAction('Cancel Shipment?', 'This will cancel the order on NimbusPost. Are you sure?', async () => {
            window.fastSubmit(`/admin/orders/${id}/cancel-nimbuspost`, {
                method: 'POST',
                success: (res) => {
                    window.toast(res.message);
                    setTimeout(() => location.reload(), 1000);
                },
                error: (err) => {
                    window.toast(err.message || 'Failed to cancel shipment', 'error');
                }
            });
        });
    },

    async schedulePickupBulk() {
        window.confirmAction('Schedule Bulk Pickup?', `Request pickup for ${this.selectedItems.length} orders?`, async () => {
            window.fastSubmit('{{ route("admin.orders.bulk-pickup") }}', {
                method: 'POST',
                data: { ids: this.selectedItems },
                success: (res) => {
                    window.toast(res.message);
                    this.selectedItems = [];
                    setTimeout(() => location.reload(), 1000);
                },
                error: (err) => {
                    const msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : (err.message || 'Failed to schedule pickup');
                    window.toast(msg, 'error');
                }
            });
        });
    },

    async openBulkShipModal() {
        this.showBulkShipModal = true;
        if (this.bulkCouriers.length === 0) {
            try {
                const response = await axios.get('{{ route("admin.orders.fetch-couriers") }}');
                if (response.data && response.data.success) {
                    this.bulkCouriers = response.data.couriers;
                }
            } catch (e) {
                window.toast('Failed to fetch couriers', 'error');
            }
        }
    },

    async bulkShip() {
        if (!this.bulkSelectedCourier) {
            window.toast('Please select a courier first', 'error');
            return;
        }

        window.fastSubmit('{{ route("admin.orders.bulk-ship-to-nimbuspost") }}', {
            method: 'POST',
            data: { 
                ids: this.selectedItems, 
                courier_id: this.bulkSelectedCourier, 
                packaging_weight: this.bulkPackagingWeight 
            },
            success: (res) => {
                this.showBulkShipModal = false;
                this.bulkSummary = {
                    total: res.total || 0,
                    successful: res.successful || 0,
                    failed: res.failed || 0,
                    errors: res.errors || [],
                    ids: this.selectedItems.join(',')
                };
                this.showBulkSummaryModal = true;
                this.selectedItems = [];
            },
            error: (err) => {
                const msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : (err.message || 'Failed to bulk ship');
                window.toast(msg, 'error');
            }
        });
    },

    async bulkCancelShipment() {
        window.confirmAction('Bulk Cancel Shipments?', `This will cancel ${this.selectedItems.length} shipments on NimbusPost. Are you sure?`, async () => {
            window.fastSubmit('{{ route("admin.orders.bulk-cancel-nimbuspost") }}', {
                method: 'POST',
                data: { ids: this.selectedItems },
                success: (res) => {
                    this.bulkSummary = {
                        total: res.total || 0,
                        successful: res.successful || 0,
                        failed: res.failed || 0,
                        errors: res.errors || [],
                        ids: '' // We don't need bulk label download for cancel
                    };
                    this.showBulkSummaryModal = true;
                    this.selectedItems = [];
                },
                error: (err) => {
                    const msg = err.response && err.response.data && err.response.data.message ? err.response.data.message : (err.message || 'Failed to bulk cancel shipments');
                    window.toast(msg, 'error');
                }
            });
        });
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Order Management</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">Manage fulfillment, logistics, and customer success</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <template x-if="selectedItems.length > 0">
                <div class="flex flex-col gap-2">
                    {{-- Selection Info Banner --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-orange-50 rounded-xl border border-orange-100">
                        <i data-lucide="check-square" class="w-3.5 h-3.5 text-orange-500 shrink-0"></i>
                        <span class="text-[10px] font-bold text-orange-600" x-text="selectedItems.length + ' selected'"></span>
                        <span class="text-[10px] text-slate-400">•</span>
                        <span class="text-[10px] font-medium text-slate-500">Max <span class="font-bold text-orange-600" x-text="maxBulk"></span> orders per bulk action</span>
                        <button @click="selectedItems = []" class="ml-1 text-slate-300 hover:text-rose-500 transition-colors" title="Clear selection">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    </div>
                    {{-- Bulk Action Buttons --}}
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <button @click="openBulkShipModal()" class="saas-btn-secondary !text-blue-600 !border-blue-100 hover:!bg-blue-50 py-2 px-3 text-xs font-bold flex items-center gap-1.5" title="Manual Courier & Ship">
                            <i data-lucide="rocket" class="w-3.5 h-3.5"></i>
                            Bulk Ship
                        </button>
                        <button @click="bulkAction('approve', { status: 'processing', delivery_status: 'packed' })" class="saas-btn-secondary !text-emerald-600 !border-emerald-100 hover:!bg-emerald-50 py-2 px-3 text-xs font-bold flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                            Approve
                        </button>
                        <button @click="bulkCancelShipment()" class="saas-btn-secondary !text-rose-600 !border-rose-100 hover:!bg-rose-50 py-2 px-3 text-xs font-bold flex items-center gap-1.5" title="Cancel Shipments on NimbusPost">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                            Cancel Shipment
                        </button>
                        <button @click="bulkAction('delete')" class="saas-btn-secondary !text-slate-500 !border-slate-200 hover:!bg-rose-50 hover:!text-rose-600 hover:!border-rose-100 py-2 px-3 text-xs font-bold flex items-center gap-1.5">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </template>
            <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                <span class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">{{ $items->where('status', 'pending')->count() }} Awaiting</span>
            </div>
        </div>
    </div>

    <!-- Filters & Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
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
        <div class="p-4 border-b border-slate-100 flex flex-col gap-3 bg-slate-50/30">
            <div class="relative w-full">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Search Order #, Name, Email..." class="saas-input pl-12">
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1 bg-white p-1 rounded-xl border border-slate-200 shadow-sm overflow-x-auto whitespace-nowrap scrollbar-hide flex-1">
                    <button @click="activeStatus = 'all'" :class="activeStatus === 'all' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">All</button>
                    <button @click="activeStatus = 'pending'" :class="activeStatus === 'pending' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Pending</button>
                    <button @click="activeStatus = 'processing'" :class="activeStatus === 'processing' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Processing</button>
                    <button @click="activeStatus = 'shipped'" :class="activeStatus === 'shipped' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Shipped</button>
                    <button @click="activeStatus = 'cancellation_requested'" :class="activeStatus === 'cancellation_requested' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Cancel Req</button>
                    <button @click="activeStatus = 'cancelled'" :class="activeStatus === 'cancelled' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Cancelled</button>
                    <div class="w-px h-4 bg-slate-200 mx-0.5 shrink-0"></div>
                    <button @click="activeStatus = 'refund_pending'" :class="activeStatus === 'refund_pending' ? 'bg-rose-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Ref:Pending</button>
                    <button @click="activeStatus = 'refund_processing'" :class="activeStatus === 'refund_processing' ? 'bg-rose-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Ref:Processing</button>
                    <button @click="activeStatus = 'refund_completed'" :class="activeStatus === 'refund_completed' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Ref:Done</button>
                    <button @click="activeStatus = 'refund_failed'" :class="activeStatus === 'refund_failed' ? 'bg-rose-600 text-white shadow-md' : 'text-slate-400'" class="px-2.5 py-1.5 text-[9px] font-bold uppercase tracking-widest rounded-lg transition-all shrink-0">Ref:Failed</button>
                </div>
                <div class="relative shrink-0">
                    <i data-lucide="calendar" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                    <input type="date" x-model="dateFilter" class="saas-input pl-9 py-1.5 text-[10px] font-bold uppercase tracking-widest border-slate-200 w-36 sm:w-40">
                    <template x-if="dateFilter">
                        <button @click="dateFilter = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-rose-500">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-slate-100">
            @foreach($items as $item)
            <div class="p-4 transition-all"
                x-show="(!search || '{{ strtolower($item->order_number ?? $item->id) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->customer_name ?? $item->user->name ?? 'Guest') }}'.includes(search.toLowerCase())) && (activeStatus === 'all' || (activeStatus.startsWith('refund_') ? '{{ $item->refund_status }}' === activeStatus.replace('refund_', '') : '{{ $item->status }}' === activeStatus)) && (!dateFilter || '{{ $item->created_at->format('Y-m-d') }}' === dateFilter)"
                :class="selectedItems.includes({{ $item->id }}) ? 'bg-orange-50' : 'bg-white hover:bg-orange-50/30'">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <input type="checkbox"
                            :checked="selectedItems.includes({{ $item->id }})"
                            @change="
                                if ($event.target.checked) {
                                    if (selectedItems.length >= maxBulk) {
                                        $event.target.checked = false;
                                        window.toast('Maximum ' + maxBulk + ' orders can be selected at once.', 'error');
                                    } else {
                                        selectedItems = [...selectedItems, {{ $item->id }}];
                                    }
                                } else {
                                    selectedItems = selectedItems.filter(id => id !== {{ $item->id }});
                                }
                            "
                            class="mt-1 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-slate-900 text-sm">#{{ $item->order_number ?? $item->id }}</span>
                                <x-status-badge :status="$item->status" size="xs" />
                                <div class="flex items-center gap-1">
                                    <div class="h-1.5 w-1.5 rounded-full {{ $item->payment_status === 'paid' ? 'bg-emerald-500' : 'bg-orange-500' }}"></div>
                                    <span class="text-[9px] font-bold uppercase {{ $item->payment_status === 'paid' ? 'text-emerald-600' : 'text-orange-600' }}">{{ $item->payment_status }}</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->created_at->format('M d, h:i A') }}</p>
                            <p class="text-sm font-bold text-slate-800 mt-1">{{ $item->customer_name ?? $item->user->name ?? 'Guest' }}</p>
                            <p class="text-[10px] text-slate-500">{{ $item->email }}</p>
                            <p class="text-sm font-bold text-orange-600 mt-1">₹{{ number_format($item->total_amount) }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5 shrink-0">
                        @if($item->status === 'pending')
                        <button @click="updateStatus({{ $item->id }}, { status: 'processing', delivery_status: 'packed' })" class="h-8 px-3 rounded-lg bg-orange-600 text-white text-[10px] font-bold uppercase hover:bg-orange-700 transition-all">
                            Approve
                        </button>
                        @elseif($item->status === 'processing' || $item->status === 'packed')
                            @if(!$item->shipment || !$item->shipment->awb_number)
                            <button @click="openCourierSelection({{ $item->id }}, {{ json_encode($item->calculated_dimensions) }})" class="h-8 px-3 rounded-lg bg-orange-600 text-white text-[10px] font-bold uppercase hover:bg-orange-700 transition-all flex items-center gap-1">
                                <i data-lucide="rocket" class="w-3 h-3"></i> Ship
                            </button>
                            @else
                            <a href="{{ route('admin.orders.nimbus-label', $item->id) }}" target="_blank" class="h-8 px-3 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-bold uppercase hover:bg-blue-100 transition-all flex items-center gap-1">
                                <i data-lucide="printer" class="w-3 h-3"></i> Print
                            </a>
                            @endif
                        @endif
                        <button @click="selectedOrder = {{ $item->append('calculated_dimensions')->toJson() }}; showDrawer = true" class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
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
                        x-show="(!search || '{{ strtolower($item->order_number ?? $item->id) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->customer_name ?? $item->user->name ?? 'Guest') }}'.includes(search.toLowerCase())) && (activeStatus === 'all' || (activeStatus.startsWith('refund_') ? '{{ $item->refund_status }}' === activeStatus.replace('refund_', '') : '{{ $item->status }}' === activeStatus)) && (!dateFilter || '{{ $item->created_at->format('Y-m-d') }}' === dateFilter)"
                        @click.self="selectedOrder = {{ $item->append('calculated_dimensions')->toJson() }}; showDrawer = true">
                        <td class="text-center" @click.stop>
                            <input type="checkbox" 
                                :checked="selectedItems.includes({{ $item->id }})"
                                @change="
                                    if ($event.target.checked) {
                                        if (selectedItems.length >= maxBulk) {
                                            $event.target.checked = false;
                                            window.toast('Maximum ' + maxBulk + ' orders can be selected at once.', 'error');
                                        } else {
                                            selectedItems = [...selectedItems, {{ $item->id }}];
                                        }
                                    } else {
                                        selectedItems = selectedItems.filter(id => id !== {{ $item->id }});
                                    }
                                "
                                class="rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </td>
                        <td @click="selectedOrder = {{ $item->append('calculated_dimensions')->toJson() }}; showDrawer = true">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 text-sm">#{{ $item->order_number ?? $item->id }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->created_at->format('M d, h:i A') }}</span>
                            </div>
                        </td>
                        <td @click="selectedOrder = {{ $item->append('calculated_dimensions')->toJson() }}; showDrawer = true">
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
                        <td @click="selectedOrder = {{ $item->append('calculated_dimensions')->toJson() }}; showDrawer = true">
                            <span class="font-bold text-slate-900 text-sm">₹{{ number_format($item->total_amount) }}</span>
                        </td>
                        <td>
                            <x-status-badge :status="$item->status" size="sm" />
                            @if($item->delivery_status && $item->delivery_status !== $item->status)
                                <div class="mt-1">
                                    <x-status-badge :status="$item->delivery_status" size="xs" />
                                </div>
                            @endif
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
                                    @if($item->shipment && $item->shipment->awb_number)
                                        <div class="flex flex-col items-end mr-2">
                                            <span class="text-[9px] font-black text-blue-600 uppercase tracking-tighter">AWB: {{ $item->shipment->awb_number }}</span>
                                            <span class="text-[8px] text-slate-400 uppercase font-bold">{{ $item->shipment->courier_name }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('admin.orders.nimbus-label', $item->id) }}" target="_blank" class="h-8 px-3 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-bold uppercase tracking-widest hover:bg-blue-100 transition-all flex items-center gap-2" title="Print Shipping Label">
                                                <i data-lucide="printer" class="w-3 h-3"></i>
                                                Print
                                            </a>
                                            <button @click="cancelNimbusPost({{ $item->id }})" class="h-8 px-3 rounded-lg bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-bold uppercase tracking-widest hover:bg-rose-100 transition-all" title="Cancel NimbusPost Shipment">
                                                Cancel
                                            </button>
                                        </div>
                                    @else
                                        <button @click="openCourierSelection({{ $item->id }}, {{ json_encode($item->calculated_dimensions) }})" class="h-8 px-3 rounded-lg bg-orange-600 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-orange-700 transition-all flex items-center gap-2" title="Select courier & generate shipment">
                                            <i data-lucide="rocket" class="w-3 h-3"></i>
                                            NimbusPost
                                        </button>
                                    @endif
                                </div>
                                @elseif($item->status === 'shipped')
                                    <div class="flex items-center gap-1">
                                        <div class="flex flex-col items-end mr-2">
                                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-tighter">AWB: {{ $item->tracking_id }}</span>
                                            <span class="text-[8px] text-slate-400 uppercase font-bold">{{ $item->courier_name }}</span>
                                        </div>
                                        @if($item->shipment && $item->shipment->nimbus_shipment_id)
                                            <a href="{{ route('admin.orders.nimbus-label', $item->id) }}" target="_blank" class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center hover:bg-blue-100 transition-all" title="Print Shipping Label">
                                                <i data-lucide="printer" class="w-4 h-4"></i>
                                            </a>
                                        @endif
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

    <!-- Side Drawer Quick Preview -->
    <div x-show="showDrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full sm:w-[400px] md:w-[450px] bg-white shadow-2xl z-[150] border-l border-slate-100 flex flex-col"
         x-cloak>
        <div class="p-4 sm:p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900" x-text="'Order #' + (selectedOrder ? (selectedOrder.order_number || selectedOrder.id) : '')"></h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1" x-text="selectedOrder ? new Date(selectedOrder.created_at).toLocaleString() : ''"></p>
            </div>
            <button @click="showDrawer = false" class="h-10 w-10 rounded-full hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">
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
                    <template x-if="selectedOrder && selectedOrder.status === 'cancellation_requested'">
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="window.fastSubmit(`/admin/orders/${selectedOrder.id}/approve-cancellation`, { method: 'POST', success: (res) => { window.toast(res.message); setTimeout(() => location.reload(), 1000); }})" class="w-full py-4 bg-rose-600 text-white rounded-2xl text-[10px] font-bold tracking-[0.1em] uppercase shadow-lg shadow-rose-100 hover:bg-rose-700 transition-all">Approve Cancellation</button>
                            <button @click="window.fastSubmit(`/admin/orders/${selectedOrder.id}/reject-cancellation`, { method: 'POST', success: (res) => { window.toast(res.message); setTimeout(() => location.reload(), 1000); }})" class="w-full py-4 bg-white border border-slate-200 text-slate-700 rounded-2xl text-[10px] font-bold tracking-[0.1em] uppercase hover:bg-slate-50 transition-all">Reject Cancellation</button>
                        </div>
                    </template>
                    <template x-if="selectedOrder && (selectedOrder.status === 'processing' || selectedOrder.status === 'packed')">
                        <button @click="openCourierSelection(selectedOrder.id, selectedOrder.calculated_dimensions)" class="w-full py-4 bg-orange-600 text-white rounded-2xl text-xs font-bold tracking-[0.2em] uppercase shadow-lg shadow-orange-100 hover:bg-orange-700 transition-all">Select Courier & Ship</button>
                    </template>
                    <template x-if="selectedOrder && selectedOrder.status === 'shipped'">
                        <button @click="updateStatus(selectedOrder.id, { status: 'delivered', delivery_status: 'delivered' })" class="w-full py-4 bg-emerald-500 text-white rounded-2xl text-xs font-bold tracking-[0.2em] uppercase shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition-all">Confirm Delivery</button>
                    </template>
                </div>
            </div>

            <!-- Cancellation Info Card -->
            <template x-if="selectedOrder && (selectedOrder.status === 'cancelled' || selectedOrder.status === 'cancellation_requested')">
                <div class="space-y-4">
                    <h4 class="text-[10px] font-bold text-rose-500 uppercase tracking-widest">Cancellation Details</h4>
                    <div class="bg-rose-50 rounded-2xl p-6 border border-rose-100">
                        <div class="space-y-3">
                            <div>
                                <p class="text-[9px] font-bold text-rose-400 uppercase">Reason</p>
                                <p class="text-xs font-bold text-rose-900" x-text="selectedOrder.cancellation_reason || 'N/A'"></p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[9px] font-bold text-rose-400 uppercase">Cancelled By</p>
                                    <p class="text-xs font-bold text-rose-900" x-text="selectedOrder.cancelled_by || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-rose-400 uppercase">Cancelled At</p>
                                    <p class="text-xs font-bold text-rose-900" x-text="selectedOrder.cancelled_at ? new Date(selectedOrder.cancelled_at).toLocaleString() : '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-rose-400 uppercase">Refund Status</p>
                                    <p class="text-xs font-bold uppercase tracking-widest" :class="selectedOrder.refund_status === 'completed' ? 'text-emerald-600' : 'text-orange-600'" x-text="selectedOrder.refund_status || 'N/A'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-rose-400 uppercase">Refund Amount</p>
                                    <p class="text-xs font-bold text-slate-800" x-text="selectedOrder.refund_amount ? '₹' + selectedOrder.refund_amount : 'N/A'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-rose-400 uppercase">Razorpay Refund ID</p>
                                    <p class="text-[10px] font-mono text-slate-600 break-all" x-text="selectedOrder.razorpay_refund_id || 'N/A'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-rose-400 uppercase">Refund ARN</p>
                                    <p class="text-[10px] font-mono text-slate-600 break-all" x-text="selectedOrder.refund_arn || 'N/A'"></p>
                                </div>
                            </div>
                            
                            <template x-if="selectedOrder.refund_status && selectedOrder.refund_status !== 'none' && selectedOrder.refund_status !== 'completed' && selectedOrder.refund_status !== 'failed' && selectedOrder.razorpay_refund_id">
                                <div class="mt-4 pt-4 border-t border-rose-100">
                                    <button @click="window.fastSubmit(`/admin/orders/${selectedOrder.id}/sync-refund`, { method: 'POST', success: (res) => { window.toast(res.message); setTimeout(() => location.reload(), 1000); }, error: (err) => window.toast(err.response?.data?.message || 'Failed to sync refund', 'error') })"
                                            class="w-full py-3 bg-rose-600 text-white rounded-xl text-[10px] font-bold tracking-[0.1em] uppercase hover:bg-rose-700 transition-all flex items-center justify-center gap-1.5 shadow-lg shadow-rose-100">
                                        <i data-lucide="rotate-cw" class="w-3.5 h-3.5"></i>
                                        Sync Refund Status from Razorpay
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Coupon Info Card -->
            <template x-if="selectedOrder && selectedOrder.coupon_code">
                <div class="space-y-4">
                    <h4 class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Coupon & Discounts</h4>
                    <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100">
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[9px] font-bold text-emerald-600 uppercase">Coupon Code</p>
                                    <p class="text-xs font-bold text-emerald-900 font-mono" x-text="selectedOrder.coupon_code"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-emerald-600 uppercase">Type & Value</p>
                                    <p class="text-xs font-bold text-emerald-900" x-text="(selectedOrder.coupon_discount_type === 'percentage' ? selectedOrder.coupon_discount_value + '%' : '₹' + selectedOrder.coupon_discount_value) + ' (' + selectedOrder.coupon_discount_type + ')'"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-emerald-600 uppercase">Discount Amount</p>
                                    <p class="text-xs font-bold text-emerald-900" x-text="'₹' + selectedOrder.discount_amount"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-emerald-600 uppercase">Subtotal / Final</p>
                                    <p class="text-xs font-bold text-emerald-900" x-text="'₹' + (selectedOrder.subtotal || '-') + ' / ₹' + selectedOrder.total_amount"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Detailed Link -->
            <a :href="'/admin/orders/' + (selectedOrder ? selectedOrder.id : '')" class="block w-full py-4 border-2 border-slate-100 rounded-2xl text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] hover:bg-slate-50 transition-all">View Full Details</a>
        </div>
    </div>
    
    <!-- Drawer Overlay -->
    <div x-show="showDrawer" @click="showDrawer = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[140]" x-cloak></div>

    <!-- Courier Selection Modal -->
    <template x-teleport="body">
        <div x-show="showCourierModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCourierModal = false"></div>
            <div class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl p-8 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="flex items-center justify-between mb-6 shrink-0">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Ship Order</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Select Courier & Generate Label</p>
                    </div>
                    <button @click="showCourierModal = false" class="h-10 w-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                
                <div class="overflow-y-auto pr-2 space-y-6 flex-1">
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
                                <label class="relative flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition-all group"
                                       :class="shippingConfig.courier_id == c.id ? 'border-orange-500 bg-orange-50/50' : 'border-slate-100 hover:border-orange-200 bg-white'">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="courier" :value="c.id" x-model="shippingConfig.courier_id" class="h-4 w-4 text-orange-500 border-slate-300 focus:ring-orange-500">
                                        <div>
                                            <p class="text-xs font-black text-slate-900 uppercase" x-text="c.name"></p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">ETA: <span x-text="c.edd || 'N/A'"></span></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-black text-emerald-600">₹<span x-text="c.total_charges"></span></p>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase">Shipping Charge</p>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100 shrink-0">
                    <button @click="confirmShipment()" :disabled="!shippingConfig.courier_id" class="w-full bg-orange-600 text-white rounded-2xl py-4 text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-orange-200 hover:brightness-110 active:scale-[0.98] transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                        <i data-lucide="rocket" class="w-4 h-4"></i>
                        Dispatch Shipment
                    </button>
                </div>
            </div>
        </div>
    </template>
    <!-- Bulk Ship Modal -->
    <div x-show="showBulkShipModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" x-transition.opacity>
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden" @click.away="showBulkShipModal = false" x-transition.scale.95>
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900">Bulk Shipping</h3>
                    <p class="text-xs text-slate-500 font-medium">Configure shipment for <span x-text="selectedItems.length" class="text-orange-600 font-bold"></span> orders</p>
                </div>
                <button @click="showBulkShipModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Packaging Weight -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Packaging Weight (gm)</label>
                    <input type="number" x-model="bulkPackagingWeight" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all">
                    <p class="text-[10px] text-slate-400 mt-1">This weight is added to the dynamic sum of each order's products.</p>
                </div>

                <!-- Courier Selection -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Select Courier</label>
                    
                    <template x-if="bulkCouriers.length === 0">
                        <div class="h-10 flex items-center gap-2 text-sm text-slate-500">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin text-orange-600"></i> Fetching couriers...
                        </div>
                    </template>
                    
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="courier in bulkCouriers" :key="courier.id">
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 cursor-pointer transition-all hover:border-orange-200"
                                :class="bulkSelectedCourier == courier.id ? 'bg-orange-50/50 border-orange-500 ring-1 ring-orange-500' : 'bg-white'">
                                <input type="radio" x-model="bulkSelectedCourier" :value="courier.id" class="text-orange-500 focus:ring-orange-500 border-slate-300">
                                <div>
                                    <p class="text-sm font-bold text-slate-900" x-text="courier.name"></p>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                <button @click="showBulkShipModal = false" class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all">Cancel</button>
                <button @click="bulkShip()" class="flex-1 px-4 py-2.5 bg-orange-600 text-white text-sm font-bold rounded-xl hover:bg-orange-700 transition-all flex justify-center items-center gap-2">
                    Generate AWBs
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Summary Modal -->
    <div x-show="showBulkSummaryModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" x-transition.opacity>
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden" @click.away="showBulkSummaryModal = false" x-transition.scale.95>
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900">Bulk Shipping Completed</h3>
                    <p class="text-xs text-slate-500 font-medium">Processed <span x-text="bulkSummary.total" class="font-bold"></span> orders</p>
                </div>
                <button @click="showBulkSummaryModal = false; location.reload()" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Summary Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex items-center gap-4">
                        <div class="h-10 w-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Successful</p>
                            <p class="text-2xl font-black text-emerald-700" x-text="bulkSummary.successful"></p>
                        </div>
                    </div>
                    <div class="bg-red-50 border border-red-100 p-4 rounded-xl flex items-center gap-4">
                        <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest">Failed</p>
                            <p class="text-2xl font-black text-red-700" x-text="bulkSummary.failed"></p>
                        </div>
                    </div>
                </div>

                <!-- Error Report -->
                <template x-if="bulkSummary.errors && bulkSummary.errors.length > 0">
                    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                        <h4 class="text-xs font-bold text-red-800 mb-2 flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i> Failure Report
                        </h4>
                        <ul class="space-y-1 max-h-40 overflow-y-auto custom-scrollbar">
                            <template x-for="error in bulkSummary.errors">
                                <li class="text-xs text-red-600 font-medium" x-text="error"></li>
                            </template>
                        </ul>
                    </div>
                </template>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row gap-3">
                <template x-if="bulkSummary.successful > 0 && bulkSummary.ids !== ''">
                    <a :href="'{{ route("admin.orders.bulk-packing-slips") }}?ids=' + bulkSummary.ids" target="_blank" class="flex-1 px-4 py-3 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all flex justify-center items-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4 text-slate-400"></i> Packing Slips
                    </a>
                </template>
                <template x-if="bulkSummary.successful > 0 && bulkSummary.ids !== ''">
                    <a :href="'{{ route("admin.orders.bulk-shipping-labels") }}?ids=' + bulkSummary.ids" target="_blank" class="flex-1 px-4 py-3 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all flex justify-center items-center gap-2">
                        <i data-lucide="download" class="w-4 h-4 text-slate-400"></i> Shipping Labels
                    </a>
                </template>
                <button @click="showBulkSummaryModal = false; location.reload()" class="flex-none px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all">Done</button>
            </div>
        </div>
    </div>
</div>
@endsection
