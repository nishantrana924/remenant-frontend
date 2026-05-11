@extends('admin.layouts.app')

@section('content')
<div class="pb-24" x-data="{ search: '' }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-orange-600 flex items-center justify-center text-white shadow-lg shadow-orange-100">
                <i data-lucide="ticket" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight uppercase">Campaigns</h1>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-0.5">Remenant Engine • Compact Classic</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.coupons.create') }}" class="saas-btn-primary py-2.5 px-5 shadow-xl shadow-orange-200/40">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                New Incentive
            </a>
        </div>
    </div>

    <!-- KPI Matrix (Compact) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="saas-card p-5 bg-orange-600 text-white border-0 shadow-lg shadow-orange-100">
            <p class="text-[8px] font-bold uppercase tracking-[0.2em] text-orange-200 mb-2">Live Codes</p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-2xl font-bold text-white tracking-tighter">{{ \App\Models\Coupon::where('is_active', true)->count() }}</h3>
                <span class="text-[9px] font-bold text-white opacity-80 uppercase">Active</span>
            </div>
        </div>
        <div class="saas-card p-5 bg-white border-slate-100 shadow-sm">
            <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Redemptions</p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter">{{ \App\Models\Coupon::sum('used_count') }}</h3>
                <span class="text-[9px] font-bold text-emerald-500 uppercase">Used</span>
            </div>
        </div>
        <div class="saas-card p-5 bg-white border-slate-100 shadow-sm">
            <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Value Shared</p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter">₹0</h3>
                <span class="text-[9px] font-bold text-slate-400 uppercase">OFF</span>
            </div>
        </div>
        <div class="saas-card p-5 bg-white border-slate-100 shadow-sm">
            <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Performance</p>
            <div class="flex items-baseline gap-1.5">
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter">+12%</h3>
                <span class="text-[9px] font-bold text-orange-500 uppercase">Growth</span>
            </div>
        </div>
    </div>

    <!-- Data Core (Classic Table) -->
    <div class="saas-card p-0 overflow-hidden border border-slate-100 bg-white shadow-xl shadow-slate-200/30">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
            <h3 class="text-[10px] font-bold text-slate-900 uppercase tracking-[0.25em]">Coupon Inventory</h3>
            <div class="relative w-64">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Search codes..." class="saas-input pl-10 py-1.5 text-[10px] uppercase font-bold tracking-widest">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left table-fixed">
                <thead>
                    <tr class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 border-b border-slate-50 bg-slate-50/20">
                        <th class="px-6 py-4 w-[250px]">Coupon Identity</th>
                        <th class="px-6 py-4 w-[120px]">Scale</th>
                        <th class="px-6 py-4 w-[180px]">Redemption</th>
                        <th class="px-6 py-4 w-[150px]">Validity</th>
                        <th class="px-6 py-4 w-[100px]">Status</th>
                        <th class="px-6 py-4 text-right w-[120px]">Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($items as $item)
                        <tr x-show="!search || '{{ strtolower($item->code) }}'.includes(search.toLowerCase())"
                            class="group hover:bg-slate-50/30 transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600 border border-orange-100">
                                        <i data-lucide="tag" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-900 text-xs tracking-tight uppercase">{{ $item->code }}</h4>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">
                                                {{ count($item->product_ids ?? []) > 0 ? 'Targeted' : 'Universal' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-black text-slate-900">
                                    {{ $item->type === 'percentage' ? $item->value . '%' : '₹' . number_format($item->value) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-1 w-20 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-orange-500" style="width: {{ $item->usage_limit ? min(($item->used_count / $item->usage_limit) * 100, 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-[9px] font-black text-slate-600">{{ $item->used_count }}/{{ $item->usage_limit ?? '∞' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-[10px] font-black text-slate-700 tracking-tight">{{ $item->end_date ? $item->end_date->format('M d, Y') : 'Life-Time' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <button onclick="toggleCouponStatus({{ $item->id }})" id="status-btn-{{ $item->id }}" class="relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 {{ $item->is_active ? 'bg-orange-500' : 'bg-slate-200' }}">
                                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 {{ $item->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.coupons.edit', $item->id) }}" class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all shadow-sm">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Archive campaign?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="h-8 w-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-200 transition-all shadow-sm">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="h-16 w-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                        <i data-lucide="ticket" class="w-8 h-8"></i>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">No active campaigns</h4>
                                    <p class="text-xs text-slate-400 max-w-[240px] mx-auto leading-relaxed">Launch your first promotional coupon to drive customer engagement.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-50">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleCouponStatus(id) {
    fetch(`{{ url('admin/coupons') }}/${id}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const btn = document.getElementById(`status-btn-${id}`);
            const dot = btn.querySelector('span');
            if(btn.classList.contains('bg-orange-500')) {
                btn.classList.replace('bg-orange-500', 'bg-slate-200');
                dot.classList.replace('translate-x-5', 'translate-x-0');
            } else {
                btn.classList.replace('bg-slate-200', 'bg-orange-500');
                dot.classList.replace('translate-x-0', 'translate-x-5');
            }
            toast('Campaign status updated');
        }
    });
}
</script>
@endpush
@endsection
