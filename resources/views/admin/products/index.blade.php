@extends('admin.layouts.app')

@section('content')
<div class="space-y-8" x-data="{ 
    selectedItems: [],
    allItems: @js($items->pluck('id')),
    
    toggleAll() {
        if (this.selectedItems.length === this.allItems.length) {
            this.selectedItems = [];
        } else {
            this.selectedItems = [...this.allItems];
        }
    },

    async bulkDelete() {
        window.confirmAction('Delete Selected?', `Are you sure you want to archive ${this.selectedItems.length} products?`, async () => {
            window.fastSubmit('{{ route("admin.products.bulk-destroy") }}', {
                data: { ids: this.selectedItems },
                success: (res) => {
                    window.toast(res.message);
                    this.selectedItems = [];
                    setTimeout(() => { if(window.up) up.reload(); else location.reload(); }, 500);
                }
            });
        });
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Product</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your inventory, variants, and visual content</p>
        </div>
        <div class="flex items-center gap-3">
            <template x-if="selectedItems.length > 0">
                <button @click="bulkDelete()" class="saas-btn-secondary !text-rose-500 !border-rose-100 hover:!bg-rose-50 flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Archive Selected (<span x-text="selectedItems.length"></span>)
                </button>
            </template>
            <a href="{{ route('admin.products.create') }}" class="saas-btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Product
            </a>
        </div>
    </div>

    <!-- Table Section -->
    <div class="saas-card p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="relative w-72">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" placeholder="Search products..." class="saas-input pl-10">
            </div>
            <div class="flex items-center gap-3">
                <button class="saas-btn-secondary px-3 py-2 flex items-center gap-2 text-xs">
                    <i data-lucide="filter" class="w-4 h-4"></i> Filter
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th class="w-10">
                            <input type="checkbox" @change="toggleAll()" :checked="selectedItems.length === allItems.length && allItems.length > 0" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </th>
                        <th>Product Details</th>
                        <th>Taxonomy</th>
                        <th>Pricing</th>
                        <th>Inventory</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr :class="selectedItems.includes({{ $item->id }}) ? 'bg-orange-50/50' : ''">
                        <td>
                            <input type="checkbox" x-model="selectedItems" value="{{ $item->id }}" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </td>
                        <td>
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-lg bg-slate-50 border border-slate-100 p-1 flex-shrink-0">
                                    <img src="{{ \App\Helpers\ImageHelper::getUrl($item->image) }}" class="h-full w-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=P&background=F97316&color=fff'">
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900 text-sm">{{ $item->title }}</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">SKU: {{ $item->sku ?? 'NOT_SET' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @foreach($item->categories as $cat)
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">{{ $cat->name }}</span>
                                @endforeach
                                @if(($item->product_type ?? 'single') === 'both')
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-600 rounded text-[10px] font-bold uppercase tracking-wider">Single & Combo</span>
                                @else
                                    <span class="px-2 py-0.5 {{ ($item->product_type ?? 'single') === 'combo' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }} rounded text-[10px] font-bold uppercase tracking-wider">{{ $item->product_type ?? 'single' }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 text-sm">₹{{ number_format($item->price) }}</span>
                                <span class="text-[10px] text-slate-400 line-through">₹{{ number_format($item->mrp) }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-1.5 rounded-full {{ $item->stock < 10 ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></div>
                                <span class="text-xs font-semibold {{ $item->stock < 10 ? 'text-rose-600' : 'text-slate-600' }}">{{ $item->stock }} in stock</span>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $item->id) }}" class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.products.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" 
                                            @click="confirmAction('Archive Product?', 'Are you sure you want to move this product to archive?', () => { fastSubmit('#delete-form-{{ $item->id }}', { success: () => { toast('Product archived successfully!'); setTimeout(() => { if(window.up) up.reload(); else location.reload(); }, 500); } }) })"
                                            class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-200 transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
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
