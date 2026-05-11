@extends('admin.layouts.app')

@section('content')
<div class="space-y-8" x-data="{ search: '' }">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 uppercase tracking-tight">Legal Compliance</h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Institutional Standards • Remenant Engine</p>
        </div>
        <div class="relative w-64">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400"></i>
            <input type="text" x-model="search" placeholder="Search documents..." class="saas-input pl-10 py-1.5 text-[10px] uppercase font-bold tracking-widest">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($pages as $page)
        <div x-show="!search || '{{ strtolower($page->content['title'] ?? '') }}'.includes(search.toLowerCase())"
             class="saas-card group hover:border-orange-200 transition-all">
            <div class="flex items-start justify-between mb-6">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-orange-50 group-hover:text-orange-500 transition-all">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold {{ $page->status === 'published' ? 'text-emerald-500 bg-emerald-50 border border-emerald-100' : 'text-orange-500 bg-orange-50 border border-orange-100' }} px-2.5 py-1 rounded-lg uppercase tracking-widest">
                        {{ $page->status }}
                    </span>
                </div>
            </div>
            
            <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $page->content['title'] ?? 'Untitled Page' }}</h3>
            <p class="text-xs text-slate-400 mb-6 font-medium">Last Updated: {{ $page->content['last_updated'] ?? 'N/A' }}</p>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.legal.edit', $page->id) }}" class="flex-1 saas-btn-primary py-3 text-center flex justify-center items-center">
                    <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i>
                    Edit Content
                </a>
                <a href="{{ route($page->slug === 'privacy-policy' ? 'privacy' : ($page->slug === 'terms-and-conditions' ? 'terms' : ($page->slug === 'shipping-guide' ? 'shipping' : 'refund'))) }}" target="_blank" class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
