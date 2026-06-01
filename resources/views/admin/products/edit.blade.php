@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen flex flex-col">
    <div class="flex-1">
        <style>[x-cloak] { display: none !important; }</style>
        <div x-data="productSystem()" class="pb-24" x-cloak @delete-category-event.window="deleteCategory($event.detail.id, $event.detail.name)">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="h-10 w-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Advanced Product Unit Editor</h1>
                <p class="text-xs text-slate-500 mt-0.5 tracking-wide">Remenant Engine • Intelligence Dashboard</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button @click="window.dispatchEvent(new CustomEvent('open-preview', { detail: formData }))" class="saas-btn-secondary">
                <i data-lucide="smartphone" class="w-4 h-4"></i>
                Live Preview
            </button>
            <button type="button" @click="submitForm($event)" class="saas-btn-primary shadow-lg shadow-orange-100">
                <i data-lucide="check" class="w-4 h-4"></i>
                Save Changes
            </button>
        </div>
    </div>

    <!-- Draft Notification -->
    <template x-if="hasDraft">
        <div x-cloak class="mb-8 p-4 bg-orange-600 rounded-[2rem] border-2 border-orange-500 shadow-2xl flex items-center justify-between">
            <div class="flex items-center gap-4 text-white">
                <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center animate-pulse">
                    <i data-lucide="history" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-sm font-black uppercase tracking-widest">Unsaved Progress Detected</p>
                    <p class="text-[10px] text-orange-100 font-medium">You have a draft for this product from a previous session. Restore it?</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="discardDraft()" class="px-4 py-2 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-white transition-all">Discard</button>
                <button @click="restoreDraft()" class="px-6 py-2 bg-white text-orange-600 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg hover:scale-105 transition-all">Restore Changes</button>
            </div>
        </div>
    </template>

    <form id="product-form" action="{{ route('admin.products.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
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
                            <textarea name="long_description" id="long_description_editor">{!! $item->long_description !!}</textarea>
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
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
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
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500"><i data-lucide="shield-check" class="w-5 h-5"></i></div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Trust Signals</h3>
                                <p class="text-[10px] text-slate-400 font-medium">Core service highlights shown below the price</p>
                            </div>
                        </div>
                        <button type="button" @click="addTrustSignal()" class="text-xs font-bold text-orange-500 flex items-center gap-1 hover:bg-orange-50 px-3 py-1 rounded-full transition-all">
                            <i data-lucide="plus" class="w-3 h-3"></i> Add Signal
                        </button>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                        <template x-for="(signal, index) in formData.trust_signals" :key="index">
                            <div class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex flex-col gap-2 group relative">
                                <button type="button" @click="removeTrustSignal(index)" class="absolute -top-2 -right-2 h-6 w-6 bg-white shadow-md rounded-full text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all"><i data-lucide="x" class="w-3 h-3"></i></button>
                                <div class="flex items-center justify-between">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                        <i :data-lucide="signal.icon || 'star'" class="w-4 h-4"></i>
                                    </div>
                                    <button type="button" @click="openTrustIconPicker(index)" class="text-[9px] font-black text-orange-500 uppercase tracking-widest hover:underline">Change</button>
                                </div>
                                <input type="text" :name="'trust_signals['+index+'][text]'" x-model="signal.text" class="saas-input h-9 text-[10px] font-black uppercase tracking-wider text-center" placeholder="e.g. FAST DELIVERY">
                                <input type="hidden" :name="'trust_signals['+index+'][icon]'" x-model="signal.icon">
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
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 group relative">
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
                                        <input type="text" :name="'ritual['+step+'][title]'" x-model="formData.ritual[step].title" class="saas-input h-8 mb-2 text-xs font-bold" placeholder="Step Title">
                                        <textarea :name="'ritual['+step+'][desc]'" x-model="formData.ritual[step].desc" rows="2" class="saas-input h-auto py-2 text-[10px]" placeholder="Instruction..."></textarea>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="saas-label text-slate-400">Rich Specifications (CKEditor)</label>
                                <textarea name="specs" id="specs_editor">{!! $item->specs !!}</textarea>
                            </div>
                            <div>
                                <label class="saas-label text-slate-400">Nutrition Description (Rich Text)</label>
                                <textarea name="nutrition_description" id="nutrition_description_editor">{!! $item->nutrition_description !!}</textarea>
                            </div>
                            <div class="mt-8">
                                <label class="saas-label text-slate-400">Nutrition Highlights (2 Boxes)</label>
                                <div class="grid grid-cols-2 gap-4 mb-8">
                                    <template x-for="(item, index) in formData.nutrition_highlights" :key="index">
                                        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                                            <button type="button" @click="openNutriIconPicker(index)" class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-orange-500 hover:scale-110 transition-all">
                                                <i :data-lucide="item.icon || 'star'" class="w-6 h-6"></i>
                                            </button>
                                            <input type="hidden" :name="'nutrition_highlights['+index+'][icon]'" x-model="item.icon">
                                            <input type="text" :name="'nutrition_highlights['+index+'][text]'" x-model="item.text" class="saas-input h-10 flex-1 text-[10px] font-black uppercase tracking-wider" placeholder="e.g. 100% VEGAN">
                                        </div>
                                    </template>
                                </div>

                                <label class="saas-label text-slate-400">Nutrition Facts Builder</label>
                                <div class="space-y-3">
                                    <template x-for="(val, label, index) in formData.nutrition" :key="label">
                                        <div class="flex items-center gap-2 group">
                                            <input type="text" :value="label" disabled class="saas-input h-10 w-1/3 bg-slate-50 font-bold text-[10px] uppercase" placeholder="Label">
                                            <input type="text" :name="'nutrition['+label+']'" x-model="formData.nutrition[label]" class="saas-input h-10 flex-1 text-xs" placeholder="Value (e.g. 1000mg)">
                                            <button type="button" @click="delete formData.nutrition[label]" class="h-8 w-8 rounded-lg text-rose-400 hover:bg-rose-50 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </template>
                                    
                                    <div class="flex items-center gap-2 p-3 bg-slate-50 rounded-2xl border border-dashed border-slate-200 mt-4">
                                        <input type="text" x-model="newNutrientLabel" class="saas-input h-9 text-[10px] uppercase font-bold" placeholder="New Label (e.g. Zinc)">
                                        <button type="button" @click="if(newNutrientLabel) { formData.nutrition[newNutrientLabel] = ''; newNutrientLabel = ''; }" class="h-9 px-4 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap">Add Row</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-8 mt-8 border-t border-slate-100">
                            <label class="saas-label">Brand Heritage (Brand Info)</label>
                            <textarea name="brand_info" id="brand_info_editor">{!! $item->brand_info !!}</textarea>
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

                <!-- 5. Combo Architecture (Only for Combo/Both types) -->
                <div class="saas-card" x-show="formData.product_type === 'combo' || formData.product_type === 'both'" x-transition>
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600"><i data-lucide="layers" class="w-5 h-5"></i></div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Combo Architecture</h3>
                                <p class="text-[10px] text-slate-400 font-medium tracking-wide uppercase">Select individual products included in this bundle</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <template x-for="(item, index) in formData.combo_products" :key="index">
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 group">
                                <div class="flex-1">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 block">Select Product</label>
                                    <select :name="'combo_products['+index+'][product_id]'" x-model="item.product_id" class="saas-input h-10 bg-white">
                                        <option value="">-- Choose Product --</option>
                                        @foreach($allProducts as $p)
                                            <option value="{{ $p->id }}">{{ $p->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-24">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 block">Quantity</label>
                                    <input type="number" :name="'combo_products['+index+'][quantity]'" x-model="item.quantity" class="saas-input h-10 bg-white text-center font-bold" min="1">
                                </div>
                                <div class="pt-5">
                                    <button type="button" @click="formData.combo_products.splice(index, 1)" class="h-10 w-10 rounded-xl text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                        
                        <button type="button" @click="formData.combo_products.push({product_id: '', quantity: 1})" class="w-full py-4 border-2 border-dashed border-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:border-orange-500 hover:text-orange-500 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add Product to Combo
                        </button>
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
                        <p class="text-lg font-black text-white mt-1" x-text="'Discount: ' + (parseFloat(formData.mrp) > 0 ? Math.round((1 - parseFloat(formData.price)/parseFloat(formData.mrp)) * 100) : 0) + '%'"></p>
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
                            <label class="saas-label">Product Type</label>
                            <select name="product_type" x-model="formData.product_type" class="saas-input">
                                <option value="single">Single Product</option>
                                <option value="combo">Combo Product</option>
                                <option value="both">Both (Single & Combo)</option>
                            </select>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="saas-label mb-0">Product Categories</label>
                            </div>
                            <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto pr-2 scrollbar-hide" id="category-list">
                                @foreach(\App\Models\Category::all() as $category)
                                    <label class="flex items-center justify-between p-3 rounded-xl border border-slate-50 hover:bg-slate-50 transition-all cursor-pointer group">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" x-model="formData.categories" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-orange-500 focus:ring-orange-500 transition-all">
                                            <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900">{{ $category->name }}</span>
                                        </div>
                                        <button type="button" 
                                                @click.stop.prevent="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                                                class="opacity-0 group-hover:opacity-100 transition-opacity h-7 w-7 rounded-full hover:bg-rose-50 text-slate-300 hover:text-rose-500 flex items-center justify-center">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            <label class="saas-label">Quick Add Category</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="newCategoryName" class="saas-input h-10 text-xs" placeholder="New Category Name">
                                <button type="button" @click="quickAddCategory($event)" class="h-10 px-4 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 transition-all">Add</button>
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
                        <div class="pt-4 border-t border-slate-100">
                            <label class="saas-label">Product Theme</label>
                            <input type="hidden" name="theme_color" x-model="formData.theme_color">
                            <div class="flex items-start gap-4 mt-2">
                                <div class="grid grid-cols-3 gap-2 w-32">
                                    <template x-for="color in ['orange', 'emerald', 'blue', 'indigo', 'rose']" :key="color">
                                        <button type="button" 
                                                @click="formData.theme_color = color"
                                                :class="formData.theme_color === color ? 'ring-2 ring-offset-2 ring-slate-900 border-transparent shadow-md' : 'hover:scale-110'"
                                                class="h-8 w-full rounded-lg transition-all border border-slate-200"
                                                :style="{ 
                                                    backgroundColor: color === 'orange' ? '#FF6B00' : 
                                                                   (color === 'emerald' ? '#10b981' : 
                                                                   (color === 'blue' ? '#3b82f6' : 
                                                                   (color === 'indigo' ? '#6366f1' : '#f43f5e')))
                                                }">
                                        </button>
                                    </template>
                                </div>
                                <div class="w-px h-8 bg-slate-100"></div>
                                <div class="relative group">
                                    <label class="block h-10 w-10 rounded-xl border-2 border-slate-200 p-0.5 cursor-pointer hover:border-slate-400 transition-all overflow-hidden"
                                           :style="{ backgroundColor: formData.theme_color.startsWith('#') ? formData.theme_color : '#fff' }"
                                           :class="formData.theme_color.startsWith('#') ? 'ring-2 ring-offset-2 ring-slate-900' : ''">
                                        <input type="color" x-model="formData.theme_color" class="absolute -inset-2 w-[150%] h-[150%] opacity-0 cursor-pointer">
                                        <div x-show="!formData.theme_color.startsWith('#')" class="w-full h-full flex items-center justify-center bg-slate-50">
                                            <i data-lucide="pipette" class="w-4 h-4 text-slate-400"></i>
                                        </div>
                                    </label>
                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-900 text-white text-[8px] font-bold uppercase rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Custom RGB</div>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-2 italic font-medium">Selected: <span class="uppercase font-black text-slate-900" x-text="formData.theme_color"></span></p>
                        </div>
                    </div>
                </div>
                <!-- 4. Media & Gallery -->
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
                            @if($item->image)
                                <div class="mb-4 aspect-video rounded-2xl overflow-hidden border border-slate-100 bg-slate-50 p-2">
                                    <img src="{{ \App\Helpers\ImageHelper::getUrl($item->image) }}" class="w-full h-full object-contain p-4">
                                </div>
                            @endif
                            <input type="file" name="image" class="filepond-main">
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <label class="saas-label font-bold mb-0">Gallery Collection</label>
                                <span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full font-bold" id="gallery-count" x-text="(formData.existing_gallery_count || 0) + '/4 Active'"></span>
                            </div>
                            @if($item->gallery && count($item->gallery) > 0)
                                <div class="grid grid-cols-4 gap-2 mb-4">
                                    @foreach($item->gallery as $index => $g)
                                        <div x-show="!formData.removed_gallery_images.includes('{{ $g }}')" 
                                             class="group relative aspect-square rounded-lg bg-slate-50 border border-slate-100 p-1 overflow-hidden">
                                            <img src="{{ \App\Helpers\ImageHelper::getUrl($g) }}" class="w-full h-full object-cover rounded">
                                            <button type="button" 
                                                    @click="removeExistingGalleryImage('{{ $g }}')"
                                                    class="absolute top-1 right-1 h-6 w-6 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:scale-110">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <template x-for="path in formData.removed_gallery_images" :key="path">
                                    <input type="hidden" name="removed_gallery_images[]" :value="path">
                                </template>
                            @endif
                            <div class="gallery-upload-area relative">
                                <input type="file" name="gallery[]" multiple class="filepond-gallery">
                                <div class="absolute bottom-4 right-4 z-10">
                                    <button type="button" onclick="document.querySelector('.filepond-gallery input').click()" class="h-10 w-10 bg-white shadow-lg rounded-full flex items-center justify-center text-orange-500 hover:scale-110 transition-all">
                                        <i data-lucide="plus" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-2 text-[10px] text-slate-400 text-center italic">Max 4 total images. Max 2MB per image.</p>
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
                            <input type="text" name="sku" x-model="formData.sku" class="saas-input uppercase" placeholder="RM-VITC-01">
                        </div>
                        <div>
                            <label class="saas-label">HSN Code</label>
                            <input type="text" name="hsn_code" x-model="formData.hsn_code" class="saas-input" placeholder="21069099">
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
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Available Stock</p>
                                <p class="text-xs text-slate-400 mt-1">Current units in warehouse</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="showInventoryHistory = true" class="h-10 w-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all">
                                    <i data-lucide="history" class="w-5 h-5"></i>
                                </button>
                                <input type="number" name="stock" x-model="formData.stock" class="w-20 h-10 saas-input border-slate-200 text-center font-bold">
                            </div>
                        </div>
                        <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">Inventory Alert</p>
                                <p class="text-xs text-orange-400 mt-1">Notify when below</p>
                            </div>
                            <input type="number" name="low_stock_threshold" x-model="formData.low_stock_threshold" class="w-16 h-10 saas-input border-orange-200 text-center font-bold">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <x-admin.preview-modal :route="route('admin.products.preview')" preview-url="remenant.com/product/preview" />

    <!-- Inventory History Modal -->
    <template x-teleport="body">
        <div x-show="showInventoryHistory" x-cloak class="fixed inset-0 z-[130] bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-6" @click.self="showInventoryHistory = false">
            <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50 text-slate-900">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-orange-500"><i data-lucide="history" class="w-5 h-5"></i></div>
                        <div>
                            <h3 class="text-xl font-black">Inventory Log</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Audit Trail for {{ $item->title }}</p>
                        </div>
                    </div>
                    <button @click="showInventoryHistory = false" class="h-10 w-10 rounded-full hover:bg-slate-200 flex items-center justify-center transition-all"><i data-lucide="x" class="w-5 h-5 text-slate-400"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto p-8">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">
                                <th class="pb-4">Date & Time</th>
                                <th class="pb-4">Admin</th>
                                <th class="pb-4">Movement</th>
                                <th class="pb-4">Balance</th>
                                <th class="pb-4">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($item->inventoryLogs as $log)
                                <tr class="group hover:bg-slate-50 transition-all">
                                    <td class="py-4">
                                        <p class="text-xs font-bold text-slate-900">{{ $log->created_at->format('M d, Y') }}</p>
                                        <p class="text-[9px] text-slate-400 font-medium">{{ $log->created_at->format('h:i A') }}</p>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-full bg-orange-100 flex items-center justify-center text-[8px] font-black text-orange-600">{{ substr($log->user->name ?? 'A', 0, 1) }}</div>
                                            <span class="text-[10px] font-bold text-slate-600">{{ $log->user->name ?? 'System' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="px-2 py-1 rounded-md text-[10px] font-black {{ $log->change_amount >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                            {{ $log->change_amount >= 0 ? '+' : '' }}{{ $log->change_amount }}
                                        </span>
                                    </td>
                                    <td class="py-4 font-black text-slate-900 text-xs">{{ $log->new_stock }}</td>
                                    <td class="py-4">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ str_replace('_', ' ', $log->reason) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300"><i data-lucide="info" class="w-6 h-6"></i></div>
                                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">No inventory movements recorded yet</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium italic">All stock movements are automatically tracked for compliance.</p>
                </div>
            </div>
        </div>
    </template>

    <!-- Icon Picker Modal -->
    <template x-teleport="body">
        <div x-show="showIconPicker" x-cloak class="fixed inset-0 z-[120] bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-6" @click.self="showIconPicker = false">
            <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50 text-slate-900">
                    <h3 class="text-xl font-black">Choose Icon</h3>
                    <button @click="showIconPicker = false" type="button"><i data-lucide="x" class="w-5 h-5 text-slate-400"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto p-8 scrollbar-hide">
                    <div class="mb-8 relative">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="text" x-model="iconSearch" class="w-full h-14 pl-12 pr-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-orange-500 focus:bg-white transition-all font-bold text-sm" placeholder="Search icons...">
                    </div>
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-4">
                        <template x-for="icon in filteredIcons" :key="icon">
                            <button type="button" @click="selectIcon(icon)" class="aspect-square rounded-2xl border-2 border-slate-50 flex flex-col items-center justify-center gap-2 hover:border-orange-500 hover:bg-orange-50 transition-all group">
                                <i :data-lucide="icon" class="w-6 h-6 text-slate-400 group-hover:text-orange-500"></i>
                                <span class="text-[8px] font-black uppercase text-slate-300 group-hover:text-orange-400" x-text="icon"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>
    </div>
</div>

<script>
function productSystem() {
    const editors = {}; // Non-reactive closure variable
    
    return {
        showIconPicker: false,
        showInventoryHistory: false,
        iconSearch: '',
        newNutrientLabel: '',
        currentBenefitIndex: null,
        newCategoryName: '',
        imagePreview: {!! $item->image ? json_encode(\App\Helpers\ImageHelper::getUrl($item->image)) : 'null' !!},
        hasDraft: false,
        get filteredIcons() {
            if (!this.iconSearch) return this.iconLibrary;
            return this.iconLibrary.filter(i => i.includes(this.iconSearch.toLowerCase()));
        },
        iconLibrary: ['leaf', 'zap', 'shield-check', 'sparkles', 'award', 'activity', 'heart', 'star', 'sun', 'moon', 'flame', 'droplets', 'smile', 'check-circle', 'info', 'layers', 'package', 'truck', 'clock', 'refresh-cw', 'trending-up', 'anchor', 'atom', 'badge-check', 'battery', 'beaker', 'bell', 'bike', 'binary', 'biohazard', 'bolt', 'bone', 'book', 'bot', 'box', 'briefcase', 'bug', 'cake', 'camera', 'candy', 'car', 'carrot', 'chef-hat', 'cherry', 'chrome', 'circle', 'clipboard', 'cloud', 'code', 'coffee', 'coins', 'compass', 'cpu', 'credit-card', 'cross', 'crown', 'cup-soda', 'database', 'diamond', 'dice-5', 'dna', 'dog', 'dollar-sign', 'drum', 'dumbbell', 'egg', 'eye', 'feather', 'figma', 'file-text', 'film', 'fingerprint', 'fish', 'flag', 'flask-conical', 'flower', 'folder', 'footprints', 'fuel', 'gamepad-2', 'gauge', 'ghost', 'gift', 'glass-water', 'globe', 'graduation-cap', 'grape', 'guitar', 'hammer', 'hand', 'hard-drive', 'hash', 'headphones', 'help-circle', 'home', 'ice-cream', 'image', 'key', 'lamp', 'laptop', 'library', 'life-buoy', 'lightbulb', 'link', 'list', 'locate', 'lock', 'log-in', 'log-out', 'magnet', 'mail', 'map', 'map-pin', 'medal', 'megaphone', 'menu', 'mic', 'microscope', 'milk', 'mountain', 'mouse', 'move', 'music', 'navigation', 'network', 'newspaper', 'octagon', 'palette', 'paperclip', 'paw-print', 'pen', 'phone', 'pie-chart', 'plane', 'play', 'plug', 'plus', 'pocket', 'power', 'printer', 'puzzle', 'qr-code', 'quote', 'radio', 'receipt', 'recycle', 'reply', 'rocket', 'rss', 'ruler', 'save', 'scissors', 'search', 'send', 'server', 'settings', 'share-2', 'shield', 'ship', 'shirt', 'shopping-bag', 'shopping-cart', 'shovel', 'shuffle', 'sidebar', 'signal', 'skull', 'slack', 'sliders', 'smartphone', 'speaker', 'sprout', 'square', 'step-forward', 'stethoscope', 'sticker', 'sticky-note', 'stop-circle', 'sun', 'sunrise', 'sunset', 'tablet', 'tag', 'target', 'terminal', 'thermometer', 'thumbs-down', 'thumbs-up', 'ticket', 'timer', 'toggle-left', 'toggle-right', 'tornado', 'trash', 'tree-deciduous', 'trophy', 'tv', 'twitch', 'twitter', 'umbrella', 'unlock', 'upload', 'user', 'vibrate', 'video', 'volume-2', 'wallet', 'watch', 'waves', 'webcam', 'wifi', 'wind', 'wine', 'wrench', 'x', 'youtube', 'zap-off'],
        formData: {
            title: {!! json_encode($item->title) !!},
            tagline: {!! json_encode($item->tagline) !!},
            description: {!! json_encode($item->description) !!},
            brand_info: {!! json_encode($item->brand_info) !!},
            nutrition_description: {!! json_encode($item->nutrition_description) !!},
            benefits_title: {!! json_encode($item->benefits_title) !!},
            benefits_subtitle: {!! json_encode($item->benefits_subtitle) !!},
            product_type: {!! json_encode($item->product_type ?? 'single') !!},
            price: {{ $item->price ?? 0 }},
            mrp: {{ $item->mrp ?? 0 }},
            stock: {{ $item->stock ?? 0 }},
            low_stock_threshold: {{ $item->low_stock_threshold ?? 10 }},
            theme_color: {!! json_encode($item->theme_color ?? 'orange') !!},
            status: {!! json_encode($item->status ?? 'published') !!},
            meta_title: {!! json_encode($item->meta_title) !!},
            meta_description: {!! json_encode($item->meta_description) !!},
            video_url: {!! json_encode($item->video_url) !!},
            faqs: {!! json_encode($item->faqs ?? []) !!},
            benefits: {!! json_encode($item->benefits ?? []) !!},
            trust_signals: {!! json_encode($item->trust_signals ?? []) !!},
            nutrition: {!! json_encode($item->nutrition ?? ['Energy' => '0 kcal', 'Protein' => '0g', 'Carbohydrates' => '0g']) !!},
            nutrition_highlights: {!! json_encode($item->nutrition_highlights ?? [['icon' => 'leaf', 'text' => '100% Vegan'], ['icon' => 'zap', 'text' => 'Zero Sugar']]) !!},
            ritual: {
                1: { title: {!! json_encode($item->ritual[1]['title'] ?? '') !!}, desc: {!! json_encode($item->ritual[1]['desc'] ?? '') !!} },
                2: { title: {!! json_encode($item->ritual[2]['title'] ?? '') !!}, desc: {!! json_encode($item->ritual[2]['desc'] ?? '') !!} },
                3: { title: {!! json_encode($item->ritual[3]['title'] ?? '') !!}, desc: {!! json_encode($item->ritual[3]['desc'] ?? '') !!} }
            },
            highlights_list: {!! json_encode($item->highlights ?? []) !!},
            sku: {!! json_encode($item->sku) !!},
            hsn_code: {!! json_encode($item->hsn_code) !!},
            categories: {!! json_encode($item->categories->pluck('id')) !!},
            combo_products: {!! json_encode($item->comboItems->map(function($ci) { return ['product_id' => $ci->product_id, 'quantity' => $ci->quantity]; })) !!},
            removed_gallery_images: [],
            existing_gallery_count: {{ count($item->gallery ?? []) }}
        },
        init() {
            this.initEditors();
            this.initFilePond();
            lucide.createIcons();
            this.formData.highlights_list.forEach((h, index) => { if(!h.id) h.id = Date.now() + index; });
            setTimeout(() => {
                this.formData.highlights_list.forEach((item, index) => { this.initHighlightEditor(item.id, index); });
            }, 1000);

            // Persistence Engine Initialization
            const persistenceKey = 'product_draft_edit_{{ $item->id }}';
            this.persistence = useFormPersistence(persistenceKey, this);
            
            if (this.persistence.load()) {
                this.hasDraft = true;
            }

            this.$watch('formData', () => {
                this.persistence.save();
            }, { deep: true });
        },
        restoreDraft() {
            const savedData = this.persistence.load();
            if (savedData) {
                this.formData = Object.assign({}, this.formData, savedData);
                // Sync CKEditors
                Object.keys(editors).forEach(id => {
                    if (this.formData[id]) editors[id].setData(this.formData[id]);
                });
                // Sync Highlight Editors
                this.formData.highlights_list.forEach(h => {
                    const ed = editors['highlight_' + h.id];
                    if (ed && h.desc) ed.setData(h.desc);
                });
                this.hasDraft = false;
                Swal.fire({ icon: 'success', title: 'Draft Restored', timer: 1500, showConfirmButton: false });
            }
        },
        discardDraft() {
            this.persistence.clear();
            this.hasDraft = false;
        },
        initEditors() {
            const mainEditors = ['long_description', 'specs', 'brand_info', 'nutrition_description'];
            mainEditors.forEach(id => {
                const el = document.querySelector('#'+id+'_editor');
                if (!el) return;
                ClassicEditor.create(el).then(editor => {
                    editors[id] = editor;
                    editor.model.document.on('change:data', () => { this.formData[id] = editor.getData(); });
                });
            });
        },
        initHighlightEditor(id, index) {
            const el = document.querySelector('#highlight_editor_' + id);
            if(!el) return;
            ClassicEditor.create(el, { toolbar: ['bold', 'italic', 'link', 'undo', 'redo'] }).then(editor => {
                editors['highlight_' + id] = editor;
                const item = this.formData.highlights_list.find(h => h.id === id);
                if(item && item.desc) editor.setData(item.desc);
                editor.model.document.on('change:data', () => { if(item) item.desc = editor.getData(); });
            });
        },
        addBenefit() { this.formData.benefits.push({icon: 'star', title: '', desc: ''}); this.$nextTick(() => lucide.createIcons()); },
        removeBenefit(index) { this.formData.benefits.splice(index, 1); },
        addFaq() { this.formData.faqs.push({question: '', answer: ''}); },
        removeFaq(index) { this.formData.faqs.splice(index, 1); },
        addTrustSignal() { this.formData.trust_signals.push({icon: 'truck', text: ''}); this.$nextTick(() => refreshIcons()); },
        removeTrustSignal(index) { this.formData.trust_signals.splice(index, 1); },
        openHighlightIconPicker(index) {
            this.pickerMode = 'highlight';
            this.currentBenefitIndex = index;
            this.showIconPicker = true;
            this.$nextTick(() => lucide.createIcons());
        },
        openNutriIconPicker(index) {
            this.pickerMode = 'nutri_highlight';
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
        },
        removeExistingGalleryImage(path) {
            confirmAction('Remove Image?', 'This image will be removed from the product gallery.', () => {
                this.formData.removed_gallery_images.push(path);
                this.formData.existing_gallery_count--;
                const pond = FilePond.find(document.querySelector('.filepond-gallery'));
                const pondFilesCount = pond ? pond.getFiles().length : 0;
                const total = this.formData.existing_gallery_count + pondFilesCount;
                const badge = document.getElementById('gallery-count');
                if (badge) badge.innerText = total + '/4 Active';
                this.$nextTick(() => lucide.createIcons());
                toast('Image marked for deletion');
            });
        },
        openIconPicker(index) { this.pickerMode = 'benefit'; this.currentBenefitIndex = index; this.showIconPicker = true; },
        openTrustIconPicker(index) { this.pickerMode = 'trust'; this.currentBenefitIndex = index; this.showIconPicker = true; },
        selectIcon(icon) {
            if(this.pickerMode === 'benefit') {
                this.formData.benefits[this.currentBenefitIndex].icon = icon;
            } else if(this.pickerMode === 'trust') {
                this.formData.trust_signals[this.currentBenefitIndex].icon = icon;
            } else if(this.pickerMode === 'nutri_highlight') {
                this.formData.nutrition_highlights[this.currentBenefitIndex].icon = icon;
            } else {
                this.formData.highlights_list[this.currentBenefitIndex].icon = icon;
            }
            this.showIconPicker = false;
            this.$nextTick(() => refreshIcons());
        },
        initFilePond() {
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginFileValidateSize,
                FilePondPluginFileValidateType
            );

            FilePond.create(document.querySelector('.filepond-main'), { 
                storeAsFile: true,
                maxFileSize: '2MB',
                acceptedFileTypes: ['image/*']
            });

            const gallery = FilePond.create(document.querySelector('.filepond-gallery'), { 
                storeAsFile: true, 
                allowMultiple: true,
                maxFiles: 4,
                maxFileSize: '2MB',
                acceptedFileTypes: ['image/*']
            });

            gallery.on('updatefiles', (files) => {
                const total = this.formData.existing_gallery_count + files.length;
                document.getElementById('gallery-count').innerText = total + '/4 Active';
            });
        },
        async quickAddCategory(event) {
            if (!this.newCategoryName) return;
            const btn = event.currentTarget;
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader-2" class="h-3 w-3 animate-spin"></i>';
            refreshIcons();

            try {
                const response = await fetch('{{ route("admin.categories.quick-add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: this.newCategoryName })
                });
                const data = await response.json();
                if (data.success) {
                    const list = document.getElementById('category-list');
                    const label = document.createElement('label');
                    label.className = 'flex items-center justify-between p-3 rounded-xl border border-slate-50 hover:bg-slate-50 transition-all cursor-pointer group';
                    label.innerHTML = `
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="categories[]" value="${data.category.id}" checked class="w-5 h-5 rounded-lg border-2 border-slate-200 text-orange-500 focus:ring-orange-500 transition-all">
                            <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900">${data.category.name}</span>
                        </div>
                        <button type="button" 
                                onclick="window.dispatchEvent(new CustomEvent('delete-category-event', { detail: { id: ${data.category.id}, name: '${data.category.name.replace(/'/g, "\\'")}' } }))" 
                                class="opacity-0 group-hover:opacity-100 transition-opacity h-7 w-7 rounded-full hover:bg-rose-50 text-slate-300 hover:text-rose-500 flex items-center justify-center">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    `;
                    list.appendChild(label);
                    this.formData.categories.push(String(data.category.id));
                    this.newCategoryName = '';
                } else {
                    toast(data.message || 'Error adding category', 'error');
                }
            } catch (error) {
                console.error(error);
                toast('Failed to add category', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
                refreshIcons();
            }
        },
        async deleteCategory(id, name) {
            confirmAction('Delete Category?', `Are you sure you want to delete category "${name}"? This will remove it from all products.`, async () => {
                try {
                    const response = await fetch(`/admin/categories/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    });
                    const data = await response.json();
                    if (data.success) {
                        toast(data.message || 'Category deleted successfully');
                        this.formData.categories = this.formData.categories.filter(c => c != id);
                        
                        const list = document.getElementById('category-list');
                        if (list) {
                            const checkbox = list.querySelector(`input[value="${id}"]`);
                            if (checkbox) {
                                const label = checkbox.closest('label');
                                if (label) label.remove();
                            }
                        }
                    } else {
                        toast(data.message || 'Error deleting category', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    toast('Failed to delete category', 'error');
                }
            });
        },
        submitForm(event) {
            // Sync all CKEditors back to their respective textareas
            Object.values(editors).forEach(editor => {
                if (editor.updateSourceElement) editor.updateSourceElement();
            });

            fastSubmit('#product-form', {
                button: event.currentTarget,
                success: (data) => {
                    this.persistence.clear();
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Product changes have been synchronized.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '{{ route("admin.products.index") }}';
                    });
                }
            });
        }
    }
}
</script>

<style>
    [x-cloak] { display: none !important; }
    .ck-editor__editable_inline {
        min-height: 100px;
        padding: 0 20px !important;
    }

    /* CKEditor Dark Mode (Matches Create Page logic) */
    .bg-slate-900 .ck-reset_all { color: #333 !important; }
    .bg-slate-900 .ck-editor__editable {
        background: #1e293b !important;
        color: #f1f5f9 !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
    }

    /* Premium Specificity Fixes for Orange/Dark Cards */
    .bg-orange-600.saas-card, .bg-slate-900.saas-card { background-image: none !important; }
    .bg-orange-600 .saas-input, .bg-slate-900 .saas-input { 
        background: rgba(255,255,255,0.1) !important; 
        color: white !important; 
        border-color: rgba(255,255,255,0.2) !important; 
    }
    .bg-orange-600 .saas-label, .bg-slate-900 .saas-label { color: rgba(255,255,255,0.7) !important; }
    .bg-orange-600 .saas-input::placeholder, .bg-slate-900 .saas-input::placeholder { color: rgba(255,255,255,0.3) !important; }
</style>
@endsection
