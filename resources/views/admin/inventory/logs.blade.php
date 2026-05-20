@extends('admin.layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-slate-900 leading-tight">Inventory Audit Trail</h2>
@endsection

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Audit Trail</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Full history of inventory movements</p>
        </div>
        <a href="{{ route('admin.inventory.index') }}" class="saas-btn-secondary px-6 py-2.5">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Inventory
        </a>
    </div>

    <!-- Logs Table -->
    <div class="saas-card overflow-hidden bg-white border border-slate-100 shadow-xl shadow-slate-200/40">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 bg-slate-50/50">
                        <th class="px-6 py-4 border-b border-slate-100">Date & Time</th>
                        <th class="px-6 py-4 border-b border-slate-100">Product</th>
                        <th class="px-6 py-4 border-b border-slate-100">Event / Reason</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">Old Stock</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">Change</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">New Stock</th>
                        <th class="px-6 py-4 border-b border-slate-100">Authorized By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black text-slate-900 uppercase tracking-tight">{{ $log->created_at->format('d M Y') }}</span>
                            <span class="block text-[9px] font-bold text-slate-400 uppercase mt-0.5">{{ $log->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-50 border border-slate-100 p-1 flex-shrink-0">
                                    <img src="{{ \App\Helpers\ImageHelper::getUrl($log->product->image ?? '') }}" class="h-full w-full object-contain">
                                </div>
                                <span class="text-xs font-bold text-slate-900 uppercase truncate max-w-[200px]">{{ $log->product->title ?? 'Deleted Product' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $reasons = [
                                    'order_placed' => ['bg-orange-50 text-orange-600', 'Order #...'],
                                    'order_cancelled' => ['bg-blue-50 text-blue-600', 'Restored (Cancel)'],
                                    'manual_update' => ['bg-slate-900 text-white', 'Manual Update'],
                                    'restock' => ['bg-emerald-50 text-emerald-600', 'Bulk Restock'],
                                ];
                                $style = $reasons[$log->reason][0] ?? 'bg-slate-50 text-slate-400';
                                $label = str_replace('_', ' ', $log->reason);
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[8px] font-black {{ $style }} uppercase tracking-widest">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-[11px] font-bold text-slate-400">{{ $log->old_stock }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-black {{ $log->change_amount > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $log->change_amount > 0 ? '+' : '' }}{{ $log->change_amount }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-black text-slate-900">{{ $log->new_stock }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-[9px] font-black text-slate-500">
                                    {{ substr($log->user->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-tighter">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="database-backup" class="w-8 h-8 text-slate-200"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">No movements recorded yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-6 border-t border-slate-50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
