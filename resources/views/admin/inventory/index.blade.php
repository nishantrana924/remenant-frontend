@extends('admin.layouts.app')

@section('content')
<div class="space-y-8" x-data="{ 
    search: '',
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
                title: 'text-2xl font-black text-slate-900',
                input: 'h-20 rounded-3xl bg-slate-50 border-0 text-center font-black text-4xl text-slate-900 focus:ring-4 focus:ring-orange-500/10 transition-all my-8 mx-0',
                confirmButton: 'h-14 px-10 rounded-2xl bg-orange-600 text-white font-black text-xs uppercase tracking-widest hover:bg-orange-700 transition shadow-xl shadow-orange-600/20',
                cancelButton: 'h-14 px-10 rounded-2xl bg-slate-100 text-slate-500 font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition ml-3'
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
            <h1 class="text-2xl font-bold text-slate-900">Inventory Intelligence</h1>
            <p class="text-sm text-slate-500 mt-1">Real-time stock auditing and warehouse logistics</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 bg-rose-50 px-4 py-2 rounded-2xl border border-rose-100">
                <div class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></div>
                <span class="text-[10px] font-black uppercase tracking-widest text-rose-600">{{ count($low_stock_items) }} Low Stock Alerts</span>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($low_stock_items as $item)
        <div class="saas-card bg-rose-50/50 border-rose-100/50 flex items-center justify-between p-5">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-rose-500 mb-1">Critical Depletion</p>
                <h4 class="text-sm font-bold text-slate-900 truncate w-48">{{ $item['name'] }}</h4>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black text-rose-600">{{ $item['stock'] }}</span>
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Left</p>
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
                <div class="flex items-center gap-1 bg-slate-50 p-1 rounded-xl border border-slate-100">
                    <button class="px-4 py-2 text-xs font-bold text-slate-600 rounded-lg bg-white shadow-sm ring-1 ring-black/5">All Items</button>
                    <button class="px-4 py-2 text-xs font-bold text-slate-400 hover:text-slate-600 transition">Low Stock</button>
                </div>
                <button class="h-12 px-6 rounded-2xl bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-slate-800 transition shadow-lg shadow-black/10 flex items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> Export Data
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="saas-table">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="pl-8">Product Information</th>
                        <th>Type</th>
                        <th>SKU Identifier</th>
                        <th>Status</th>
                        <th>Current Stock</th>
                        <th class="text-right pr-8">Management</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($products as $p)
                        @php
                            $isLow = $p->stock <= 10 && $p->stock > 0;
                            $isOut = $p->stock <= 0;
                            $statusClass = $isOut ? 'bg-rose-100 text-rose-600' : ($isLow ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600');
                            $statusLabel = $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock');
                        @endphp
                        <!-- Product Level -->
                        @if($p->variants->count() == 0)
                        <tr x-show="!search || '{{ strtolower($p->title) }}'.includes(search.toLowerCase()) || '{{ strtolower($p->sku) }}'.includes(search.toLowerCase())"
                            class="hover:bg-slate-50/30 transition-colors">
                            <td class="pl-8">
                                <div class="flex items-center gap-4 py-1">
                                    <div class="h-12 w-12 rounded-2xl bg-white shadow-sm ring-1 ring-black/5 p-1.5 flex-shrink-0">
                                        <img src="{{ Str::startsWith($p->image, 'products/') ? asset('storage/' . $p->image) : asset('images/products/' . $p->image) }}" 
                                             class="h-full w-full object-contain" 
                                             onerror="this.src='https://ui-avatars.com/api/?name=P&background=ea5f06&color=fff'">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $p->title }}</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID: #{{ $p->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $p->product_type === 'combo' ? 'bg-orange-50 text-orange-600' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $p->product_type ?? 'Single' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="hash" class="w-3 h-3 text-slate-300"></i>
                                    <span class="text-xs font-mono font-bold text-slate-500 uppercase">{{ $p->sku ?? 'NOT_SET' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full {{ $statusClass }} text-[10px] font-black uppercase tracking-wider">
                                    <div class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></div>
                                    {{ $statusLabel }}
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900">{{ $p->stock }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Available Units</span>
                                </div>
                            </td>
                            <td class="text-right pr-8">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="updateStock({{ $p->id }}, 'product', {{ $p->stock }})" 
                                            class="h-10 px-4 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 transition-all shadow-md shadow-black/5 flex items-center gap-2">
                                        <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Add Stock
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @else
                            <!-- Variants -->
                            @foreach($p->variants as $v)
                            @php
                                $vIsLow = $v->stock <= 10 && $v->stock > 0;
                                $vIsOut = $v->stock <= 0;
                                $vStatusClass = $vIsOut ? 'bg-rose-100 text-rose-600' : ($vIsLow ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600');
                                $vStatusLabel = $vIsOut ? 'Out of Stock' : ($vIsLow ? 'Low Stock' : 'In Stock');
                            @endphp
                            <tr x-show="!search || '{{ strtolower($p->title) }}'.includes(search.toLowerCase()) || '{{ strtolower($v->variant_name) }}'.includes(search.toLowerCase()) || '{{ strtolower($v->sku) }}'.includes(search.toLowerCase())"
                                class="hover:bg-slate-50/30 transition-colors">
                                <td class="pl-8 relative py-3">
                                    <div class="absolute left-4 top-0 bottom-0 w-px bg-slate-100"></div>
                                    <div class="flex items-center gap-4 relative">
                                        <div class="w-6 h-px bg-slate-100 absolute -left-4 top-1/2"></div>
                                        <div class="h-8 w-8 rounded-lg bg-slate-50 border border-slate-100 p-1 flex-shrink-0 ml-4">
                                            <i data-lucide="layers" class="w-full h-full text-slate-300 p-1"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-[13px] leading-tight">{{ $v->variant_name }}</h4>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Parent: {{ $p->title }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-slate-50 text-slate-400">Variant</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="hash" class="w-3 h-3 text-slate-300"></i>
                                        <span class="text-xs font-mono font-bold text-slate-500 uppercase">{{ $v->sku ?? 'NOT_SET' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full {{ $vStatusClass }} text-[10px] font-black uppercase tracking-wider">
                                        <div class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></div>
                                        {{ $vStatusLabel }}
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900">{{ $v->stock }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Available Units</span>
                                    </div>
                                </td>
                                <td class="text-right pr-8">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="updateStock({{ $v->id }}, 'variant', {{ $v->stock }})" 
                                                class="h-9 px-4 rounded-xl border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest hover:border-orange-500 hover:text-orange-500 transition-all flex items-center gap-2">
                                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Update
                                        </button>
                                    </div>
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
