@extends('admin.layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full pr-4">
        <div class="flex items-center gap-8">
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5 text-slate-400 hover:text-orange-500 transition-all font-bold text-sm group">
                <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                </div>
                Back
            </a>
            <div class="h-10 w-px bg-slate-100 hidden md:block"></div>
            <div class="flex flex-col justify-center">
                <h2 class="font-black text-xl text-slate-800 leading-none tracking-tight">Create Product</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="h-1 w-1 bg-orange-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] font-black">Inventory Expansion</p>
                </div>
            </div>
        </div>
        
        <button type="button" onclick="document.querySelector('form').submit()" class="bg-orange-500 text-white px-6 py-3 rounded-2xl text-[11px] font-black shadow-[0_10px_20px_-5px_rgba(234,95,6,0.3)] hover:scale-105 hover:shadow-[0_15px_25px_-5px_rgba(234,95,6,0.4)] transition-all flex items-center gap-3 uppercase tracking-widest">
            <div class="h-5 w-5 bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="save" class="h-3.5 w-3.5"></i>
            </div>
            Save Product
        </button>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- General Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">General Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Product Title*</label>
                            <input type="text" name="title" required value="{{ old('title') }}" 
                                   class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="e.g. Vitamin C 1000mg Capsules">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Slug (Unique URL)*</label>
                                <input type="text" name="slug" required value="{{ old('slug') }}" 
                                       class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="vitamin-c-1000mg">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Tagline</label>
                                <input type="text" name="tagline" value="{{ old('tagline') }}" 
                                       class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="Double the Protection">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Short Description</label>
                            <textarea name="description" rows="3" 
                                      class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder="Brief summary of the product...">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Detailed Description (HTML/Rich Text)</label>
                            <textarea name="long_description" id="editor" rows="10" 
                                      class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500">{{ old('long_description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Features & Specs -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Benefits & Specifications (JSON)</h3>
                        <p class="text-[10px] text-gray-400 font-mono">Format: ["Item 1", "Item 2"]</p>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Key Benefits</label>
                            <textarea name="benefits" rows="4" 
                                      class="w-full font-mono text-xs rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder='["Boosts Immunity", "Natural Antioxidant"]'>{{ old('benefits') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Technical Specs</label>
                            <textarea name="specs" rows="4" 
                                      class="w-full font-mono text-xs rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder='{"Capsule Count": "60", "Servings": "30"}'>{{ old('specs') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Settings -->
            <div class="space-y-6">
                <!-- Status & Visibility -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Visibility & Status</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-700">Publish Status</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-700">Featured Product</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Pricing & Rating</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">MRP (₹)</label>
                                <input type="number" name="mrp" step="0.01" required value="{{ old('mrp') }}" 
                                       class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Selling Price (₹)</label>
                                <input type="number" name="price" step="0.01" required value="{{ old('price') }}" 
                                       class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Initial Rating</label>
                                <input type="number" name="rating" step="0.1" value="5.0" 
                                       class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Total Reviews</label>
                                <input type="number" name="reviews" value="0" 
                                       class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Product Media</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Main Image*</label>
                            <input type="file" name="image" required class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-[#ea5f06] hover:file:bg-orange-100">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Gallery (Multiple)</label>
                            <input type="file" name="gallery[]" multiple class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                        </div>
                    </div>
                </div>

                <!-- Visual Customization -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Theme & UI</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Color Theme</label>
                            <select name="color_theme" class="w-full rounded-xl border-gray-200">
                                <option value="orange">Brand Orange</option>
                                <option value="sage">Sage Green</option>
                                <option value="peach">Peach Pink</option>
                                <option value="blue">Classic Blue</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer for Actions -->
        <div class="sticky bottom-6 bg-white/80 backdrop-blur-md p-4 rounded-2xl shadow-2xl border border-gray-200 flex justify-end gap-3 z-10">
            <button type="reset" class="px-6 py-2 rounded-xl text-gray-600 font-bold hover:bg-gray-100 transition">Discard</button>
            <button type="submit" class="px-8 py-2 bg-[#ea5f06] text-white font-bold rounded-xl shadow-lg hover:bg-[#cf5305] transition transform active:scale-95">Save Product</button>
        </div>
    </form>

    <!-- CKEditor for Rich Text -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                ckfinder: {
                    uploadUrl: '{{ route("admin.editor.upload", ["_token" => csrf_token()]) }}'
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
