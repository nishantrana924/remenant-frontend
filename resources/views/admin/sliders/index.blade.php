@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <!-- Left: Back & Title -->
        <div class="flex items-center gap-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 text-slate-400 hover:text-orange-500 transition-all font-bold text-sm group">
                <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </div>
                Back
            </a>
            
            <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
            
            <div class="flex flex-col justify-center">
                <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Slider Management</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="h-1 w-1 bg-orange-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Control Center</p>
                </div>
            </div>
        </div>

        <!-- Right: Action Button -->
        <a href="{{ route('admin.sliders.create') }}" class="bg-orange-500 text-white px-6 py-3 rounded-2xl text-[11px] font-black shadow-[0_10px_20px_-5px_rgba(234,95,6,0.3)] hover:scale-105 hover:shadow-[0_15px_25px_-5px_rgba(234,95,6,0.4)] transition-all flex items-center gap-3 uppercase tracking-widest">
            <div class="h-5 w-5 bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="plus" class="h-3.5 w-3.5"></i>
            </div>
            Add Slider
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Control Strip (Premium) -->
        <form action="{{ route('admin.sliders.index') }}" method="GET" class="flex flex-col lg:flex-row items-center gap-4 bg-white/40 backdrop-blur-xl p-4 rounded-3xl shadow-xl shadow-slate-200/50">
            <!-- Search Group -->
            <div class="flex-1 w-full" style="min-height: 52px;">
                <div style="display: flex; align-items: center; background: white; border: 1px solid #e2e8f0; border-radius: 9999px; padding: 0 20px;">
                    <i data-lucide="search" style="width: 16px; height: 16px; color: #94a3b8; flex-shrink: 0;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search banners..." 
                           style="flex: 1; background: transparent !important; border: 0 !important; outline: none !important; box-shadow: none !important; padding: 12px 15px; font-size: 14px; font-weight: 500; color: #475569; appearance: none !important;">
                    @if(request('search'))
                        <a href="{{ route('admin.sliders.index') }}" style="color: #cbd5e1; text-decoration: none; margin-left: 10px;">
                            <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Filter Group (Segmented Controls) -->
            <div class="flex items-center gap-1.5 bg-slate-100/50 p-1.5 rounded-2xl border border-slate-100 w-full lg:w-auto">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}" id="status-filter-input">
                
                @php $currentStatus = request('status', 'all'); @endphp
                <button type="button" onclick="setStatusFilter('all')" class="status-filter-btn flex-1 lg:flex-none px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $currentStatus == 'all' ? 'bg-white shadow-lg shadow-orange-500/10 text-orange-600 ring-1 ring-orange-100' : 'text-slate-500 hover:text-slate-800 hover:bg-white/50' }}">
                    All
                </button>
                <button type="button" onclick="setStatusFilter('active')" class="status-filter-btn flex-1 lg:flex-none px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $currentStatus == 'active' ? 'bg-white shadow-lg shadow-emerald-500/10 text-emerald-600 ring-1 ring-emerald-100' : 'text-slate-500 hover:text-slate-800 hover:bg-white/50' }}">
                    Active
                </button>
                <button type="button" onclick="setStatusFilter('inactive')" class="status-filter-btn flex-1 lg:flex-none px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $currentStatus == 'inactive' ? 'bg-white shadow-lg shadow-slate-500/10 text-slate-600 ring-1 ring-slate-100' : 'text-slate-500 hover:text-slate-800 hover:bg-white/50' }}">
                    Inactive
                </button>
            </div>

            <!-- Sort/Action Group -->
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <button type="submit" class="w-full lg:w-64 bg-orange-500 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-orange-600 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 shadow-xl shadow-orange-500/30">
                    <i data-lucide="filter" class="h-4 w-4"></i>
                    Apply Filter
                </button>
            </div>
        </form>

        <!-- Compact Card Grid (5 columns on XL) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            @forelse($items ?? [] as $item)
                <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <!-- Compact Thumbnail -->
                    <div class="relative aspect-video bg-slate-50 rounded-t-2xl overflow-hidden">
                        <img src="{{ \App\Helpers\ImageHelper::getUrl($item->image_desktop, 'images/banners') }}" 
                             class="w-full h-full object-cover">
                        
                        <!-- Mini Preview Overlay -->
                        <div class="absolute bottom-2 right-2 w-6 h-9 rounded-md border border-white shadow-md overflow-hidden bg-white">
                            <img src="{{ \App\Helpers\ImageHelper::getUrl($item->image_mobile, 'images/banners/mobile-bg') }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Action Icons (Overlay) -->
                        <div class="absolute inset-0 bg-white/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="{{ route('admin.sliders.edit', $item->id) }}" class="p-2 bg-white rounded-lg shadow-sm text-slate-400 hover:text-indigo-500 transition-colors">
                                <i data-lucide="edit-3" class="h-4 w-4"></i>
                            </a>
                            <a href="{{ $item->link ?: '#' }}" target="_blank" class="p-2 bg-white rounded-lg shadow-sm text-slate-400 hover:text-emerald-500 transition-colors">
                                <i data-lucide="external-link" class="h-4 w-4"></i>
                            </a>
                            <form action="{{ route('admin.sliders.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete slider?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-white rounded-lg shadow-sm text-slate-400 hover:text-rose-500 transition-colors">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="p-3">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <h3 class="text-[11px] font-black text-slate-700 truncate flex-1" title="{{ $item->alt_text }}">
                                {{ $item->alt_text ?: 'Untitled #'.$item->id }}
                            </h3>
                            <span class="text-[9px] font-black bg-orange-50 text-orange-500 px-1.5 py-0.5 rounded uppercase tracking-tighter">#{{ $item->order }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $item->updated_at->format('d M') }}</span>
                            <!-- Inline Status Toggle -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" {{ $item->status ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-6 h-3 bg-slate-200 rounded-full peer peer-checked:bg-orange-500 after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:rounded-full after:h-2.5 after:w-2.5 after:transition-all peer-checked:after:translate-x-3"></div>
                            </label>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-slate-400 text-sm font-bold">No sliders found. Click "Add Slider" to begin.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function setStatusFilter(value) {
        document.getElementById('status-filter-input').value = value;
        // Auto-submit the form
        document.getElementById('status-filter-input').closest('form').submit();
    }
</script>
@endpush
