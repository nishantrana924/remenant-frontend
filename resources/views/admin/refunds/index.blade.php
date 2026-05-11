@extends('admin.layouts.app')

@section('content')
<div class="space-y-8 pb-24" x-data="{ search: '' }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Refunds & Returns</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Capital Protection • System Audit</p>
        </div>
    </div>

    <!-- Refund Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="saas-card group hover:border-rose-500 transition-all">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Pending Requests</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $stats['pending_refunds'] }}</h3>
                <div class="h-10 w-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
        <div class="saas-card bg-orange-600 border-0">
            <p class="text-[9px] font-bold text-orange-100 uppercase tracking-widest mb-3">Total Amount Refunded</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-white tracking-tighter">₹{{ number_format($stats['total_refunded']) }}</h3>
                <i data-lucide="rotate-ccw" class="w-5 h-5 text-orange-400"></i>
            </div>
        </div>
    </div>

    <!-- Refund Stream -->
    <div class="saas-card p-0 overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/40">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20 flex items-center justify-between">
            <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Refund Records</h3>
            <div class="relative w-64">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Search refunds..." class="saas-input pl-10 py-1.5 text-[10px] uppercase font-bold tracking-widest">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[8px] font-bold uppercase tracking-[0.25em] text-slate-400 bg-slate-50/50">
                        <th class="px-8 py-4">Order ID</th>
                        <th class="px-8 py-4">Customer</th>
                        <th class="px-8 py-4">Amount</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $item)
                    <tr x-show="!search || '{{ strtolower($item->order_number) }}'.includes(search.toLowerCase()) || '{{ strtolower($item->customer_name) }}'.includes(search.toLowerCase())"
                        class="hover:bg-slate-50/50 transition-all">
                        <td class="px-8 py-6 font-bold text-slate-900 text-xs uppercase">#{{ $item->order_number }}</td>
                        <td class="px-8 py-6 text-xs font-bold text-slate-600 uppercase tracking-tighter">{{ $item->customer_name }}</td>
                        <td class="px-8 py-6 font-bold text-rose-600 text-sm tracking-tighter">₹{{ number_format($item->refund_amount) }}</td>
                        <td class="px-8 py-6">
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase bg-slate-50 text-slate-400 border border-slate-100">{{ $item->refund_status }}</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('admin.orders.show', $item->id) }}" class="saas-btn-secondary py-1.5 px-3 text-[9px] font-bold uppercase tracking-widest">Resolve</a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($items->count() == 0)
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <p class="text-xs font-bold text-slate-300 uppercase tracking-[0.2em]">No refunds on record</p>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
