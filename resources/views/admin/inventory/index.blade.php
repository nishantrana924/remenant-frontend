@extends('admin.layouts.app')

@section('content')
<div class="space-y-8" x-data="{ 
    search: '',
    activeTab: 'all',
    updateStock(id, type, currentStock) {
        Swal.fire({
            title: 'Update Stock Level',
            text: 'Adjust the current units for this item',
            input: 'number',
            inputValue: currentStock,
            showCancelButton: true,
            confirmButtonText: 'Update Units',
            cancelButtonText: 'Cancel',
            inputAttributes: { min: 0, step: 1 },
            customClass: {
                popup: 'rounded-[3rem] p-10 shadow-2xl border-0',
                title: 'text-2xl font-bold text-slate-900',
                input: 'h-20 rounded-3xl bg-slate-50 border-0 text-center font-bold text-4xl text-slate-900 focus:ring-4 focus:ring-orange-500/10 transition-all my-8 mx-0',
                confirmButton: 'h-14 px-10 rounded-2xl bg-orange-600 text-white font-bold text-xs uppercase tracking-widest hover:bg-orange-700 transition shadow-xl shadow-orange-600/20',
                cancelButton: 'h-14 px-10 rounded-2xl bg-slate-100 text-slate-500 font-bold text-xs uppercase tracking-widest hover:bg-slate-200 transition ml-3'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                fastSubmit('{{ route("admin.inventory.update") }}', {
                    data: { id: id, type: type, stock: result.value },
                    success: () => { toast('Stock updated successfully!'); setTimeout(() => location.reload(), 1000); }
                });
            }
        });
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Warehouse Inventory</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Real-time stock auditing • Remenant Engine</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 bg-rose-50 px-4 py-2 rounded-2xl border border-rose-100 shadow-sm shadow-rose-100">
                <div class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></div>
                <span class="text-[9px] font-bold uppercase tracking-widest text-rose-600">{{ count($low_stock_items) }} Alerts</span>
            </div>
        </div>
    </div>

    <!-- Inventory Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="saas-card bg-orange-600 border-0">
            <p class="text-[9px] font-bold text-orange-100 uppercase tracking-widest mb-3">Total Inventory Value</p>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-white tracking-tighter">₹{{ number_format($products->sum(fn($p) => $p->stock * $p->price)) }}</h3>
                <i data-lucide="shield-check" class="w-5 h-5 text-orange-400"></i>
            </div>
        </div>
        @foreach(collect($low_stock_items)->take(3) as $item)
        <div class="saas-card group hover:border-rose-500 transition-all border-l-4 border-l-rose-500">
            <p class="text-[9px] font-bold text-rose-500 uppercase tracking-widest mb-3">Low Stock</p>
            <div class="flex items-baseline justify-between">
                <h4 class="text-[11px] font-bold text-slate-900 truncate w-32 uppercase">{{ $item['name'] }}</h4>
                <span class="text-2xl font-bold text-rose-600 tracking-tighter">{{ $item['stock'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Table Section -->
    <div class="saas-card p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
            <div class="relative w-96">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Search by SKU, Name or Category..." class="saas-input pl-11 h-12 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-orange-500 transition-all">
            </div>
            <div class="flex items-center gap-3">
                <div class="flex bg-slate-100 p-1 rounded-2xl border border-slate-100">
                    <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-white shadow-lg text-orange-600 ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'" class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">All Items</button>
                    <button @click="activeTab = 'low'" :class="activeTab === 'low' ? 'bg-white shadow-lg text-rose-600 ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'" class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Low Stock</button>
                </div>
                <button onclick="window.print()" class="h-12 px-6 rounded-2xl bg-white border border-slate-200 text-slate-600 text-[10px] font-bold uppercase tracking-widest hover:bg-slate-50 transition shadow-sm flex items-center gap-3">
                    <i data-lucide="download" class="w-4 h-4"></i> Export PDF
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="saas-table">
                <thead>
                    <tr class="bg-slate-50/50 text-[9px] font-bold uppercase tracking-widest text-slate-400">
                        <th class="pl-8 py-4">Product Name</th>
                        <th>Type</th>
                        <th>SKU</th>
                        <th>Status</th>
                        <th>Stock</th>
                        <th class="text-right pr-8">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($products as $p)
                        @php
                            $isLow = $p->stock <= 10 && $p->stock > 0;
                            $isOut = $p->stock <= 0;
                            $statusClass = $isOut ? 'bg-rose-50 text-rose-600' : ($isLow ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600');
                        @endphp
                        @if($p->variants->count() == 0)
                        <tr x-show="(!search || '{{ strtolower($p->title) }}'.includes(search.toLowerCase()) || '{{ strtolower($p->sku) }}'.includes(search.toLowerCase())) && (activeTab === 'all' || (activeTab === 'low' && {{ $p->stock }} <= 10))"
                            class="hover:bg-slate-50/30 transition-colors">
                            <td class="pl-8">
                                <div class="flex items-center gap-4 py-1">
                                    <div class="h-10 w-10 rounded-xl bg-white shadow-sm ring-1 ring-black/5 p-1 flex-shrink-0">
                                        <img src="{{ \App\Helpers\ImageHelper::getUrl($p->image) }}" class="h-full w-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=P&background=F97316&color=fff'">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs uppercase tracking-tight">{{ $p->title }}</h4>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter mt-0.5">Primary Entry</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-widest bg-slate-50 text-slate-400">Single</span>
                            </td>
                            <td>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">{{ $p->sku ?? 'NOT_SET' }}</span>
                            </td>
                            <td>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $statusClass }} text-[8px] font-bold uppercase tracking-widest border border-current opacity-70">
                                    {{ $isOut ? 'Depleted' : ($isLow ? 'Critical' : 'Stable') }}
                                </div>
                            </td>
                            <td>
                                <span class="text-sm font-bold text-slate-900 tracking-tighter">{{ $p->stock }}</span>
                            </td>
                            <td class="text-right pr-8">
                                <button @click="updateStock({{ $p->id }}, 'product', {{ $p->stock }})" 
                                        class="saas-btn-secondary py-1.5 px-3 text-[9px] font-bold uppercase tracking-widest">Update</button>
                            </td>
                        </tr>
                        @else
                            @foreach($p->variants as $v)
                            @php
                                $vIsLow = $v->stock <= 10 && $v->stock > 0;
                                $vIsOut = $v->stock <= 0;
                                $vStatusClass = $vIsOut ? 'bg-rose-50 text-rose-600' : ($vIsLow ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600');
                            @endphp
                            <tr x-show="(!search || '{{ strtolower($p->title) }}'.includes(search.toLowerCase()) || '{{ strtolower($v->sku) }}'.includes(search.toLowerCase())) && (activeTab === 'all' || (activeTab === 'low' && {{ $v->stock }} <= 10))"
                                class="hover:bg-slate-50/30 transition-colors group">
                                <td class="pl-8 relative py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-8 w-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                                            <i data-lucide="layers" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-tight">{{ $p->title }}</h4>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                @if($v->size)<span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $v->size }}</span>@endif
                                                @if($v->color)<div class="h-2 w-2 rounded-full border border-slate-200" style="background: {{ $v->color }}"></div>@endif
                                                @if($v->weight)<span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $v->weight }}</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-widest bg-blue-50 text-blue-500">Variant</span>
                                </td>
                                <td>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">{{ $v->sku ?? 'NOT_SET' }}</span>
                                </td>
                                <td>
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $vStatusClass }} text-[8px] font-bold uppercase tracking-widest border border-current opacity-70">
                                        {{ $vIsOut ? 'Depleted' : ($vIsLow ? 'Critical' : 'Stable') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm font-bold text-slate-900 tracking-tighter">{{ $v->stock }}</span>
                                </td>
                                <td class="text-right pr-8">
                                    <button @click="updateStock({{ $v->id }}, 'variant', {{ $v->stock }})" 
                                            class="saas-btn-secondary py-1.5 px-3 text-[9px] font-bold uppercase tracking-widest">Update</button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-50">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
