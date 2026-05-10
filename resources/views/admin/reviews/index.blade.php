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

    async bulkAction(action) {
        let msg = action === 'delete' ? 'delete selected reviews?' : (action === 'reject' ? 'reject (hide) selected reviews?' : 'approve selected reviews?');
        window.confirmAction('Are you sure?', `Do you want to ${msg}`, async () => {
            window.fastSubmit('{{ route("admin.reviews.bulk-action") }}', {
                data: { ids: this.selectedItems, action: action },
                success: (res) => {
                    window.toast(res.message);
                    this.selectedItems = [];
                    setTimeout(() => { location.reload(); }, 500);
                }
            });
        });
    },

    async updateStatus(id, status) {
        window.fastSubmit('{{ url("admin/reviews") }}/' + id + '/status', {
            data: { status: status },
            success: (res) => {
                window.toast(res.message);
                setTimeout(() => { location.reload(); }, 500);
            }
        });
    },

    async toggleFeatured(id) {
        window.fastSubmit('{{ url("admin/reviews") }}/' + id + '/toggle-featured', {
            data: {},
            success: (res) => {
                window.toast(res.message);
                setTimeout(() => { location.reload(); }, 500);
            }
        });
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Customer Voices</h1>
            <p class="text-sm text-slate-500 mt-1">Moderate and manage product reviews and ratings</p>
        </div>
        <div class="flex items-center gap-3">
            <template x-if="selectedItems.length > 0">
                <div class="flex items-center gap-2">
                    <button @click="bulkAction('delete')" class="saas-btn-secondary !text-rose-500 !border-rose-100 hover:!bg-rose-50 flex items-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Delete Selected
                    </button>
                    <button @click="bulkAction('reject')" class="saas-btn-secondary flex items-center gap-2">
                        <i data-lucide="eye-off" class="w-4 h-4"></i>
                        Reject Selected
                    </button>
                    <button @click="bulkAction('approve')" class="saas-btn-primary flex items-center gap-2">
                        <i data-lucide="check-check" class="w-4 h-4"></i>
                        Approve Selected
                    </button>
                </div>
            </template>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="saas-card bg-orange-600 text-white border-0">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-white/20 flex items-center justify-center"><i data-lucide="star" class="w-6 h-6"></i></div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-orange-200">Total Reviews</p>
                    <p class="text-2xl font-black">{{ \App\Models\Review::count() }}</p>
                </div>
            </div>
        </div>
        <div class="saas-card">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500"><i data-lucide="clock" class="w-6 h-6"></i></div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pending Approval</p>
                    <p class="text-2xl font-black text-slate-900">{{ \App\Models\Review::where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="saas-card">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500"><i data-lucide="award" class="w-6 h-6"></i></div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Featured Reviews</p>
                    <p class="text-2xl font-black text-slate-900">{{ \App\Models\Review::where('is_featured', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="saas-card p-0 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-4">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Active Filters:</span>
                <div class="flex gap-2">
                    <a href="?status=pending" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border {{ request('status') === 'pending' ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">Pending</a>
                    <a href="?status=approved" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border {{ request('status') === 'approved' ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">Approved</a>
                    <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border {{ !request('status') ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">All</a>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="saas-table">
                <thead>
                    <tr>
                        <th class="w-10">
                            <input type="checkbox" @change="toggleAll()" :checked="selectedItems.length === allItems.length && allItems.length > 0" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </th>
                        <th>User & Product</th>
                        <th>Rating & Feedback</th>
                        <th>Status</th>
                        <th>Featured</th>
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
                                <div class="h-10 w-10 rounded-full bg-slate-900 flex items-center justify-center text-white text-[10px] font-black uppercase">
                                    {{ substr($item->user->name ?? 'G', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $item->user->name ?? 'Guest User' }}</h4>
                                    <p class="text-[10px] text-orange-500 font-bold uppercase mt-0.5">{{ $item->product->title ?? 'Product Deleted' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="max-w-md">
                            <div class="flex gap-0.5 mb-1.5">
                                @for($i=1; $i<=5; $i++)
                                    <i data-lucide="star" class="w-3 h-3 {{ $i <= $item->rating ? 'fill-amber-400 text-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed italic">"{{ $item->comment }}"</p>
                            @if($item->images && count($item->images) > 0)
                                <div class="flex gap-1 mt-2">
                                    @foreach($item->images as $img)
                                        <div class="h-8 w-8 rounded bg-slate-100 border border-slate-200 overflow-hidden">
                                            <img src="{{ \App\Helpers\ImageHelper::getUrl($img, 'reviews') }}" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($item->status === 'pending')
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-full text-[9px] font-black uppercase tracking-wider animate-pulse">Pending</span>
                            @elseif($item->status === 'approved')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[9px] font-black uppercase tracking-wider">Approved</span>
                            @else
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[9px] font-black uppercase tracking-wider">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <button @click="toggleFeatured({{ $item->id }})" class="h-8 w-8 rounded-lg flex items-center justify-center transition-all {{ $item->is_featured ? 'bg-orange-500 text-white shadow-lg' : 'bg-slate-50 text-slate-300 hover:bg-slate-100' }}">
                                <i data-lucide="award" class="w-4 h-4"></i>
                            </button>
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end gap-2">
                                @if($item->status !== 'approved')
                                    <button @click="updateStatus({{ $item->id }}, 'approved')" class="h-8 px-3 bg-emerald-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 shadow-sm transition-all">Approve</button>
                                @else
                                    <button @click="updateStatus({{ $item->id }}, 'rejected')" class="h-8 px-3 bg-slate-900 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 shadow-sm transition-all">Reject</button>
                                @endif
                                <button onclick="window.confirmAction('Delete Review?', 'This action cannot be undone.', () => window.fastSubmit('{{ route('admin.reviews.destroy', $item->id) }}', { method: 'DELETE', success: (res) => { window.toast(res.message); setTimeout(() => location.reload(), 500); } }))" class="h-8 w-8 rounded-lg border border-slate-100 text-rose-500 flex items-center justify-center hover:bg-rose-50 transition-all">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($items->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
