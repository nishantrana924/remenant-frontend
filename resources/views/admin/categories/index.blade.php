@extends('admin.layouts.app')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Categories</h1>
            <p class="text-xs text-slate-400 font-medium">Manage your product organization</p>
        </div>
        <button type="button" onclick="document.getElementById('add-modal').classList.remove('hidden')" class="h-12 px-6 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-orange-600 transition-all shadow-lg shadow-slate-200">
            Create Category
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                <div class="flex items-start justify-between">
                    <div class="h-12 w-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-orange-50 group-hover:text-orange-500 transition-all">
                        <i data-lucide="folder-tree" class="w-6 h-6"></i>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button class="h-8 w-8 rounded-full hover:bg-rose-50 text-slate-300 hover:text-rose-500 flex items-center justify-center transition-all">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mt-6">
                    <h3 class="text-lg font-bold text-slate-900">{{ $category->name }}</h3>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">{{ $category->slug }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Simple Add Modal -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-6">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden p-8">
        <h3 class="text-xl font-black text-slate-900 mb-6">New Category</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Category Name</label>
                    <input type="text" name="name" required class="w-full h-14 px-6 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:border-orange-500 focus:bg-white transition-all font-bold text-sm" placeholder="e.g. Wellness Essentials">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')" class="flex-1 h-14 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 h-14 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 transition-all">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
