@extends('admin.layouts.app')

@section('header')
    <div class="flex flex-wrap items-center justify-between w-full pr-4 gap-4">
        <!-- Title Group -->
        <div class="flex items-center gap-3 md:gap-6">
            <a href="{{ route('admin.sliders.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-orange-500 transition-all font-bold text-xs group">
                <div class="h-7 w-7 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                    <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
                </div>
            </a>
            <div class="h-8 w-px bg-slate-100"></div>
            <div class="flex flex-col">
                <h2 class="font-black text-base md:text-lg text-slate-800 leading-tight">Add Slider</h2>
                <p class="text-[8px] text-slate-400 uppercase tracking-widest font-black">New Banner</p>
            </div>
        </div>
        
        <!-- Action Group -->
        <div class="flex items-center gap-2">
            <button type="button" onclick="document.getElementById('slider-form').submit()" class="bg-orange-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-black shadow-lg shadow-orange-500/20 hover:scale-105 transition-all flex items-center gap-2 uppercase tracking-widest whitespace-nowrap">
                <i data-lucide="plus" class="h-3.5 w-3.5"></i>
                <span>Create Slider</span>
            </button>
        </div>
    </div>
@endsection

@section('content')
    <form id="slider-form" action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="max-w-[1900px] mx-auto">
        @csrf
        
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
            
            <!-- Column 1: Slider Specs & Editor Controls -->
            <div class="xl:col-span-5 space-y-6">
                <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm space-y-8 sticky top-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <!-- <span class="h-1 w-4 bg-orange-500 rounded-full"></span> -->
                        Slider Specs
                    </h3>

                    <!-- Assets -->
                    <div class="space-y-6">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between px-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Desktop Banner</label>
                                <span class="text-[9px] font-black text-orange-500 bg-orange-50 px-2 py-0.5 rounded-md border border-orange-100">1200x450 PX</span>
                            </div>
                            <input type="file" name="image_desktop" id="image_desktop" class="hidden" accept="image/*" required onchange="previewImage(this, 'desktop-preview')">
                            <label for="image_desktop" class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 hover:border-orange-300 hover:bg-orange-50/50 cursor-pointer transition-all group">
                                <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center text-slate-400 group-hover:text-orange-500 shadow-sm transition-all group-hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-slate-700">Select Desktop</p>
                                    <p class="text-[9px] text-slate-400">Drag & Drop or Click</p>
                                </div>
                            </label>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between px-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mobile Banner</label>
                                <span class="text-[9px] font-black text-orange-500 bg-orange-50 px-2 py-0.5 rounded-md border border-orange-100">1200x1200PX</span>
                            </div>
                            <input type="file" name="image_mobile" id="image_mobile" class="hidden" accept="image/*" required onchange="previewImage(this, 'mobile-preview')">
                            <label for="image_mobile" class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 hover:border-orange-300 hover:bg-orange-50/50 cursor-pointer transition-all group">
                                <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center text-slate-400 group-hover:text-orange-500 shadow-sm transition-all group-hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-slate-700">Select Mobile</p>
                                    <p class="text-[9px] text-slate-400">Square ratio works best</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Fields -->
                    <div class="pt-4 space-y-5">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Order</label>
                                <input type="number" name="order" value="0" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-orange-500 font-black text-slate-800">
                            </div>
                            <div class="col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">URL</label>
                                <input type="text" name="link" placeholder="https://..." class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-orange-500 font-bold text-slate-700">
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4 items-end">
                            <div class="col-span-3 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alt Text</label>
                                <input type="text" name="alt_text" placeholder="Banner description..." class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-orange-500 font-bold text-slate-700">
                            </div>
                            <div class="col-span-1 space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block text-center">Status</label>
                                <div class="flex items-center justify-center h-[52px]">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="status" value="1" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5 shadow-md"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 2 & 3: Half Screen Previews -->
            <div class="xl:col-span-7 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Desktop Card -->
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 shadow-sm border border-orange-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Desktop</h4>
                                <p class="text-[8px] text-slate-400 font-bold uppercase">MacBook 16"</p>
                            </div>
                        </div>
                        <div id="desktop-fit-controls" class="flex items-center gap-1 bg-slate-50 p-1 rounded-lg border border-slate-100 opacity-50 grayscale pointer-events-none transition-all duration-300">
                            <button type="button" onclick="setFit('desktop', 'cover', this)" class="px-3 py-1.5 rounded-md text-[8px] font-black uppercase bg-orange-500 text-white shadow-md border border-transparent scale-[1.02] transition-all flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3"><path d="M8 3H5a2 2 0 0 0-2 2v3"/><path d="M21 8V5a2 2 0 0 0-2-2h-3"/><path d="M3 16v3a2 2 0 0 0 2 2h3"/><path d="M16 21h3a2 2 0 0 0 2-2v-3"/></svg> Cover</button>
                            <button type="button" onclick="setFit('desktop', 'contain', this)" class="px-3 py-1.5 rounded-md text-[8px] font-black uppercase bg-white text-slate-500 border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3"><path d="M8 3v3a2 2 0 0 1-2 2H3"/><path d="M21 8h-3a2 2 0 0 1-2-2V3"/><path d="M3 16h3a2 2 0 0 1 2 2v3"/><path d="M16 21v-3a2 2 0 0 1 2-2h3"/></svg> Contain</button>
                        </div>
                    </div>

                    <div class="flex-1 bg-slate-50 rounded-2xl border border-slate-100 p-4 flex items-center justify-center">
                        <div class="w-full">
                            <div class="relative aspect-[8/3] bg-slate-900 rounded-xl p-1.5 shadow-xl border border-white/10 overflow-hidden">
                                <div id="desktop-fit-badge" class="hidden absolute top-3 right-3 z-20 bg-orange-500/90 backdrop-blur-md text-white text-[8px] font-black uppercase tracking-widest px-2.5 py-1.5 rounded-md border border-orange-400 shadow-lg transition-all">COVER MODE (CROPPED)</div>
                                <div id="desktop-container" class="h-full w-full bg-slate-200 rounded-lg overflow-hidden relative group preview-fit-cover transition-colors duration-300">
                                    <div id="desktop-crop-hint" class="absolute inset-0 border-[4px] border-orange-500/30 transition-all duration-300 pointer-events-none z-20 mix-blend-overlay scale-100"></div>
                                    <img id="desktop-preview" src="" class="w-full h-full object-cover hidden relative z-10 transition-all duration-300 scale-100">
                                    <div id="desktop-preview-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
                                        <i data-lucide="image" class="h-8 w-8 text-slate-300 mb-2"></i>
                                        <p class="text-[8px] font-black text-slate-400 uppercase">Awaiting Image</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mx-auto w-[102%] -ml-[1%] h-2 bg-slate-800 rounded-b-lg"></div>
                            <p id="desktop-indicator-text" class="text-[9px] font-bold text-slate-500 mt-3 text-center transition-all opacity-0 duration-300">Image is currently cropped to fill container</p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Card -->
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 shadow-sm border border-orange-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Mobile</h4>
                                <p class="text-[8px] text-slate-400 font-bold uppercase">iPhone 15 Pro</p>
                            </div>
                        </div>
                        <div id="mobile-fit-controls" class="flex items-center gap-1 bg-slate-50 p-1 rounded-lg border border-slate-100 opacity-50 grayscale pointer-events-none transition-all duration-300">
                            <button type="button" onclick="setFit('mobile', 'cover', this)" class="px-3 py-1.5 rounded-md text-[8px] font-black uppercase bg-orange-500 text-white shadow-md border border-transparent scale-[1.02] transition-all flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3"><path d="M8 3H5a2 2 0 0 0-2 2v3"/><path d="M21 8V5a2 2 0 0 0-2-2h-3"/><path d="M3 16v3a2 2 0 0 0 2 2h3"/><path d="M16 21h3a2 2 0 0 0 2-2v-3"/></svg> Cover</button>
                            <button type="button" onclick="setFit('mobile', 'contain', this)" class="px-3 py-1.5 rounded-md text-[8px] font-black uppercase bg-white text-slate-500 border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3 w-3"><path d="M8 3v3a2 2 0 0 1-2 2H3"/><path d="M21 8h-3a2 2 0 0 1-2-2V3"/><path d="M3 16h3a2 2 0 0 1 2 2v3"/><path d="M16 21v-3a2 2 0 0 1 2-2h3"/></svg> Contain</button>
                        </div>
                    </div>

                    <div class="flex-1 bg-slate-50 rounded-2xl border border-slate-100 p-4 flex flex-col items-center justify-center gap-4">
                        <div class="relative w-[280px] h-[280px] bg-slate-900 rounded-[2rem] p-1.5 shadow-xl border border-white/10 ring-2 ring-slate-800 shrink-0">
                                <div id="mobile-fit-badge" class="hidden absolute top-4 right-2 z-20 bg-orange-500/90 backdrop-blur-md text-white text-[6px] font-black uppercase tracking-widest px-1.5 py-1 rounded-md border border-orange-400 shadow-lg transition-all">COVER MODE</div>
                                <div id="mobile-container" class="h-full w-full bg-slate-200 rounded-[1.8rem] overflow-hidden relative group preview-fit-cover transition-colors duration-300">
                                    <div id="mobile-crop-hint" class="absolute inset-0 border-[4px] border-orange-500/30 transition-all duration-300 pointer-events-none z-20 mix-blend-overlay scale-100"></div>
                                    <img id="mobile-preview" src="" class="w-full h-full object-cover hidden relative z-10 transition-all duration-300 scale-100">
                                    <div id="mobile-preview-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
                                        <i data-lucide="smartphone" class="h-6 w-6 text-slate-300 mb-1"></i>
                                        <p class="text-[7px] font-black text-slate-400 uppercase">Wait Asset</p>
                                    </div>
                                </div>
                        </div>
                        <p id="mobile-indicator-text" class="text-[9px] font-bold text-slate-500 text-center transition-all opacity-0 duration-300">Image is currently cropped to fill container</p>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        // Initialize Icons for this page
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });

        let desktopCropper = null;
        let mobileCropper = null;

        function initCropper(type, img) {
            const cropperOpts = {
                aspectRatio: type === 'desktop' ? 8 / 3 : 1 / 1,
                viewMode: 0,
                dragMode: 'move',
                autoCropArea: 1,
                cropBoxMovable: false,
                cropBoxResizable: false,
                guides: true,
                background: false,
                cropend: function() {
                    if (type === 'desktop') desktopCropper.isModified = true;
                    if (type === 'mobile') mobileCropper.isModified = true;
                }
            };
            
            if (type === 'desktop') {
                if (desktopCropper) desktopCropper.destroy();
                desktopCropper = new Cropper(img, cropperOpts);
                desktopCropper.isModified = true;
            } else {
                if (mobileCropper) mobileCropper.destroy();
                mobileCropper = new Cropper(img, cropperOpts);
                mobileCropper.isModified = true;
            }
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(previewId);
                    const placeholder = document.getElementById(previewId + '-placeholder');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if(placeholder) placeholder.classList.add('hidden');
                    
                    const type = previewId.split('-')[0];
                    const controls = document.getElementById(type + '-fit-controls');
                    if(controls) controls.classList.remove('opacity-50', 'grayscale', 'pointer-events-none');
                    
                    const badge = document.getElementById(type + '-fit-badge');
                    if(badge) badge.classList.remove('hidden');
                    
                    const indicator = document.getElementById(type + '-indicator-text');
                    if(indicator) indicator.classList.remove('opacity-0');
                    
                    initCropper(type, img);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function setFit(type, fit, btn) {
            const container = document.getElementById(type + '-container');
            const img = document.getElementById(type + '-preview');
            const badge = document.getElementById(type + '-fit-badge');
            const indicator = document.getElementById(type + '-indicator-text');
            const hint = document.getElementById(type + '-crop-hint');
            
            const isCover = fit === 'cover';
            const cropper = type === 'desktop' ? desktopCropper : mobileCropper;
            
            if(badge) {
                badge.innerText = isCover ? (type === 'desktop' ? 'COVER MODE (CROPPED)' : 'COVER MODE') : (type === 'desktop' ? 'CONTAIN MODE (FULL IMAGE)' : 'CONTAIN MODE');
                if(isCover) {
                    badge.className = `absolute ${type === 'desktop' ? 'top-3 right-3 text-[8px] px-2.5 py-1.5' : 'top-4 right-2 text-[6px] px-1.5 py-1'} z-20 bg-orange-500/90 backdrop-blur-md text-white font-black uppercase tracking-widest rounded-md border border-orange-400 shadow-lg transition-all`;
                } else {
                    badge.className = `absolute ${type === 'desktop' ? 'top-3 right-3 text-[8px] px-2.5 py-1.5' : 'top-4 right-2 text-[6px] px-1.5 py-1'} z-20 bg-indigo-500/90 backdrop-blur-md text-white font-black uppercase tracking-widest rounded-md border border-indigo-400 shadow-lg transition-all`;
                }
            }
            
            if(indicator) {
                indicator.innerText = isCover ? 'Image is currently cropped to fill container' : 'Full image is visible with spacing';
            }
            
            if(hint) {
                if(isCover) {
                    hint.classList.add('border-orange-500/30', 'scale-100');
                    hint.classList.remove('border-transparent', 'scale-105');
                } else {
                    hint.classList.remove('border-orange-500/30', 'scale-100');
                    hint.classList.add('border-transparent', 'scale-105');
                }
            }
            
            if(!isCover) {
                container.classList.replace('bg-slate-200', 'bg-slate-300');
                if(cropper) {
                    cropper.moveTo(0,0);
                    cropper.zoomTo(0.1);
                    cropper.isModified = true;
                }
            } else {
                container.classList.replace('bg-slate-300', 'bg-slate-200');
                if(cropper) {
                    cropper.reset();
                    cropper.isModified = true;
                }
            }
            
            const btnContainer = btn.parentElement;
            btnContainer.querySelectorAll('button').forEach(b => {
                b.className = 'px-3 py-1.5 rounded-md text-[8px] font-black uppercase bg-white text-slate-500 border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-1';
            });
            btn.className = 'px-3 py-1.5 rounded-md text-[8px] font-black uppercase bg-orange-500 text-white shadow-md border border-transparent scale-[1.02] transition-all flex items-center gap-1';
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const desktopNeedsSave = desktopCropper && (desktopCropper.isModified || document.getElementById('image_desktop').files.length > 0);
            const mobileNeedsSave = mobileCropper && (mobileCropper.isModified || document.getElementById('image_mobile').files.length > 0);
            
            if (!desktopNeedsSave && !mobileNeedsSave) return;
            
            e.preventDefault();
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if(submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin inline"></i> Processing...';
                if(window.lucide) window.lucide.createIcons();
            }
            
            const processMobile = () => {
                if (mobileNeedsSave) {
                    mobileCropper.getCroppedCanvas({ width: 1200, height: 1200, fillColor: '#ffffff' }).toBlob((blob) => {
                        let file = new File([blob], "mobile.webp", { type: "image/webp" });
                        let dt = new DataTransfer();
                        dt.items.add(file);
                        document.getElementById('image_mobile').files = dt.files;
                        form.submit();
                    }, 'image/webp', 0.85);
                } else {
                    form.submit();
                }
            };

            if (desktopNeedsSave) {
                desktopCropper.getCroppedCanvas({ width: 1200, height: 450, fillColor: '#ffffff' }).toBlob((blob) => {
                    let file = new File([blob], "desktop.webp", { type: "image/webp" });
                    let dt = new DataTransfer();
                    dt.items.add(file);
                    document.getElementById('image_desktop').files = dt.files;
                    processMobile();
                }, 'image/webp', 0.85);
            } else {
                processMobile();
            }
        });
    </script>
@endsection
