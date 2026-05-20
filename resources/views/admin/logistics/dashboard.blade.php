@extends('admin.layouts.app')

@section('content')
<div class="page-enter space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-slate-900 leading-tight">Logistics Dashboard</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">Shipping & Fulfillment Overview</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.logistics.all-shipments') }}" class="flex items-center gap-1.5 px-4 py-1.5 bg-white border border-slate-200 rounded text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                <i data-lucide="layers" class="w-3 h-3"></i>
                All Shipments
            </a>
            <button onclick="openRateCalculator()" class="flex items-center gap-1.5 px-4 py-1.5 bg-slate-900 border border-slate-900 rounded text-[10px] font-bold uppercase tracking-widest text-white hover:bg-slate-800 transition-all shadow-sm">
                <i data-lucide="calculator" class="w-3 h-3"></i>
                Rate Calculator
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Shipments</p>
                <div class="h-7 w-7 rounded bg-blue-50 flex items-center justify-center text-blue-600">
                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['total_shipments'] ?? 0) }}</h3>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Delivered</p>
                <div class="h-7 w-7 rounded bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['delivered'] ?? 0) }}</h3>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">In Transit</p>
                <div class="h-7 w-7 rounded bg-orange-50 flex items-center justify-center text-orange-600">
                    <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['in_transit'] ?? 0) }}</h3>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">RTO / Cancelled</p>
                <div class="h-7 w-7 rounded bg-rose-50 flex items-center justify-center text-rose-600">
                    <i data-lucide="alert-octagon" class="w-3.5 h-3.5"></i>
                </div>
            </div>
            <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['rto'] ?? 0) }}</h3>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

        {{-- Left Sidebar Nav --}}
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Quick Navigation</p>
                </div>
                <div class="divide-y divide-slate-100">
                    <a href="{{ route('admin.logistics.warehouses.index') }}" class="flex items-center justify-between px-4 py-3 hover:bg-slate-50 transition-all group">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="warehouse" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">Warehouses</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-slate-600 transition-all"></i>
                    </a>
                    <a href="{{ route('admin.logistics.ndr') }}" class="flex items-center justify-between px-4 py-3 hover:bg-slate-50 transition-all group">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-rose-500"></i>
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">NDR Panel</span>
                        </div>
                        <span class="h-4 w-4 bg-rose-500 text-white rounded-full text-[8px] font-black flex items-center justify-center">!</span>
                    </a>
                    <a href="{{ route('admin.logistics.logs') }}" class="flex items-center justify-between px-4 py-3 hover:bg-slate-50 transition-all group">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="terminal" class="w-3.5 h-3.5 text-emerald-500"></i>
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">API Logs</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-slate-600 transition-all"></i>
                    </a>
                </div>
            </div>

            {{-- Courier Network --}}
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Network Health</p>
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach(array_slice($active_couriers, 0, 4) as $courier)
                    <div class="flex items-center justify-between px-4 py-2.5">
                        <span class="text-[10px] font-bold text-slate-600 uppercase">{{ $courier['name'] }}</span>
                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Online</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Recent Shipments Table --}}
        <div class="lg:col-span-3">
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                    <div>
                        <h4 class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">Recent Shipments</h4>
                        <p class="text-[9px] text-slate-400 uppercase tracking-widest mt-0.5">Latest order fulfillment activity</p>
                    </div>
                    <form action="{{ route('admin.logistics.sync-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                            <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                            Sync API
                        </button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-[9px] font-bold uppercase tracking-widest text-slate-400 bg-slate-50/50 border-b border-slate-100">
                                <th class="px-4 py-3 text-left">Shipment</th>
                                <th class="px-4 py-3 text-left">Courier</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recent_shipments as $shipment)
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-4 py-3">
                                    <p class="text-[11px] font-black text-slate-900 uppercase">#{{ $shipment->order->order_number }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">AWB: {{ $shipment->awb_number }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-[11px] font-bold text-slate-700">{{ $shipment->courier_name }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $s = strtolower($shipment->status);
                                        $color = match($s) {
                                            'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'shipped', 'in_transit' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'cancelled', 'rto' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-orange-50 text-orange-700 border-orange-200'
                                        };
                                    @endphp
                                    <span class="{{ $color }} px-2.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest border">
                                        {{ str_replace('_', ' ', $s) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.orders.nimbus-label', $shipment->order->id) }}" target="_blank" class="h-7 w-7 rounded border border-slate-200 bg-white text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all flex items-center justify-center" title="Download Label">
                                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <a href="https://ship.nimbuspost.com/tracking/{{ $shipment->awb_number }}" target="_blank" class="h-7 w-7 rounded border border-slate-200 bg-white text-slate-400 hover:text-orange-600 hover:border-orange-200 transition-all flex items-center justify-center" title="Track Live">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <i data-lucide="package" class="w-8 h-8 text-slate-200 mx-auto mb-2"></i>
                                    <p class="text-[11px] text-slate-400 font-bold uppercase">No shipments yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.logistics.dashboard-modals')

@endsection
