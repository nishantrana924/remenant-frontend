@extends('admin.layouts.app')

@section('content')
<div class="space-y-8 pb-24" x-data="{ activeTab: 'all', search: '' }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Shipping Management</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Systematic Shipment Tracking • Remenant Engine</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="saas-btn-secondary">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export Manifest
            </button>
        </div>
    </div>

    <!-- Logistics KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="saas-card group hover:border-orange-500 transition-all cursor-pointer" @click="activeTab = 'processing'">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">To Pack</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $stats['to_ship'] }}</h3>
                <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-all">
                    <i data-lucide="package" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="saas-card group hover:border-blue-500 transition-all cursor-pointer" @click="activeTab = 'shipped'">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">In Transit</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $stats['in_transit'] }}</h3>
                <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i data-lucide="truck" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="saas-card group hover:border-indigo-500 transition-all cursor-pointer" @click="activeTab = 'out_for_delivery'">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Out for Delivery</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $stats['out_for_delivery'] }}</h3>
                <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                    <i data-lucide="navigation" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="saas-card bg-orange-600 border-0">
            <p class="text-[9px] font-bold text-orange-100 uppercase tracking-widest mb-3">Delivery Rate</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-white tracking-tighter">98.2%</h3>
                <div class="h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Logistics Table -->
    <div class="saas-card p-0 overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/40">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
            <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Shipment Registry</h3>
            <div class="flex items-center gap-4">
                <div class="relative w-64 mr-2">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                    <input type="text" x-model="search" placeholder="Search shipments..." class="saas-input pl-10 py-1.5 text-[10px] uppercase font-bold tracking-widest">
                </div>
                <div class="flex bg-white rounded-lg p-1 border border-slate-100 shadow-sm">
                    <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-orange-600 text-white' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1 text-[8px] font-bold uppercase tracking-widest rounded-md transition-all">All</button>
                    <button @click="activeTab = 'processing'" :class="activeTab === 'processing' ? 'bg-orange-600 text-white' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1 text-[8px] font-bold uppercase tracking-widest rounded-md transition-all">Pending</button>
                    <button @click="activeTab = 'shipped'" :class="activeTab === 'shipped' ? 'bg-orange-600 text-white' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1 text-[8px] font-bold uppercase tracking-widest rounded-md transition-all">Transit</button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[8px] font-bold uppercase tracking-[0.25em] text-slate-400 bg-slate-50/50">
                        <th class="px-8 py-4">Shipment Details</th>
                        <th class="px-8 py-4">Destination</th>
                        <th class="px-8 py-4">Courier</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $item)
                    <tr class="hover:bg-slate-50/50 transition-all group" 
                        x-show="(activeTab === 'all' || activeTab === '{{ $item->status }}') && (!search || '{{ strtolower($item->order_number) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->customer_name) }}'.includes(search.toLowerCase()))">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-900 uppercase">#{{ $item->order_number }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">{{ $item->customer_name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-900 tracking-tight">{{ $item->city }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $item->pincode }} • {{ $item->state }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($item->tracking_id)
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-900 uppercase">{{ $item->courier_name }}</span>
                                <span class="text-[9px] font-bold text-blue-500 uppercase tracking-tighter mt-0.5">{{ $item->tracking_id }}</span>
                            </div>
                            @else
                            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Awaiting Assignment</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-1.5 rounded-full bg-{{ $item->status_color }}-500 animate-pulse"></div>
                                <span class="text-[9px] font-bold text-{{ $item->status_color }}-600 uppercase tracking-widest">{{ str_replace('_', ' ', $item->status) }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('admin.orders.show', $item->id) }}" class="saas-btn-secondary py-1.5 px-3 text-[9px] font-bold uppercase tracking-widest">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-8 py-4 border-t border-slate-50">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
