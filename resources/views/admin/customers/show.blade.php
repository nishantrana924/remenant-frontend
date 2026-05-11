@extends('admin.layouts.app')

@section('content')
<div class="space-y-8 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.customers.index') }}" class="h-10 w-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-orange-500 transition-all shadow-sm">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">{{ $item->name }}</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Customer Profile • Since {{ $item->created_at->format('M Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($item->deleted_at)
            <form action="{{ route('admin.customers.restore', $item->id) }}" method="POST">
                @csrf
                <button class="saas-btn-primary py-2.5 px-6">Restore Account</button>
            </form>
            @else
            <form action="{{ route('admin.customers.destroy', $item->id) }}" method="POST">
                @csrf @method('DELETE')
                <button class="saas-btn-secondary !text-rose-500 !border-rose-100 hover:!bg-rose-50 py-2.5 px-6">Deactivate Identity</button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="space-y-8">
            <div class="saas-card p-8">
                <div class="flex flex-col items-center text-center">
                    <div class="h-24 w-24 rounded-[2rem] bg-orange-600 flex items-center justify-center text-white font-bold text-4xl border-8 border-orange-100 shadow-2xl shadow-orange-100 mb-6">
                        {{ substr($item->name, 0, 1) }}
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 uppercase tracking-tight">{{ $item->name }}</h3>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter mt-1">{{ $item->email }}</p>
                </div>
                
                <div class="mt-10 space-y-6 pt-8 border-t border-slate-50">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Access Level</p>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-orange-600 text-white shadow-lg shadow-orange-100">
                                {{ $item->role_id == 1 ? 'Administrator' : 'Customer' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Registration</p>
                        <p class="text-sm font-bold text-slate-700 uppercase tracking-tight">{{ $item->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Value Matrix -->
            <div class="saas-card p-0 overflow-hidden bg-white border border-orange-100 shadow-2xl shadow-orange-100/50">
                <div class="p-8 space-y-8 bg-gradient-to-br from-white to-orange-50/30">
                    <div>
                        <p class="text-[9px] font-bold text-orange-600 uppercase tracking-[0.25em] mb-2">Lifetime Value (LTV)</p>
                        <h4 class="text-4xl font-bold text-slate-900 tracking-tighter">₹{{ number_format($stats['total_spent']) }}</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-8 pt-8 border-t border-orange-100">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.25em] mb-1">Total Orders</p>
                            <p class="text-xl font-bold text-slate-900 tracking-tighter">{{ $stats['total_orders'] }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.25em] mb-1">Avg Ticket</p>
                            <p class="text-xl font-bold text-orange-600 tracking-tighter">₹{{ number_format($stats['avg_order_value']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="lg:col-span-2 space-y-8">
            <div class="saas-card p-0 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20">
                    <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Recent Transactions</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[8px] font-bold uppercase tracking-[0.25em] text-slate-400 bg-slate-50/50">
                                <th class="px-8 py-4">Order ID</th>
                                <th class="px-8 py-4">Amount</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($item->orders as $order)
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-900 uppercase">#{{ $order->order_number }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">{{ $order->created_at->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-[10px] font-bold text-slate-900 tracking-tighter">₹{{ number_format($order->total_amount) }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-1.5 rounded-full bg-{{ $order->status_color }}-500"></div>
                                        <span class="text-[9px] font-bold text-{{ $order->status_color }}-600 uppercase tracking-widest">{{ $order->status }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:border-orange-200 transition-all shadow-sm">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <p class="text-xs font-black text-slate-300 uppercase tracking-[0.2em]">No asset acquisitions found</p>
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
@endsection
