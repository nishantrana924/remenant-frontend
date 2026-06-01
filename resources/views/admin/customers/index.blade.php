@extends('admin.layouts.app')

@section('content')
<div class="space-y-8 pb-24" x-data="{ search: '' }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Customer List</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Manage Users & Loyalty • Remenant Engine</p>
        </div>
    </div>

    <!-- Customer Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="saas-card bg-orange-600 border-0 shadow-2xl shadow-orange-100">
            <p class="text-[9px] font-bold text-orange-100 uppercase tracking-widest mb-3">Total Customers</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-white tracking-tighter">{{ $items->count() }}</h3>
                <i data-lucide="users" class="w-6 h-6 text-orange-400"></i>
            </div>
        </div>
        <div class="saas-card">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">Active Now</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">{{ $items->whereNull('deleted_at')->count() }}</h3>
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
            </div>
        </div>
        <div class="saas-card">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">New This Month</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-slate-900 tracking-tighter">+{{ $items->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                <i data-lucide="trending-up" class="w-5 h-5 text-emerald-100"></i>
            </div>
        </div>
    </div>

    <!-- Identity Core -->
    <div class="saas-card p-0 overflow-hidden border border-slate-100 shadow-xl shadow-slate-200/40">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/20 flex items-center justify-between">
            <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Customer Directory</h3>
            <div class="relative w-72">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Search customers..." class="saas-input pl-10 py-1.5 text-[10px] uppercase font-bold tracking-widest">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[8px] font-bold uppercase tracking-[0.25em] text-slate-400 bg-slate-50/50">
                        <th class="px-8 py-4">Customer</th>
                        <th class="px-8 py-4">Total Spent (LTV)</th>
                        <th class="px-8 py-4">Orders</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($items as $user)
                    <tr x-show="!search || '{{ strtolower($user->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(search.toLowerCase())"
                        class="hover:bg-slate-50/50 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-orange-600 flex items-center justify-center text-white font-bold text-sm border-4 border-orange-100 shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-tight">{{ $user->name }}</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[10px] font-bold text-slate-900 tracking-tighter">₹{{ number_format($user->orders->where('payment_status', 'paid')->sum('total_amount')) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">{{ $user->orders->count() }} Orders</span>
                        </td>
                        <td class="px-8 py-6">
                            @if($user->deleted_at)
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase bg-rose-50 text-rose-500 border border-rose-100">Deactivated</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-bold uppercase bg-emerald-50 text-emerald-500 border border-emerald-100">Verified</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.customers.show', $user->id) }}" class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all shadow-sm" title="View Customer Details">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>

                                @if($user->deleted_at)
                                    <!-- Restore Button -->
                                    <form action="{{ route('admin.customers.restore', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Restore this user account?')">
                                        @csrf
                                        <button type="submit" class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-emerald-500 hover:bg-emerald-50 hover:border-emerald-200 transition-all shadow-sm" title="Restore Account">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>

                                    <!-- Permanent Delete Button -->
                                    <form action="{{ route('admin.customers.force-delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this user account? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all shadow-sm" title="Permanently Delete">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Deactivate Button -->
                                    <form action="{{ route('admin.customers.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Deactivate this user account?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-rose-500 hover:bg-rose-50 hover:border-rose-200 transition-all shadow-sm" title="Deactivate Account">
                                            <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
