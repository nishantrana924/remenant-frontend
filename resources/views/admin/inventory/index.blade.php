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
            confirmButtonColor: '#FF6B00',
            inputAttributes: { min: 0, step: 1 },
            customClass: { popup: 'rounded-[2rem]', input: 'saas-input text-center font-bold text-xl' }
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
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="relative w-72">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Search by SKU or Name..." class="saas-input pl-10">
            </div>
            <div class="flex items-center gap-3">
                <button class="saas-btn-secondary px-3 py-2 flex items-center gap-2 text-xs">
                    <i data-lucide="download" class="w-4 h-4"></i> Export CSV
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th>Inventory Item</th>
                        <th>SKU / ID</th>
                        <th>Valuation</th>
                        <th>Stock Level</th>
                        <th class="text-right">Last Audit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        <!-- Product Level (If no variants) -->
                        @if($p->variants->count() == 0)
                        <tr x-show="!search || '{{ strtolower($p->title) }}'.includes(search.toLowerCase()) || '{{ strtolower($p->sku) }}'.includes(search.toLowerCase())">
                            <td>
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-xl bg-slate-50 border border-slate-100 p-1 flex-shrink-0">
                                        <img src="{{ asset('images/products/' . $p->image) }}" class="h-full w-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=P&background=ea5f06&color=fff'">
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900 text-sm">{{ $p->title }}</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Base Product</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-[10px] font-mono text-slate-400 font-bold tracking-tighter">{{ $p->sku ?? 'NO_SKU' }}</span>
                            </td>
                            <td>
                                <span class="font-bold text-slate-900 text-sm">₹{{ number_format($p->price) }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="px-4 py-2 rounded-xl text-xs font-black min-w-[60px] text-center {{ $p->stock < 10 ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $p->stock }} Units
                                    </div>
                                    <button @click="updateStock({{ $p->id }}, 'product', {{ $p->stock }})" class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all shadow-sm">
                                        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $p->updated_at->format('d M, Y') }}</span>
                            </td>
                        </tr>
                        @else
                            <!-- Variants Section -->
                            @foreach($p->variants as $v)
                            <tr x-show="!search || '{{ strtolower($p->title) }}'.includes(search.toLowerCase()) || '{{ strtolower($v->variant_name) }}'.includes(search.toLowerCase()) || '{{ strtolower($v->sku) }}'.includes(search.toLowerCase())">
                                <td class="pl-12 relative">
                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 border-l-2 border-b-2 border-slate-100 rounded-bl-lg"></div>
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <h4 class="font-semibold text-slate-900 text-sm">{{ $v->variant_name }}</h4>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $p->title }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-[10px] font-mono text-slate-400 font-bold tracking-tighter">{{ $v->sku ?? 'NO_SKU' }}</span>
                                </td>
                                <td>
                                    <span class="font-bold text-slate-900 text-sm">₹{{ number_format($v->price) }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="px-4 py-2 rounded-xl text-xs font-black min-w-[60px] text-center {{ $v->stock < 10 ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $v->stock }} Units
                                        </div>
                                        <button @click="updateStock({{ $v->id }}, 'variant', {{ $v->stock }})" class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all shadow-sm">
                                            <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-right">
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
