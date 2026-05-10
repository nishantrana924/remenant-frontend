@props(['route', 'previewUrl' => 'your-site.com'])

<div x-data="{ 
    showPreview: false, 
    previewMode: 'mobile', 
    isLoadingPreview: false,
    previewContent: {},
    
    openPreview(content) {
        this.previewContent = content;
        this.showPreview = true;
        this.isLoadingPreview = true;
        this.$nextTick(() => {
            this.$refs.previewForm.submit();
        });
    }
}" 
@open-preview.window="openPreview($event.detail)"
class="relative">
    
    <!-- Preview Engine Modal -->
    <div x-show="showPreview" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="fixed inset-0 z-[500] bg-slate-950/95 backdrop-blur-2xl flex flex-col items-center justify-center p-4 lg:p-8">
        
        <!-- Preview Controls -->
        <div class="w-full max-w-6xl flex items-center justify-between mb-8">
            <div class="flex items-center gap-6">
                <div class="bg-white/5 p-1.5 rounded-2xl border border-white/10 flex items-center shadow-2xl">
                    <button @click="previewMode = 'mobile'" 
                            :class="previewMode === 'mobile' ? 'bg-white text-slate-950 shadow-lg scale-105' : 'text-white/50 hover:text-white'"
                            class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2.5">
                        <i data-lucide="smartphone" class="w-4 h-4"></i> Mobile
                    </button>
                    <button @click="previewMode = 'desktop'" 
                            :class="previewMode === 'desktop' ? 'bg-white text-slate-950 shadow-lg scale-105' : 'text-white/50 hover:text-white'"
                            class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2.5">
                        <i data-lucide="monitor" class="w-4 h-4"></i> Desktop
                    </button>
                </div>
                <div class="hidden md:flex items-center gap-3 text-white/40 text-[10px] font-black uppercase tracking-[0.2em] bg-white/5 px-5 py-3 rounded-2xl border border-white/5 shadow-inner">
                    <div class="flex gap-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-orange-500/50"></div>
                    </div>
                    Engine Active
                </div>
            </div>
            <button @click="showPreview = false" class="h-14 w-14 rounded-2xl bg-white/5 text-white flex items-center justify-center hover:bg-rose-500/20 hover:text-rose-400 transition-all border border-white/10 shadow-xl group">
                <i data-lucide="x" class="w-7 h-7 group-hover:rotate-90 transition-transform duration-500"></i>
            </button>
        </div>

        <!-- Preview Frame Container -->
        <div class="w-full flex-1 flex items-center justify-center overflow-hidden py-4">
            <!-- The Mockup Device -->
            <div :class="previewMode === 'desktop' ? 'w-full h-full max-w-[1300px] rounded-[2.5rem] border-[10px]' : 'w-[360px] h-[740px] rounded-[3.5rem] border-[14px]'" 
                 class="bg-white shadow-[0_60px_150px_-30px_rgba(0,0,0,1)] overflow-hidden transition-all duration-700 relative border-slate-900 mx-auto ring-4 ring-slate-800/50">
                
                <!-- Premium Mobile Specific Elements (iPhone style) -->
                <template x-if="previewMode === 'mobile'">
                    <div class="contents">
                        <!-- Dynamic Island -->
                        <div class="absolute top-2 left-1/2 -translate-x-1/2 w-28 h-7 bg-slate-950 rounded-full z-[80] flex items-center justify-center border border-white/5 shadow-2xl">
                            <div class="absolute right-6 w-1.5 h-1.5 rounded-full bg-[#1a1a1a] border border-white/5"></div>
                        </div>
                        
                        <!-- Side Buttons -->
                        <div class="absolute -left-[16px] top-32 w-[3px] h-14 bg-slate-800 rounded-r-md"></div> <!-- Volume Up -->
                        <div class="absolute -left-[16px] top-52 w-[3px] h-14 bg-slate-800 rounded-r-md"></div> <!-- Volume Down -->
                        <div class="absolute -right-[16px] top-40 w-[3px] h-20 bg-slate-800 rounded-l-md"></div> <!-- Power -->

                        <!-- Screen Reflection -->
                        <div class="absolute inset-0 pointer-events-none bg-gradient-to-tr from-transparent via-white/[0.03] to-white/[0.08] z-[75]"></div>
                        
                        <!-- Bottom Indicator -->
                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 w-32 h-1.5 bg-slate-950/20 rounded-full z-[80]"></div>
                    </div>
                </template>

                <!-- Desktop Specific Elements -->
                <template x-if="previewMode === 'desktop'">
                    <div class="absolute top-0 left-0 right-0 h-12 bg-slate-50 border-b border-slate-200 z-[60] flex items-center px-6 gap-4">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-rose-400 shadow-inner"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-400 shadow-inner"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-400 shadow-inner"></div>
                        </div>
                        <div class="flex-1 max-w-xl mx-auto h-8 bg-white rounded-lg border border-slate-200 flex items-center px-4 gap-3 shadow-sm">
                            <i data-lucide="lock" class="w-3 h-3 text-emerald-500"></i>
                            <span class="text-[10px] text-slate-400 font-bold tracking-tight truncate">{{ $previewUrl }}</span>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-4 h-4 rounded bg-slate-200"></div>
                            <div class="w-4 h-4 rounded bg-slate-200"></div>
                        </div>
                    </div>
                </template>

                <!-- Loading State Overlay -->
                <div x-show="isLoadingPreview" 
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 bg-slate-950 flex flex-col items-center justify-center space-y-6 z-[100]">
                    <div class="relative">
                        <div class="h-16 w-16 border-4 border-white/5 border-t-orange-500 rounded-full animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="h-2 w-2 bg-orange-500 rounded-full animate-ping"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-black text-white uppercase tracking-[0.4em] mb-2">Syncing Data</p>
                        <div class="flex gap-1 justify-center">
                            <div class="w-1 h-1 bg-orange-500 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                            <div class="w-1 h-1 bg-orange-500 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                            <div class="w-1 h-1 bg-orange-500 rounded-full animate-bounce"></div>
                        </div>
                    </div>
                </div>

                <iframe name="global-preview-frame" 
                        @load="isLoadingPreview = false"
                        class="w-full h-full border-none bg-white transition-all duration-300" 
                        :class="previewMode === 'desktop' ? 'pt-12' : ''"></iframe>
            </div>
        </div>

        <!-- Hidden Form for Preview Data Submission -->
        <form x-ref="previewForm" action="{{ $route }}" method="POST" target="global-preview-frame" class="hidden">
            @csrf
            <input type="hidden" name="content" :value="JSON.stringify(previewContent)">
        </form>

        <div class="mt-8 flex flex-col items-center gap-2">
            <div class="flex items-center gap-3 opacity-30 group hover:opacity-100 transition-opacity duration-500">
                <i data-lucide="shield-check" class="w-3 h-3 text-orange-500"></i>
                <p class="text-[10px] text-white font-black uppercase tracking-[0.5em]">REMENANT SECURE PREVIEW SYSTEM</p>
            </div>
            <p class="text-[8px] text-white/10 font-bold uppercase tracking-[0.2em]">Build 2026.05.08.01</p>
        </div>
    </div>
</div>
