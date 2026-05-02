@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex flex-col justify-center">
            <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Customer Intelligence</h2>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="h-1 w-1 bg-indigo-500 rounded-full animate-pulse"></span>
                <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Managing {{ $items->count() }} Profiles</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Customer List Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Customer Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Contact</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Orders</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">LTV</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-[1.25rem] bg-indigo-50 flex items-center justify-center text-indigo-500 font-black text-lg flex-shrink-0">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-800 leading-tight">{{ $item->name }}</h4>
                                        <p class="text-[10px] text-slate-400 mt-1 font-bold tracking-wide uppercase">Joined {{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">{{ $item->email }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $item->phone ?? 'No phone' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-500 uppercase">{{ $item->orders->count() }} Orders</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-black text-slate-800">₹{{ number_format($item->orders()->where('payment_status', 'paid')->sum('total_amount')) }}</span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('admin.customers.show', $item->id) }}" class="h-9 px-4 inline-flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 transition-all text-[9px] font-black uppercase tracking-widest gap-2">
                                    View Intel <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <p class="text-slate-400 italic">No customers found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
