@extends('admin.layouts.app')

@section('content')
<form action="{{ route('admin.legal.update', $page->id) }}" method="POST" class="space-y-8 pb-20">
    @csrf
    @method('PUT')
    
    <!-- Header -->
    <div class="flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur-md z-10 py-4 -mx-4 px-4 sm:-mx-8 sm:px-8 border-b border-slate-100">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Editing: {{ $page->content['title'] ?? 'Legal Page' }}</h1>
            <p class="text-sm text-slate-500 mt-1">Configure content and SEO settings for this document.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.legal.index') }}" class="px-6 py-3 rounded-2xl bg-slate-50 text-slate-600 text-sm font-bold hover:bg-slate-100 transition-all">Cancel</a>
            <button type="submit" class="saas-btn-primary">Save Changes</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Page Identity -->
            <div class="saas-card">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Page Content</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Page Title</label>
                        <input type="text" name="title" value="{{ old('title', $page->content['title'] ?? '') }}" class="saas-input" placeholder="e.g. Privacy Policy">
                    </div>
                </div>
            </div>

            <!-- Dynamic Sections -->
            <div class="saas-card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Document Sections</h3>
                    <button type="button" onclick="addSection()" class="text-[10px] font-black text-orange-500 uppercase tracking-widest hover:underline">+ Add Section</button>
                </div>
                
                <div id="sections-container" class="space-y-6">
                    @foreach($page->content['sections'] ?? [] as $index => $section)
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-4 section-item" data-index="{{ $index }}">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-300 uppercase">Section #{{ $index + 1 }}</span>
                            <button type="button" onclick="this.closest('.section-item').remove()" class="text-rose-500 hover:text-rose-600">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <input type="text" name="content[sections][{{ $index }}][title]" value="{{ $section['title'] ?? '' }}" class="saas-input bg-white" placeholder="Section Title">
                        <textarea name="content[sections][{{ $index }}][content]" rows="6" class="saas-input bg-white" placeholder="Section Content">{{ $section['content'] ?? '' }}</textarea>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar / SEO Area -->
        <div class="space-y-8">
            <!-- SEO Settings -->
            <div class="saas-card">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">SEO Optimization</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Meta Title</label>
                        <input type="text" name="seo_title" value="{{ old('seo_title', $page->content['seo']['title'] ?? '') }}" class="saas-input" placeholder="Browser tab title">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Meta Description</label>
                        <textarea name="seo_description" rows="4" class="saas-input" placeholder="Brief summary for search engines">{{ old('seo_description', $page->content['seo']['description'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Meta Information -->
            <div class="saas-card bg-slate-900 text-white">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Meta Data</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-white/10">
                        <span class="text-[10px] font-bold text-slate-400">Slug</span>
                        <span class="text-[10px] font-bold">{{ $page->slug }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-[10px] font-bold text-slate-400">Last Modified</span>
                        <span class="text-[10px] font-bold">{{ $page->updated_at->format('d M, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function addSection() {
        const container = document.getElementById('sections-container');
        const index = container.querySelectorAll('.section-item').length;
        
        const html = `
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-4 section-item" data-index="${index}">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black text-slate-300 uppercase">New Section</span>
                    <button type="button" onclick="this.closest('.section-item').remove()" class="text-rose-500 hover:text-rose-600">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
                <input type="text" name="content[sections][${index}][title]" class="saas-input bg-white" placeholder="Section Title">
                <textarea name="content[sections][${index}][content]" rows="6" class="saas-input bg-white" placeholder="Section Content"></textarea>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        lucide.createIcons();
    }
</script>
@endsection
