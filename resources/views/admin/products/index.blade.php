@extends('admin.layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Product Hub</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your inventory, variants, and visual content</p>
        </div>
        <div class="flex items-center gap-3">
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
                        <th>Product Details</th>
                        <th>Taxonomy</th>
                        <th>Pricing</th>
                        <th>Inventory</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-lg bg-slate-50 border border-slate-100 p-1 flex-shrink-0">
                                    <img src="{{ asset('images/products/' . $item->image) }}" class="h-full w-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=P&background=F97316&color=fff'">
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
                                <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Archive this product?')" class="h-8 w-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-200 transition-all">
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
