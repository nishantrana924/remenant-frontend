@extends('admin.layouts.app')

@section('content')
<style>[x-cloak] { display: none !important; }</style>
<div x-data="productSystem()" class="pb-24" x-cloak>
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="h-10 w-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Advanced Product Unit Creator</h1>
                <p class="text-xs text-slate-500 mt-0.5 tracking-wide">Remenant Engine • Intelligence Dashboard</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showMobilePreview = true" class="saas-btn-secondary">
                <i data-lucide="smartphone" class="w-4 h-4"></i>
                Live Preview
            </button>
            <button type="button" onclick="document.querySelector('#product-form').submit()" class="saas-btn-primary shadow-lg shadow-orange-100">
                <i data-lucide="check" class="w-4 h-4"></i>
                Deploy Product
            </button>
        </div>
    </div>

    <form id="product-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Product Architecture -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- 1. Identity & Core Content -->
                <div class="saas-card">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500"><i data-lucide="component" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-slate-900">Core Identity</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="saas-label">Product Name</label>
                            <input type="text" name="title" x-model="formData.title" class="saas-input font-bold" placeholder="e.g. Vitamin C Effervescent">
                        </div>
                        <div>
                            <label class="saas-label">Product Tagline</label>
                            <input type="text" name="tagline" x-model="formData.tagline" class="saas-input" placeholder="e.g. Immunity & Skin Health">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="saas-label">Marketing Summary</label>
                            <textarea name="description" x-model="formData.description" rows="3" class="saas-input h-auto py-3" placeholder="Short catchy description..."></textarea>
                        </div>
                        <div>
                            <label class="saas-label">The Product Story (CKEditor)</label>
                            <textarea name="long_description" id="long_description_editor"></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Product Benefits Builder -->
                <div class="saas-card">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500"><i data-lucide="sparkles" class="w-5 h-5"></i></div>
                            <h3 class="text-base font-bold text-slate-900">Key Benefits (2x2 Grid)</h3>
                        </div>
                        <button type="button" @click="addBenefit()" class="text-xs font-bold text-orange-500 flex items-center gap-1 hover:bg-orange-50 px-3 py-1 rounded-full transition-all">
                            <i data-lucide="plus" class="w-3 h-3"></i> Add Benefit
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="(benefit, index) in formData.benefits" :key="index">
                            <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 group relative hover:border-orange-200 transition-all">
                                <button type="button" @click="removeBenefit(index)" class="absolute -top-2 -right-2 h-8 w-8 bg-white shadow-lg rounded-full text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-rose-500 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
                                <div class="flex items-start gap-6">
                                    <!-- Icon Picker/Preview Box -->
                                    <div class="w-24 shrink-0 space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block text-center">Icon</label>
                                        <button type="button" @click="openIconPicker(index)" class="w-full aspect-square rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-orange-500 shadow-inner group-hover:border-orange-100 transition-all hover:scale-105 active:scale-95">
                                            <i :data-lucide="benefit.icon || 'star'" class="w-8 h-8"></i>
                                        </button>
                                        <div class="text-center">
                                            <p class="text-[9px] font-black text-slate-400 truncate uppercase" x-text="benefit.icon || 'star'"></p>
                                        </div>
                                    </div>
                                    <!-- Content Fields -->
                                    <div class="flex-1 space-y-4 pt-1">
                                        <div>
                                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 block">Benefit Title</label>
                                            <input type="text" :name="'benefits['+index+'][title]'" x-model="benefit.title" class="saas-input h-11 font-bold bg-white" placeholder="e.g. Immunity Boost">
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 block">Short Insight</label>
                                            <input type="text" :name="'benefits['+index+'][desc]'" x-model="benefit.desc" class="saas-input h-10 text-xs bg-white" placeholder="e.g. Strengthens defenses.">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <p class="mt-4 text-[10px] text-slate-400 italic">Recommended: Add exactly 4 benefits for the best 2x2 grid layout.</p>
                </div>

                <!-- 3. Trust Signals (Fast Delivery, Secure, etc.) -->
                <div class="saas-card bg-slate-50/50 border-dashed">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500"><i data-lucide="shield-check" class="w-5 h-5"></i></div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Trust Signals</h3>
                            <p class="text-[10px] text-slate-400 font-medium">Core service highlights shown below the price</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <template x-for="(signal, index) in formData.trust_signals" :key="index">
                            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col gap-3">
                                <div class="flex items-center justify-between">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                        <i :data-lucide="signal.icon || 'star'" class="w-4 h-4"></i>
                                    </div>
                                    <button type="button" @click="openTrustIconPicker(index)" class="text-[9px] font-black text-orange-500 uppercase tracking-widest hover:underline">Change</button>
                                </div>
                                <input type="text" x-model="signal.text" class="saas-input h-9 text-[10px] font-black uppercase tracking-wider text-center" placeholder="e.g. FAST DELIVERY">
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 4. Dynamic FAQ Builder -->
                <div class="saas-card">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500"><i data-lucide="help-circle" class="w-5 h-5"></i></div>
                            <h3 class="text-base font-bold text-slate-900">Knowledge Base (FAQs)</h3>
                        </div>
                        <button type="button" @click="addFaq()" class="text-xs font-bold text-orange-500 flex items-center gap-1 hover:bg-orange-50 px-3 py-1 rounded-full transition-all">
                            <i data-lucide="plus" class="w-3 h-3"></i> Add FAQ
                        </button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(faq, index) in formData.faqs" :key="index">
                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 group relative">
                                <button type="button" @click="removeFaq(index)" class="absolute -top-2 -right-2 h-7 w-7 bg-white shadow-md rounded-full text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all"><i data-lucide="x" class="w-4 h-4"></i></button>
                                <div class="space-y-4">
                                    <input type="text" :name="'faqs['+index+'][question]'" x-model="faq.question" class="saas-input h-10 font-bold bg-white" placeholder="Question?">
                                    <textarea :name="'faqs['+index+'][answer]'" x-model="faq.answer" rows="2" class="saas-input h-auto py-3 bg-white" placeholder="Answer..."></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- 3. Advanced Logistics & Ritual -->
                <div class="saas-card">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500"><i data-lucide="settings-2" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-slate-900">Execution & Ritual</h3>
                    </div>
                    <div class="space-y-8">
                        <div>
                            <label class="saas-label">The Ritual (Steps 1-3)</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <template x-for="step in [1, 2, 3]" :key="step">
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                        <div class="w-6 h-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold mb-3" x-text="step"></div>
                                        <input type="text" :name="'ritual['+step+'][title]'" class="saas-input h-8 mb-2 text-xs font-bold" placeholder="Step Title">
                                        <textarea :name="'ritual['+step+'][desc]'" rows="2" class="saas-input h-auto py-2 text-[10px]" placeholder="Instruction..."></textarea>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="saas-label">Rich Specifications</label>
                                <textarea name="specs" id="specs_editor"></textarea>
                            </div>
                            <div>
                                <label class="saas-label">Brand Heritage (Brand Info)</label>
                                <textarea name="brand_info" id="brand_info_editor"></textarea>
                            </div>
                        </div>

                        <!-- 4. Experience Excellence (Structured Highlights) -->
                        <div class="pt-8 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-orange-500"><i data-lucide="award" class="w-5 h-5"></i></div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900">Experience Excellence</h3>
                                        <p class="text-[10px] text-slate-400 font-medium">Highlight key features with icons</p>
                                    </div>
                                </div>
                                <button type="button" @click="addHighlight()" class="text-xs font-bold text-orange-500 flex items-center gap-1 hover:bg-orange-50 px-3 py-1 rounded-full transition-all">
                                    <i data-lucide="plus" class="w-3 h-3"></i> Add Highlight
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="(item, index) in formData.highlights_list" :key="item.id">
                                    <div class="p-6 bg-slate-900 rounded-[2.5rem] text-white border border-white/5 relative group">
                                        <!-- Remove Button -->
                                        <button type="button" @click="removeHighlight(index)" class="absolute -top-2 -right-2 h-8 w-8 bg-white shadow-lg rounded-full text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-rose-500 hover:text-white z-10"><i data-lucide="x" class="w-4 h-4"></i></button>
                                        
                                        <div class="flex gap-6">
                                            <!-- Icon Picker for Highlight -->
                                            <div class="w-16 shrink-0 space-y-2">
                                                <button type="button" @click="openHighlightIconPicker(index)" class="w-full aspect-square rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-orange-500 transition-all hover:bg-white/10">
                                                    <i :data-lucide="item.icon || 'star'" class="w-6 h-6"></i>
                                                </button>
                                                <input type="hidden" :name="'highlights['+index+'][icon]'" x-model="item.icon">
                                                <p class="text-[8px] font-black text-white/30 text-center uppercase truncate" x-text="item.icon"></p>
                                            </div>
                                            <div class="flex-1 space-y-3">
                                                <input type="text" :name="'highlights['+index+'][title]'" x-model="item.title" class="w-full bg-transparent border-b border-white/10 py-1 text-sm font-black uppercase tracking-widest placeholder:text-white/20 focus:outline-none focus:border-orange-500 transition-all" placeholder="Title (e.g. Pure Ingredients)">
                                                <div class="prose prose-invert prose-xs">
                                                    <textarea :id="'highlight_editor_' + item.id" :name="'highlights['+index+'][desc]'" x-model="item.desc"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Marketing & Media -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- 0. Pricing Architecture -->
                <div class="saas-card bg-orange-600 text-white border-0 shadow-2xl shadow-orange-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center text-white"><i data-lucide="banknote" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-white">Pricing Architecture</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="saas-label text-orange-100">Selling Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-white/50">₹</span>
                                <input type="number" name="price" x-model="formData.price" class="saas-input pl-8 bg-white/10 border-white/20 text-white placeholder:text-white/30" placeholder="0.00">
                            </div>
                        </div>
                        <div>
                            <label class="saas-label text-orange-100">MRP (Original)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-white/50">₹</span>
                                <input type="number" name="mrp" x-model="formData.mrp" class="saas-input pl-8 bg-white/10 border-white/20 text-white placeholder:text-white/30" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-white/10 rounded-xl border border-white/10">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-orange-200">Profit Analysis</p>
                        <p class="text-lg font-black text-white mt-1" x-text="'Discount: ' + (formData.mrp > 0 ? Math.round((1 - formData.price/formData.mrp) * 100) : 0) + '%'"></p>
                    </div>
                </div>

                <!-- 0.1 Organization (Categories) -->
                <div class="saas-card">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600"><i data-lucide="folder-tree" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-slate-900">Organization</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="saas-label mb-0">Product Categories</label>
                            </div>
                            <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto pr-2 scrollbar-hide" id="category-list">
                                @foreach(\App\Models\Category::all() as $category)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-50 hover:bg-slate-50 transition-all cursor-pointer group">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-orange-500 focus:ring-orange-500 transition-all">
                                        <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            <label class="saas-label">Product Status</label>
                            <select name="status" x-model="formData.status" class="saas-input">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="hidden">Hidden</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- 4. Media & Gallery (The "Wow" Section) -->
                <div class="saas-card">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500"><i data-lucide="images" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-slate-900">Visual Collection</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="saas-label font-bold mb-0">Primary Hero</label>
                                <span class="text-[10px] text-slate-400">1000x1000px</span>
                            </div>
                            <input type="file" name="image" class="filepond-main">
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <label class="saas-label font-bold mb-0">Gallery Collection</label>
                                <span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full font-bold" id="gallery-count">0 Selected</span>
                            </div>
                            <div class="gallery-upload-area relative">
                                <input type="file" name="gallery[]" multiple class="filepond-gallery">
                                <div class="absolute bottom-4 right-4 z-10">
                                    <button type="button" onclick="document.querySelector('.filepond-gallery input').click()" class="h-10 w-10 bg-white shadow-lg rounded-full flex items-center justify-center text-orange-500 hover:scale-110 transition-all">
                                        <i data-lucide="plus" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-2 text-[10px] text-slate-400 text-center italic">Drag and drop multiple images to create a story</p>
                        </div>
                    </div>
                </div>

                <!-- 5. Search Engine Optimization (SEO) -->
                <div class="saas-card bg-slate-900 text-white border-0 shadow-xl shadow-slate-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center text-orange-400"><i data-lucide="search" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-white">SEO & Social</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="saas-label text-slate-400">Meta Title</label>
                            <input type="text" name="meta_title" x-model="formData.meta_title" class="saas-input bg-white/5 border-white/10 text-white placeholder:text-slate-600" placeholder="Google search title">
                        </div>
                        <div>
                            <label class="saas-label text-slate-400">Meta Description</label>
                            <textarea name="meta_description" x-model="formData.meta_description" rows="2" class="saas-input h-auto py-2 bg-white/5 border-white/10 text-white placeholder:text-slate-600" placeholder="Brief summary for SERP"></textarea>
                        </div>
                        <div>
                            <label class="saas-label text-slate-400">Video Integration (YouTube)</label>
                            <div class="relative">
                                <i data-lucide="youtube" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-rose-500"></i>
                                <input type="text" name="video_url" x-model="formData.video_url" class="saas-input pl-10 bg-white/5 border-white/10 text-white" placeholder="https://youtube.com/watch?v=...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Inventory & Pricing -->
                <div class="saas-card">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="saas-label">SKU</label>
                            <input type="text" name="sku" class="saas-input uppercase" placeholder="RM-VITC-01">
                        </div>
                        <div>
                            <label class="saas-label">HSN Code</label>
                            <input type="text" name="hsn_code" class="saas-input" placeholder="21069099">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="saas-label">Price (₹)</label>
                                <input type="number" name="price" x-model="formData.price" class="saas-input font-bold">
                            </div>
                            <div>
                                <label class="saas-label">MRP (₹)</label>
                                <input type="number" name="mrp" x-model="formData.mrp" class="saas-input">
                            </div>
                        </div>
                        <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">Inventory Alert</p>
                                <p class="text-xs text-orange-400 mt-1">Notify when below</p>
                            </div>
                            <input type="number" name="low_stock_threshold" value="10" class="w-16 h-10 saas-input border-orange-200 text-center font-bold">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Multi-Device Preview Drawer (Sync with show.blade.php) -->
    <div x-show="showMobilePreview" 
         x-cloak         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="fixed inset-0 z-[100] bg-slate-950/95 backdrop-blur-3xl flex flex-col items-center justify-center p-4 sm:p-8" 
         @click.self="showMobilePreview = false">
        
        <!-- Preview Controls -->
        <div class="mb-6 flex items-center gap-4 p-1.5 bg-slate-900 rounded-2xl border border-white/10 shadow-2xl z-[110]">
            <button @click="previewMode = 'mobile'" 
                    :class="previewMode === 'mobile' ? 'bg-orange-500 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-xs transition-all">
                <i data-lucide="smartphone" class="w-4 h-4"></i>
                Mobile View
            </button>
            <button @click="previewMode = 'desktop'" 
                    :class="previewMode === 'desktop' ? 'bg-orange-500 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-xs transition-all">
                <i data-lucide="monitor" class="w-4 h-4"></i>
                Desktop View
            </button>
        </div>

        <!-- 1. Mobile Preview (Phone Mockup) -->
        <div x-show="previewMode === 'mobile'" x-transition class="relative w-full max-w-[375px] h-[760px] bg-slate-900 rounded-[3.5rem] border-[10px] border-slate-900 shadow-2xl overflow-hidden flex flex-col ring-1 ring-white/10">
            <!-- Mock Status Bar -->
            <div class="absolute top-0 inset-x-0 h-7 flex items-center justify-center z-[110] pointer-events-none">
                <div class="w-28 h-5 bg-slate-900 rounded-b-xl"></div>
            </div>
            
            <div class="flex-1 bg-white overflow-y-auto scrollbar-hide pt-10">
                <!-- Mobile Hero Image -->
                <div class="aspect-square bg-slate-50 relative overflow-hidden flex items-center justify-center border-b border-slate-100">
                    <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-contain p-4">
                    <i x-show="!imagePreview" data-lucide="image" class="w-16 h-16 text-slate-200"></i>
                </div>

                <!-- Mobile Info -->
                <div class="p-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-500" x-text="formData.tagline || 'Tagline'"></p>
                    <h1 class="mt-2 text-2xl font-black text-slate-900 leading-tight" x-text="formData.title || 'Product Title'"></h1>
                    
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex items-center gap-1 bg-orange-50 px-2 py-1 rounded-full text-orange-600 text-[10px] font-black">
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <span>4.9</span>
                        </div>
                        <span class="text-[10px] text-slate-400 font-bold underline decoration-dotted">1,240 Reviews</span>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <div class="bg-emerald-600 px-2.5 py-1 rounded text-white text-[10px] font-black uppercase tracking-widest">Hot Deal</div>
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex items-center text-emerald-600">
                            <i data-lucide="arrow-down" class="w-6 h-6 stroke-[3px]"></i>
                            <span class="text-3xl font-black"><span x-text="Math.round((formData.mrp - formData.price)/formData.mrp * 100) || 0"></span>%</span>
                        </div>
                        <span class="text-xl font-bold text-slate-300 line-through">₹<span x-text="formData.mrp"></span></span>
                        <span class="text-3xl font-black text-slate-900">₹<span x-text="formData.price"></span></span>
                    </div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Inclusive of all taxes</p>

                    <!-- Benefits 2x2 -->
                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <template x-for="benefit in formData.benefits.slice(0,4)" :key="benefit.title">
                            <div class="p-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-orange-500"><i :data-lucide="benefit.icon" class="w-4 h-4"></i></div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-900" x-text="benefit.title"></p>
                                    <p class="text-[8px] text-slate-400 leading-tight" x-text="benefit.desc"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Ritual Section -->
                <div class="mt-8 p-8 bg-slate-900 text-white rounded-[3rem] mx-2">
                    <h2 class="text-lg font-black uppercase tracking-tighter mb-8 flex items-center gap-3">
                        <span class="w-8 h-px bg-orange-500"></span> The Ritual
                    </h2>
                    <div class="space-y-8">
                        <template x-for="step in [1, 2, 3]" :key="step">
                            <div class="flex gap-4">
                                <span class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center font-black text-xs text-orange-500" x-text="step"></span>
                                <div>
                                    <h5 class="text-xs font-bold uppercase tracking-wider">Step Title</h5>
                                    <p class="text-[10px] text-white/50 mt-1 leading-relaxed">Watch the pure wellness dissolve.</p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <!-- Sticky CTA -->
            <div class="p-4 bg-white/80 backdrop-blur-md border-t border-slate-100">
                <button class="w-full h-14 bg-slate-950 rounded-2xl text-white font-black uppercase text-xs tracking-widest shadow-2xl">Add to Cart</button>
            </div>
        </div>

        <!-- 2. Desktop Preview (Real UI Port) -->
        <div x-show="previewMode === 'desktop'" x-transition class="w-full max-w-[1280px] h-[800px] bg-white rounded-3xl shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] border-t-[40px] border-slate-900 relative overflow-hidden flex flex-col">
            <!-- Browser Controls -->
            <div class="absolute -top-[28px] left-6 flex gap-2">
                <div class="w-3.5 h-3.5 rounded-full bg-rose-500 shadow-inner"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-amber-500 shadow-inner"></div>
                <div class="w-3.5 h-3.5 rounded-full bg-emerald-500 shadow-inner"></div>
            </div>

            <!-- Scrollable Content mimicking show.blade.php -->
            <div class="flex-1 overflow-y-auto px-16 py-12 scroll-smooth">
                <!-- Hero Section -->
                <div class="grid grid-cols-2 gap-16 items-start">
                    <!-- Left Gallery Mock -->
                    <div class="flex gap-6 sticky top-0 h-fit">
                        <div class="flex flex-col gap-4 w-24">
                            <template x-for="i in [1,2,3]" :key="i">
                                <div class="aspect-square bg-slate-50 rounded-2xl border-2 border-slate-100 flex items-center justify-center p-4 cursor-pointer hover:border-orange-200 transition">
                                    <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-contain mix-blend-multiply opacity-50">
                                    <i x-show="!imagePreview" data-lucide="image" class="w-8 h-8 text-slate-200"></i>
                                </div>
                            </template>
                        </div>
                        <div class="flex-1 aspect-square bg-slate-50 rounded-[3rem] border border-slate-100 flex items-center justify-center p-16 shadow-inner relative overflow-hidden">
                            <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-contain mix-blend-multiply drop-shadow-2xl">
                            <i x-show="!imagePreview" data-lucide="image" class="w-32 h-32 text-slate-100"></i>
                            <div class="absolute top-6 right-6 flex flex-col gap-3">
                                <div class="w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center text-slate-400 hover:text-orange-500 transition"><i data-lucide="heart" class="w-5 h-5"></i></div>
                                <div class="w-12 h-12 rounded-full bg-white shadow-lg flex items-center justify-center text-slate-400 hover:text-orange-500 transition"><i data-lucide="send" class="w-5 h-5"></i></div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Info -->
                    <div class="space-y-8">
                        <div class="space-y-2">
                            <p class="text-sm font-black uppercase tracking-[0.3em] text-orange-500" x-text="formData.tagline || 'Tagline'"></p>
                            <h1 class="text-6xl font-black text-slate-900 tracking-tighter leading-none" x-text="formData.title || 'Product Title'"></h1>
                            <div class="flex items-center gap-6 mt-6">
                                <div class="flex items-center gap-2 bg-orange-50 px-4 py-2 rounded-full text-orange-600 font-black">
                                    <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                                    <span>4.9</span>
                                </div>
                                <span class="text-sm font-bold text-slate-400 underline decoration-dotted decoration-2 underline-offset-4">1,240 Verified Reviews</span>
                            </div>
                        </div>

                        <div class="py-10 border-y border-slate-100 space-y-8">
                            <div class="inline-flex items-center bg-[#008A48] px-4 py-2 rounded-lg text-white text-xs font-black uppercase tracking-widest">Hot Deal</div>
                            
                            <div class="flex items-center gap-8">
                                <div class="flex items-center text-[#008A48]">
                                    <i data-lucide="arrow-down" class="w-10 h-10 stroke-[4px]"></i>
                                    <span class="text-5xl font-black"><span x-text="Math.round((formData.mrp - formData.price)/formData.mrp * 100) || 0"></span>%</span>
                                </div>
                                <span class="text-3xl font-bold text-slate-200 line-through">₹<span x-text="formData.mrp"></span></span>
                                <span class="text-6xl font-black text-slate-900 tracking-tight">₹<span x-text="formData.price"></span></span>
                            </div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">Inclusive of all taxes</p>
                        </div>

                        <!-- CTA -->
                        <div class="flex gap-6 pt-4">
                            <button class="flex-1 h-20 bg-slate-950 rounded-2xl text-white font-black uppercase tracking-[0.3em] text-sm shadow-2xl shadow-slate-900/20 active:scale-95 transition-all">Add to Cart</button>
                            <button class="w-20 h-20 rounded-2xl border-2 border-slate-100 flex items-center justify-center text-slate-300 hover:border-orange-200 hover:text-orange-500 transition-all"><i data-lucide="heart" class="w-8 h-8"></i></button>
                        </div>

                        <!-- Trust Grid -->
                        <div class="grid grid-cols-2 gap-6 pt-10">
                            <template x-for="benefit in formData.benefits.slice(0,4)" :key="benefit.title">
                                <div class="flex items-start gap-4 p-5 bg-white border border-slate-100 rounded-[2rem] shadow-sm">
                                    <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500"><i :data-lucide="benefit.icon" class="w-6 h-6"></i></div>
                                    <div>
                                        <p class="text-base font-black text-slate-900" x-text="benefit.title"></p>
                                        <p class="text-xs text-slate-400 mt-1" x-text="benefit.desc"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Info Table Layout (Real Site Sync) -->
                <div class="mt-32 border-t border-slate-100 pt-20">
                    <div class="grid grid-cols-3 border-b border-slate-100 bg-slate-50/50">
                        <div class="p-8 border-r border-slate-100 flex items-center gap-3"><i data-lucide="info" class="w-5 h-5 text-orange-500"></i><span class="text-xs font-black uppercase tracking-widest text-slate-400">Description</span></div>
                        <div class="p-8 border-r border-slate-100 flex items-center gap-3"><i data-lucide="layers" class="w-5 h-5 text-blue-500"></i><span class="text-xs font-black uppercase tracking-widest text-slate-400">Specifications</span></div>
                        <div class="p-8 flex items-center gap-3"><i data-lucide="award" class="w-5 h-5 text-emerald-500"></i><span class="text-xs font-black uppercase tracking-widest text-slate-400">Brand Info</span></div>
                    </div>
                    <div class="grid grid-cols-3 min-h-[400px]">
                        <div class="p-10 border-r border-slate-100 prose prose-sm text-slate-600" x-html="formData.long_description || 'Story...'"></div>
                        <div class="p-10 border-r border-slate-100 prose prose-sm text-slate-600" x-html="formData.specs || 'Technical data...'"></div>
                        <div class="p-10 prose prose-sm text-slate-600" x-html="formData.brand_info || 'Brand heritage...'"></div>
                    </div>
                </div>

                <!-- Highlights Section -->
                <div class="mt-32 p-24 bg-slate-950 rounded-[4rem] text-white relative overflow-hidden">
                    <div class="relative z-10 grid grid-cols-2 gap-20 items-center">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.4em] text-orange-500 mb-6">Experience Excellence</p>
                            <h2 class="text-6xl font-black italic tracking-tighter uppercase mb-12">Product Highlights</h2>
                            <div class="prose prose-invert prose-lg max-w-none" x-html="formData.highlights"></div>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md rounded-[3rem] p-12 border border-white/10 shadow-inner">
                            <h2 class="text-3xl font-black tracking-tighter uppercase mb-12 flex items-center gap-6">
                                <span class="h-px flex-1 bg-white/10"></span> The Ritual <span class="h-px flex-1 bg-white/10"></span>
                            </h2>
                            <div class="space-y-12">
                                <template x-for="step in [1, 2, 3]" :key="step">
                                    <div class="flex gap-8 group">
                                        <span class="w-14 h-14 shrink-0 rounded-2xl bg-orange-500 flex items-center justify-center font-black text-2xl text-white shadow-xl shadow-orange-500/20 group-hover:rotate-12 transition-transform" x-text="step"></span>
                                        <div>
                                            <h4 class="text-xl font-bold uppercase tracking-wider">Step Title</h4>
                                            <p class="text-lg text-white/40 mt-2 leading-relaxed">Watch the pure wellness dissolve.</p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Close -->
        <button @click="showMobilePreview = false" class="absolute top-8 right-8 h-14 w-14 bg-white/10 hover:bg-rose-500 text-white rounded-full flex items-center justify-center transition-all group shadow-2xl">
            <i data-lucide="x" class="w-7 h-7 group-hover:rotate-90 transition-transform"></i>
        </button>
    </div>

    <!-- Icon Picker Modal -->
    <div x-show="showIconPicker" 
         x-cloak         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[120] bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-6" 
         @click.self="showIconPicker = false">
        <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="text-xl font-black text-slate-900">Choose Icon</h3>
                    <p class="text-xs text-slate-500 mt-1">Select a visual for this product benefit</p>
                </div>
                <button @click="showIconPicker = false" class="h-10 w-10 rounded-full hover:bg-white flex items-center justify-center text-slate-400 transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto p-8 scrollbar-hide">
                <!-- Search / Manual Entry -->
                <div class="mb-8 relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" 
                           x-model="iconSearch" 
                           @input="$nextTick(() => lucide.createIcons())"
                           class="w-full h-14 pl-12 pr-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-orange-500 focus:bg-white transition-all font-bold text-sm" 
                           placeholder="Search or enter custom icon name (e.g. heart-pulse)">
                </div>

                <div class="grid grid-cols-4 sm:grid-cols-6 gap-4">
                    <!-- Dynamic Search Filter -->
                    <template x-for="icon in filteredIcons" :key="icon">
                        <button type="button" 
                                @click="selectIcon(icon)"
                                class="aspect-square rounded-2xl border-2 border-slate-50 flex flex-col items-center justify-center gap-2 hover:border-orange-500 hover:bg-orange-50 transition-all group">
                            <i :data-lucide="icon" class="w-6 h-6 text-slate-400 group-hover:text-orange-500"></i>
                            <span class="text-[8px] font-black uppercase text-slate-300 group-hover:text-orange-400" x-text="icon"></span>
                        </button>
                    </template>
                    
                    <!-- Custom Icon Option -->
                    <div x-show="iconSearch && !filteredIcons.includes(iconSearch)" class="col-span-full mt-4">
                        <button type="button" 
                                @click="selectIcon(iconSearch)"
                                class="w-full p-4 bg-orange-50 border-2 border-dashed border-orange-200 rounded-2xl flex items-center justify-center gap-3 text-orange-600 font-bold text-xs uppercase tracking-widest hover:bg-orange-100 transition-all">
                            <i :data-lucide="iconSearch" class="w-5 h-5"></i>
                            Use Custom Icon: "<span x-text="iconSearch"></span>"
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Powered by Lucide</p>
                <a href="https://lucide.dev/icons" target="_blank" class="text-[10px] font-black text-orange-500 hover:underline uppercase tracking-widest flex items-center gap-1">
                    View More on Lucide <i data-lucide="external-link" class="w-3 h-3"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function productSystem() {
    return {
        showMobilePreview: false,
        showIconPicker: false,
        iconSearch: '',
        currentBenefitIndex: null,
        previewMode: 'mobile',
        imagePreview: null,
        get filteredIcons() {
            if (!this.iconSearch) return this.iconLibrary;
            return this.iconLibrary.filter(i => i.includes(this.iconSearch.toLowerCase()));
        },
        iconLibrary: [
            'leaf', 'zap', 'shield-check', 'sparkles', 'award', 'activity', 'heart', 'star', 'sun', 'moon', 'flame', 'droplets', 
            'smile', 'check-circle', 'info', 'layers', 'package', 'truck', 'clock', 'refresh-cw', 'trending-up', 'anchor', 'atom', 'badge-check',
            'battery', 'beaker', 'bell', 'bike', 'binary', 'biohazard', 'bolt', 'bone', 'book', 'bot', 'box', 'briefcase', 'bug', 'cake', 'camera',
            'candy', 'car', 'carrot', 'chef-hat', 'cherry', 'chrome', 'circle', 'clipboard', 'cloud', 'code', 'coffee', 'coins', 'compass', 'cpu',
            'credit-card', 'cross', 'crown', 'cup-soda', 'database', 'diamond', 'dice-5', 'dna', 'dog', 'dollar-sign', 'drum', 'dumbbell', 'egg',
            'eye', 'feather', 'figma', 'file-text', 'film', 'fingerprint', 'fish', 'flag', 'flask-conical', 'flower', 'folder', 'footprints', 'fuel',
            'gamepad-2', 'gauge', 'ghost', 'gift', 'glass-water', 'globe', 'graduation-cap', 'grape', 'guitar', 'hammer', 'hand', 'hard-drive', 'hash',
            'headphones', 'help-circle', 'home', 'ice-cream', 'image', 'key', 'lamp', 'laptop', 'library', 'life-buoy', 'lightbulb', 'link', 'list',
            'locate', 'lock', 'log-in', 'log-out', 'magnet', 'mail', 'map', 'map-pin', 'medal', 'megaphone', 'menu', 'mic', 'microscope', 'milk',
            'mountain', 'mouse', 'move', 'music', 'navigation', 'network', 'newspaper', 'octagon', 'palette', 'paperclip', 'paw-print', 'pen', 'phone',
            'pie-chart', 'plane', 'play', 'plug', 'plus', 'pocket', 'power', 'printer', 'puzzle', 'qr-code', 'quote', 'radio', 'receipt', 'recycle',
            'reply', 'rocket', 'rss', 'ruler', 'save', 'scissors', 'search', 'send', 'server', 'settings', 'share-2', 'shield', 'ship', 'shirt',
            'shopping-bag', 'shopping-cart', 'shovel', 'shuffle', 'sidebar', 'signal', 'skull', 'slack', 'sliders', 'smartphone', 'speaker', 'sprout',
            'square', 'step-forward', 'stethoscope', 'sticker', 'sticky-note', 'stop-circle', 'sun', 'sunrise', 'sunset', 'tablet', 'tag', 'target',
            'terminal', 'thermometer', 'thumbs-down', 'thumbs-up', 'ticket', 'timer', 'toggle-left', 'toggle-right', 'tornado', 'trash', 'tree-deciduous',
            'trophy', 'tv', 'twitch', 'twitter', 'umbrella', 'unlock', 'upload', 'user', 'vibrate', 'video', 'volume-2', 'wallet', 'watch', 'waves',
            'webcam', 'wifi', 'wind', 'wine', 'wrench', 'x', 'youtube', 'zap-off'
        ],
        formData: {
            title: '', tagline: '', badge: '', description: '', long_description: '', 
            specs: '', brand_info: '', meta_title: '', meta_description: '', video_url: '',
            price: 0, mrp: 0,
            faqs: [{question: 'How to use?', answer: 'Dissolve one tablet in 200ml water.'}],
            benefits: [
                {icon: 'shield-check', title: 'Immunity Boost', desc: 'Strengthens defenses.'},
                {icon: 'sparkles', title: 'Skin Glow', desc: 'Supports collagen.'}
            ],
            trust_signals: [
                {icon: 'truck', text: 'Fast Delivery'},
                {icon: 'shield-check', text: '100% Secure'},
                {icon: 'refresh-cw', text: 'Easy Returns'},
                {icon: 'leaf', text: 'Vegan & Pure'}
            ],
            highlights_list: [
                {id: 1, icon: 'zap', title: 'Advanced Formulation', desc: '100% Bioavailable Effervescent Formula.'},
                {id: 2, icon: 'leaf', title: 'Pure Ingredients', desc: 'Clean Label Certified Ingredients.'},
                {id: 3, icon: 'star', title: 'Zero Compromise', desc: 'Zero Sugar & No Artificial Colors.'},
                {id: 4, icon: 'smile', title: 'Fast & Gentle', desc: 'Fast Acting & Gentle on the Stomach.'}
            ]
        },
        init() {
            // Force reset on load
            this.showIconPicker = false;
            this.showMobilePreview = false;
            
            this.initEditors();
            this.initFilePond();
            
            lucide.createIcons();
            
            this.$watch('showMobilePreview', value => { if(value) this.$nextTick(() => lucide.createIcons()); });
            this.$watch('showIconPicker', value => { if(value) this.$nextTick(() => lucide.createIcons()); });
        },
        openIconPicker(index) {
            this.pickerMode = 'benefit';
            this.currentBenefitIndex = index;
            this.showIconPicker = true;
            this.$nextTick(() => lucide.createIcons());
        },
        openTrustIconPicker(index) {
            this.pickerMode = 'trust';
            this.currentBenefitIndex = index;
            this.showIconPicker = true;
            this.$nextTick(() => lucide.createIcons());
        },
        openHighlightIconPicker(index) {
            this.pickerMode = 'highlight';
            this.currentBenefitIndex = index;
            this.showIconPicker = true;
            this.$nextTick(() => lucide.createIcons());
        },
        addHighlight() {
            const id = Date.now();
            this.formData.highlights_list.push({id: id, icon: 'star', title: '', desc: ''});
            const newIndex = this.formData.highlights_list.length - 1;
            this.$nextTick(() => {
                this.initHighlightEditor(id, newIndex);
                lucide.createIcons();
            });
        },
        removeHighlight(index) {
            this.formData.highlights_list.splice(index, 1);
            // Re-init editors might be needed if IDs shift, but CKEditor doesn't like shifting.
            // Better to keep the array stable or re-render carefully.
        },
        selectIcon(icon) {
            if(this.pickerMode === 'benefit') {
                this.formData.benefits[this.currentBenefitIndex].icon = icon;
            } else if(this.pickerMode === 'trust') {
                this.formData.trust_signals[this.currentBenefitIndex].icon = icon;
            } else {
                this.formData.highlights_list[this.currentBenefitIndex].icon = icon;
            }
            this.showIconPicker = false;
            this.$nextTick(() => lucide.createIcons());
        },
        initFilePond() {
            FilePond.registerPlugin(FilePondPluginImagePreview);
            
            const mainPond = FilePond.create(document.querySelector('.filepond-main'), {
                labelIdle: 'Drag & Drop Hero Image',
                allowImagePreview: true,
                imagePreviewHeight: 180,
                storeAsFile: true,
            });

            mainPond.on('addfile', (error, file) => {
                if (!error) {
                    const reader = new FileReader();
                    reader.onload = (e) => { this.imagePreview = e.target.result; };
                    reader.readAsDataURL(file.file);
                }
            });

            const gallery = FilePond.create(document.querySelector('.filepond-gallery'), {
                labelIdle: 'Drag & Drop Gallery Images',
                allowMultiple: true,
                allowImagePreview: true,
                imagePreviewHeight: 100,
                storeAsFile: true,
            });

            gallery.on('updatefiles', (files) => {
                document.getElementById('gallery-count').innerText = files.length + ' Selected';
            });
        },
        initEditors() {
            const mainEditors = ['long_description', 'specs', 'brand_info'];
            mainEditors.forEach(id => {
                ClassicEditor.create(document.querySelector('#'+id+'_editor'), {
                    toolbar: [
                        'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 
                        'insertTable', 'blockQuote', 'undo', 'redo'
                    ],
                    table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] }
                }).then(editor => {
                    editor.model.document.on('change:data', () => { this.formData[id] = editor.getData(); });
                });
            });

            // Initial Mini Editors for Highlights
            setTimeout(() => {
                this.formData.highlights_list.forEach((item, index) => {
                    this.initHighlightEditor(item.id, index);
                });
            }, 500);
        },
        initHighlightEditor(id, index) {
            const el = document.querySelector('#highlight_editor_' + id);
            if(!el || el.classList.contains('ck-editor-initialized')) return;
            
            ClassicEditor.create(el, {
                toolbar: ['bold', 'italic', 'link', 'undo', 'redo']
            }).then(editor => {
                el.classList.add('ck-editor-initialized');
                editor.model.document.on('change:data', () => { 
                    const item = this.formData.highlights_list.find(h => h.id === id);
                    if(item) item.desc = editor.getData(); 
                });
            }).catch(error => {
                console.error('Highlight Editor Error:', error);
            });
        },
        addFaq() { this.formData.faqs.push({question: '', answer: ''}); this.$nextTick(() => lucide.createIcons()); },
        removeFaq(index) { this.formData.faqs.splice(index, 1); },
        addBenefit() { this.formData.benefits.push({icon: 'star', title: '', desc: ''}); this.$nextTick(() => lucide.createIcons()); },
        removeBenefit(index) { this.formData.benefits.splice(index, 1); }
    }
}
</script>

<style>
    [x-cloak] { display: none !important; }
    .ck-editor__editable_inline {
        min-height: 100px;
        padding: 0 20px !important;
    }
    /* Fix for Highlights (Dark Mode Editors) */
    .bg-slate-900 .ck-reset_all {
        color: #333 !important; /* Keep toolbar icons dark */
    }
    .bg-slate-900 .ck-editor__editable {
        background: #1e293b !important; /* Slate 800 background */
        color: #f1f5f9 !important; /* Slate 100 text */
        border: 1px solid rgba(255,255,255,0.1) !important;
    }
    .ck-editor__editable { min-height: 150px; border-radius: 0 0 12px 12px !important; border-color: #E5E7EB !important; font-size: 13px; }
    .ck-toolbar { border-radius: 12px 12px 0 0 !important; border-color: #E5E7EB !important; background: #F9FAFB !important; }
    .filepond--root { border: 2px dashed #E5E7EB; border-radius: 16px; margin-bottom: 0; background: #F9FAFB; }
    .filepond--panel-root { background-color: transparent; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
</style>
@endsection
