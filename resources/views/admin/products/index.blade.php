@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex items-center gap-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 text-slate-400 hover:text-orange-500 transition-all font-bold text-sm group">
                <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </div>
                Back
            </a>
            <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
            <div class="flex flex-col justify-center">
                <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Products Inventory</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="h-1 w-1 bg-indigo-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Catalog Management</p>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-orange-500 text-white px-6 py-3 rounded-2xl text-[11px] font-black shadow-[0_10px_20px_-5px_rgba(234,95,6,0.3)] hover:scale-105 hover:shadow-[0_15px_25px_-5px_rgba(234,95,6,0.4)] transition-all flex items-center gap-3 uppercase tracking-widest">
            <div class="h-5 w-5 bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="plus" class="h-3.5 w-3.5"></i>
            </div>
            Add Product
        </a>
    </div>
@endsection

@section('content')
    <div class="page-enter space-y-8">
        <!-- Filters & Search Bar -->
        <div class="premium-card p-4 flex flex-wrap gap-4 items-center justify-between">
            <div class="relative flex-1 min-w-[300px]">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                    <i data-lucide="search" class="h-5 w-5"></i>
                </span>
                <input type="text" placeholder="Search by name, slug or SKU..." class="premium-input block w-full pl-12 pr-4 py-2 text-sm">
            </div>
            <div class="flex gap-3">
                <select class="premium-input text-sm focus:ring-orange-500 pr-10">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <select class="premium-input text-sm focus:ring-orange-500 pr-10">
                    <option>Featured First</option>
                    <option>Newest First</option>
                </select>
            </div>
        </div>

        <!-- Products Table Card -->
        <div class="premium-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-8 py-5">Product Info</th>
                            <th class="px-6 py-5">Pricing</th>
                            <th class="px-6 py-5">Status</th>
                            <th class="px-6 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($items ?? [] as $item)
                            <tr class="hover:bg-orange-50/20 transition-all group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16 relative">
                                            <img class="h-16 w-16 rounded-2xl object-cover border border-gray-100 shadow-sm group-hover:scale-105 transition" 
                                                 src="{{ asset('storage/' . $item->image) }}" 
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->title) }}&color=ea5f06&background=fff1e8'"
                                                 alt="">
                                            @if($item->is_featured)
                                                <span class="absolute -top-2 -right-2 bg-yellow-400 text-white p-1.5 rounded-xl shadow-lg border-2 border-white">
                                                    <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-5">
                                            <div class="text-sm font-bold text-gray-800 group-hover:text-[#ea5f06] transition-colors">{{ $item->title }}</div>
                                            <div class="text-xs text-gray-400 mt-1 line-clamp-1">{{ $item->tagline }}</div>
                                            <div class="flex items-center gap-3 mt-2">
                                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter bg-gray-100 px-2 py-0.5 rounded-lg">{{ $item->slug }}</span>
                                                <div class="flex items-center text-yellow-500 font-bold text-[10px]">
                                                    <i data-lucide="star" class="w-3 h-3 fill-current mr-1"></i>
                                                    {{ $item->rating }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-gray-800">₹{{ number_format($item->price) }}</div>
                                    <div class="text-[10px] text-gray-400 line-through">₹{{ number_format($item->mrp) }}</div>
                                    @php $discount = round((($item->mrp - $item->price) / $item->mrp) * 100); @endphp
                                    <span class="text-[9px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-lg mt-1 inline-block">{{ $discount }}% OFF</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 inline-flex text-[9px] leading-5 font-bold rounded-full {{ $item->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} uppercase tracking-widest">
                                        {{ $item->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.products.edit', $item->id) }}" class="p-2.5 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition shadow-sm" title="Edit Product">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition shadow-sm" onclick="return confirm('Are you sure?')">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i data-lucide="package-search" class="w-10 h-10 text-gray-300"></i>
                                        </div>
                                        <h3 class="font-bold text-gray-800 text-lg">Inventory Empty</h3>
                                        <p class="text-gray-400 text-sm max-w-xs mt-1">Start building your catalog by adding your first healthcare product.</p>
                                        <a href="{{ route('admin.products.create') }}" class="mt-6 premium-button px-8 py-3">Add First Product</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($items) && method_exists($items, 'links'))
                <div class="p-6 border-t border-gray-50">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
