@extends('admin.layouts.app')

@section('content')
<div class="page-enter space-y-8" x-data="{ 
    search: '',
    dateFrom: '{{ request('from') }}',
    dateTo: '{{ request('to') }}',
    showDrawer: false,
    loading: false,
    selectedShipment: null,
    selectedIds: [],
    
    filter() {
        const url = new URL(window.location.href);
        if(this.dateFrom) url.searchParams.set('from', this.dateFrom);
        if(this.dateTo) url.searchParams.set('to', this.dateTo);
        window.location.href = url.toString();
    },

    async viewShipment(id) {
        this.loading = true;
        this.showDrawer = true;
        try {
            const res = await fetch(`/admin/logistics/shipment-details/${id}`);
            const json = await res.json();
            if(json.success) {
                this.selectedShipment = json.data;
            }
        } catch (e) {
            window.toast('Failed to load details', 'error');
        } finally {
            this.loading = false;
        }
    },

    async schedulePickup(id) {
        const ids = Array.isArray(id) ? id : [id];
        if(!confirm(`Schedule pickup for ${ids.length} shipment(s)?`)) return;
        
        try {
            const res = await fetch('/admin/orders/bulk-pickup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: ids })
            });
            const json = await res.json();
            if(json.success) {
                window.toast(json.message, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                window.toast(json.message, 'error');
            }
        } catch (e) {
            window.toast('Failed to schedule pickup', 'error');
        }
    },

    async bulkPrint() {
        if(this.selectedIds.length === 0) return;
        
        try {
            const res = await fetch('{{ route('admin.logistics.bulk-labels') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: this.selectedIds })
            });
            const json = await res.json();
            if(json.success && json.url) {
                window.open(json.url, '_blank');
            } else {
                window.toast(json.message, 'error');
            }
        } catch (e) {
            window.toast('Failed to generate bulk labels', 'error');
        }
    },

    async cancelShipment(id) {
        if(!confirm('Are you sure you want to cancel this shipment? This cannot be undone.')) return;
        
        try {
            const res = await fetch(`/admin/logistics/cancel-shipment/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const json = await res.json();
            if(json.success) {
                window.toast(json.message, 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                window.toast(json.message, 'error');
            }
        } catch (e) {
            window.toast('Failed to cancel shipment', 'error');
        }
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">All Shipments</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Live data directly from NimbusPost</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl border border-slate-100 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">Live API Data</span>
            </div>
        </div>
    </div>

    <!-- Filters & Stats -->
    <div class="saas-card p-6 bg-slate-50/50 border-slate-100">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">From Date</label>
                <input type="date" x-model="dateFrom" class="saas-input">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">To Date</label>
                <input type="date" x-model="dateTo" class="saas-input">
            </div>
            <div class="flex items-end gap-2">
                <button @click="filter()" class="saas-btn-primary h-[42px] px-6">Apply Filters</button>
                <a href="{{ route('admin.logistics.all-shipments') }}" class="saas-btn-secondary h-[42px] px-6">Reset</a>
            </div>
        </div>
    </div>

    <!-- Shipments Table -->
    <div class="saas-card p-0 overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th class="w-10">
                            <input type="checkbox" @change="selectedIds = $el.checked ? {{ json_encode(collect($shipments)->pluck('id')->toArray()) }} : []" class="rounded border-slate-200">
                        </th>
                        <th>Shipment ID</th>
                        <th>Order #</th>
                        <th>AWB Number</th>
                        <th>Courier</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-slate-50/50 transition-all cursor-pointer group" @click="viewShipment({{ $shipment['id'] }})">
                        <td @click.stop>
                            <input type="checkbox" :value="{{ $shipment['id'] }}" x-model="selectedIds" class="rounded border-slate-200">
                        </td>
                        <td>
                            <span class="font-bold text-slate-900 text-sm">#{{ $shipment['id'] }}</span>
                        </td>
                        <td>
                            <span class="text-xs font-medium text-slate-600">{{ $shipment['order_number'] ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-black text-blue-600 text-xs tracking-tight">{{ $shipment['awb_number'] ?? 'Not Generated' }}</span>
                                <span class="text-[9px] text-slate-400 uppercase font-bold">{{ $shipment['courier_name'] ?? '' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-xs text-slate-600 font-medium">{{ $shipment['courier_name'] ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-800">{{ $shipment['consignee_name'] ?? ($shipment['consignee']['name'] ?? 'Guest') }}</span>
                                <span class="text-[10px] text-slate-400">{{ $shipment['consignee_city'] ?? ($shipment['consignee']['city'] ?? '') }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $status = strtolower($shipment['status'] ?? 'pending');
                                $color = match($status) {
                                    'delivered' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'shipped', 'in transit' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'pending' => 'bg-orange-50 text-orange-600 border-orange-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100'
                                };
                            @endphp
                            <span class="{{ $color }} px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border">
                                {{ $status }}
                            </span>
                        </td>
                        <td>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">{{ date('M d, Y', strtotime($shipment['created_at'])) }}</span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2" @click.stop>
                                @if(!empty($shipment['awb_number']))
                                <a href="https://ship.nimbuspost.com/tracking/{{ $shipment['awb_number'] }}" target="_blank" class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-500 transition-all" title="Track">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </a>
                                @endif
                                <button @click="viewShipment({{ $shipment['id'] }})" class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all" title="View Details">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-20">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i data-lucide="package-search" class="w-12 h-12 mb-4 opacity-20"></i>
                                <p class="text-sm font-medium">No shipments found on NimbusPost for the selected range.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pagination)
        <div class="p-6 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
            <p class="text-xs text-slate-500 font-medium">Showing Page <span class="text-slate-900 font-bold">{{ $pagination['current_page'] ?? 1 }}</span> of <span class="text-slate-900 font-bold">{{ $pagination['total_pages'] ?? 1 }}</span></p>
            <div class="flex items-center gap-2">
                @if(($pagination['current_page'] ?? 1) > 1)
                <a href="?page={{ $pagination['current_page'] - 1 }}&from={{ request('from') }}&to={{ request('to') }}" class="saas-btn-secondary py-2 px-4 text-xs font-bold uppercase tracking-widest">Previous</a>
                @endif
                @if(($pagination['current_page'] ?? 1) < ($pagination['total_pages'] ?? 1))
                <a href="?page={{ $pagination['current_page'] + 1 }}&from={{ request('from') }}&to={{ request('to') }}" class="saas-btn-primary py-2 px-4 text-xs font-bold uppercase tracking-widest">Next</a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Bulk Actions Bar -->
    <div x-show="selectedIds.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         class="fixed bottom-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-8 py-4 rounded-3xl shadow-2xl z-[100] flex items-center gap-8 border border-white/10 backdrop-blur-xl"
         x-cloak>
        <div class="flex items-center gap-3 pr-8 border-r border-white/10">
            <span class="h-6 w-6 bg-blue-500 rounded-full flex items-center justify-center text-[10px] font-black" x-text="selectedIds.length"></span>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Selected</span>
        </div>
        <div class="flex items-center gap-4">
            <button @click="bulkPrint()" class="flex items-center gap-2 hover:text-blue-400 transition-all text-[11px] font-black uppercase tracking-widest">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Bulk Print Labels
            </button>
            <button @click="schedulePickup(selectedIds)" class="flex items-center gap-2 hover:text-emerald-400 transition-all text-[11px] font-black uppercase tracking-widest">
                <i data-lucide="truck" class="w-4 h-4"></i>
                Bulk Pickup Request
            </button>
        </div>
    </div>

    <!-- Details Slide-over Drawer -->
    <div x-show="showDrawer" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full md:w-[500px] bg-white shadow-2xl z-[150] border-l border-slate-100 flex flex-col"
         x-cloak>
        
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900" x-text="selectedShipment ? 'Shipment #' + selectedShipment.id : 'Loading...'"></h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1" x-text="selectedShipment ? 'Order: ' + (selectedShipment.order_number || '-') : ''"></p>
            </div>
            <button @click="showDrawer = false" class="h-10 w-10 rounded-full hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-8">
            <template x-if="loading">
                <div class="flex flex-col items-center justify-center h-full space-y-4">
                    <div class="w-10 h-10 border-4 border-slate-100 border-t-blue-500 rounded-full animate-spin"></div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Fetching Live Data...</p>
                </div>
            </template>

            <template x-if="!loading && selectedShipment">
                <div class="space-y-8">
                    <!-- Status Card -->
                    <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-100 overflow-hidden relative">
                        <i data-lucide="package" class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10"></i>
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Current Status</p>
                        <h4 class="text-3xl font-black mt-2 capitalize" x-text="selectedShipment.status"></h4>
                        <div class="mt-6 flex items-center gap-4">
                            <div class="flex-1">
                                <p class="text-[9px] font-bold uppercase opacity-60">AWB Number</p>
                                <p class="font-bold text-lg" x-text="selectedShipment.awb_number || 'Pending'"></p>
                            </div>
                            <div class="flex-1">
                                <p class="text-[9px] font-bold uppercase opacity-60">Courier</p>
                                <p class="font-bold text-lg" x-text="selectedShipment.courier_name || '-'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Consignee Section -->
                    <div class="space-y-4">
                        <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Customer Details</h5>
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                            <p class="text-sm font-bold text-slate-900" x-text="selectedShipment.consignee_name || (selectedShipment.consignee ? selectedShipment.consignee.name : 'Guest')"></p>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed" x-text="selectedShipment.consignee_address || (selectedShipment.consignee ? selectedShipment.consignee.address : '-')"></p>
                            <div class="mt-4 pt-4 border-t border-white grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">City</p>
                                    <p class="text-xs font-bold text-slate-800" x-text="selectedShipment.consignee_city || (selectedShipment.consignee ? selectedShipment.consignee.city : '-')"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Phone</p>
                                    <p class="text-xs font-bold text-slate-800" x-text="selectedShipment.consignee_phone || (selectedShipment.consignee ? selectedShipment.consignee.phone : '-')"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="space-y-4">
                        <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Order Items</h5>
                        <div class="space-y-3">
                            <template x-for="item in (selectedShipment.order_items || [])">
                                <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-xl">
                                    <div>
                                        <p class="text-xs font-bold text-slate-800" x-text="item.name"></p>
                                        <p class="text-[10px] text-slate-400 font-medium" x-text="'SKU: ' + (item.sku || '-')"></p>
                                    </div>
                                    <p class="text-xs font-black text-slate-900" x-text="'x' + item.qty"></p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tracking Timeline -->
                    <div class="space-y-6 pt-4">
                        <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tracking Timeline</h5>
                        <div class="space-y-8 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100">
                            <template x-if="!(selectedShipment.tracking_history && selectedShipment.tracking_history.length)">
                                <div class="pl-8 py-2">
                                    <p class="text-xs text-slate-400 italic">No tracking updates available yet.</p>
                                </div>
                            </template>
                            <template x-for="event in (selectedShipment.tracking_history || [])">
                                <div class="relative pl-8 group">
                                    <div class="absolute left-0 top-1 w-6 h-6 rounded-full bg-white border-4 border-slate-100 group-hover:border-blue-500 transition-all z-10"></div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-800 uppercase tracking-tight" x-text="event.status_description || event.status"></span>
                                        <span class="text-[10px] text-slate-400 mt-1" x-text="event.location + ' • ' + event.date"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Actions Section -->
                    <div class="pt-6 border-t border-slate-50 flex flex-col gap-3">
                        <template x-if="selectedShipment.status !== 'cancelled'">
                            <div class="space-y-3">
                                <button @click="schedulePickup(selectedShipment.id)" 
                                        class="w-full h-12 rounded-2xl bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center justify-center gap-3">
                                    <i data-lucide="truck" class="w-4 h-4"></i>
                                    Request Pickup Now
                                </button>
                                <button @click="cancelShipment(selectedShipment.id)" 
                                        class="w-full h-12 rounded-2xl bg-rose-50 text-rose-600 text-[11px] font-black uppercase tracking-widest hover:bg-rose-100 transition-all flex items-center justify-center gap-3">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Cancel Shipment
                                </button>
                            </div>
                        </template>
                        <a :href="'/admin/orders/' + selectedShipment.id + '/nimbus-label'" target="_blank"
                           class="w-full h-12 rounded-2xl bg-blue-50 text-blue-600 text-[11px] font-black uppercase tracking-widest hover:bg-blue-100 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            Download Label
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Drawer Overlay -->
    <div x-show="showDrawer" @click="showDrawer = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[140]" x-cloak></div>
</div>
@endsection
