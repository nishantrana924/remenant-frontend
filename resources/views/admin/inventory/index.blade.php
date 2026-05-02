@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex flex-col justify-center">
            <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Stock Logistics</h2>
            <div class="flex items-center gap-2 mt-1.5">
                <span class="h-1 w-1 bg-rose-500 rounded-full animate-pulse"></span>
                <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">{{ count($low_stock_items) }} Alerts Triggered</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-8 pb-12">
    <!-- Low Stock Alerts -->
    @if(count($low_stock_items) > 0)
    <div class="premium-card p-6 bg-rose-50 border-rose-100 shadow-sm border-2">
        <h3 class="text-rose-700 font-black uppercase tracking-widest text-[10px] mb-4 flex items-center gap-2">
            <i data-lucide="alert-triangle" class="h-4 w-4"></i>
            Critical Stock Depletion
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($low_stock_items as $item)
            <div class="bg-white p-4 rounded-2xl flex items-center justify-between shadow-sm border border-rose-50">
                <div class="min-w-0">
                    <h4 class="text-xs font-black text-slate-800 truncate">{{ $item['name'] }}</h4>
                    <p class="text-[9px] text-rose-500 font-black uppercase mt-0.5">{{ $item['type'] }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xl font-black text-rose-500">{{ $item['stock'] }}</span>
                    <p class="text-[8px] text-slate-400 font-black uppercase">Left</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Main Inventory Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Inventory Audit</h3>
            <div class="flex items-center gap-4">
                <div class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-1.5">
                    <i data-lucide="search" class="h-4 w-4 text-slate-400 mr-2"></i>
                    <input type="text" placeholder="Search SKU..." class="border-0 bg-transparent text-[10px] font-black uppercase tracking-widest focus:ring-0 w-32">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <th class="px-8 py-4">Inventory Item</th>
                        <th class="px-8 py-4">SKU Fingerprint</th>
                        <th class="px-8 py-4">Valuation</th>
                        <th class="px-8 py-4 text-center">Stock Level</th>
                        <th class="px-8 py-4 text-right">Last Audit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" x-data="{ editing: null, stockVal: 0 }">
                    @foreach($products as $p)
                        <!-- Product Level (If no variants) -->
                        @if($p->variants->count() == 0)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-xl bg-slate-50 border border-slate-100 p-1 flex-shrink-0">
                                        <img src="{{ asset('images/products/' . $p->image) }}" class="h-full w-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=P&background=ea5f06&color=fff'">
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-800">{{ $p->title }}</h4>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Base Item</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-[10px] font-mono text-slate-400">{{ $p->sku ?? 'NOT_SET' }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-black text-slate-800">₹{{ number_format($p->price) }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="px-4 py-2 rounded-xl text-sm font-black {{ $p->stock < 10 ? 'bg-rose-50 text-rose-500' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $p->stock }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $p->updated_at->format('d M, Y') }}</span>
                            </td>
                        </tr>
                        @else
                        <!-- Variants -->
                        @foreach($p->variants as $v)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5 pl-12 border-l-4 border-slate-100">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <h4 class="text-xs font-black text-slate-800">{{ $v->variant_name }}</h4>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $p->title }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-[10px] font-mono text-slate-400">{{ $v->sku ?? 'NOT_SET' }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-black text-slate-800">₹{{ number_format($v->price) }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="px-4 py-2 rounded-xl text-sm font-black {{ $v->stock < 10 ? 'bg-rose-50 text-rose-500' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $v->stock }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $v->updated_at->format('d M, Y') }}</span>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
