@extends('admin.layouts.app')

@section('header_scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endsection

@section('title', 'About Page Editor')

@section('content')
<div class="space-y-6 pb-24" 
     x-data="{ 
        activeTab: localStorage.getItem('about_cms_last_tab') || 'hero',
        content: @js($about->content),
        status: @js($about->status),
        isSaving: false,
        hasUnsavedChanges: false,
        originalContent: null,
        editors: {},
        showIconPicker: false,
        iconSearch: '',
        currentFeatureIndex: null,
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

        get filteredIcons() {
            if (!this.iconSearch) return this.iconLibrary;
            return this.iconLibrary.filter(i => i.includes(this.iconSearch.toLowerCase()));
        },

        showMediaLibrary: false,
        mediaFiles: [],
        isLoadingMedia: false,
        mediaTargetPath: '',
        mediaTargetKey: '',

        showHistory: false,
        versions: @js($versions),
        isRestoring: false,

        init() {
            // Ensure SEO object exists
            if (!this.content.seo) {
                this.content.seo = { title: '', description: '' };
            }

            this.originalContent = JSON.parse(JSON.stringify(this.content));
            this.$watch('content', () => {
                this.hasUnsavedChanges = JSON.stringify(this.content) !== JSON.stringify(this.originalContent);
            }, { deep: true });

            this.$watch('activeTab', (val) => {
                localStorage.setItem('about_cms_last_tab', val);
                this.$nextTick(() => {
                    if(window.lucide) lucide.createIcons();
                    if(val === 'hero') this.initEditor('heroDescEditor', 'hero.description');
                });
            });

            this.$watch('showIconPicker', value => { if(value) this.$nextTick(() => lucide.createIcons()); });

            setTimeout(() => {
                if(window.lucide) lucide.createIcons();
                this.initEditor('heroDescEditor', 'hero.description');
                
                // Initialize Sortable for Quality Pledges
                if (this.$refs.featuresList) {
                    new Sortable(this.$refs.featuresList, {
                        animation: 150,
                        handle: '.drag-handle',
                        onEnd: (evt) => {
                            const item = this.content.features.splice(evt.oldIndex, 1)[0];
                            this.content.features.splice(evt.newIndex, 0, item);
                        }
                    });
                }

                // Initialize Sortable for Process Steps
                if (this.$refs.processList) {
                    new Sortable(this.$refs.processList, {
                        animation: 150,
                        handle: '.drag-handle',
                        onEnd: (evt) => {
                            const item = this.content.process.steps.splice(evt.oldIndex, 1)[0];
                            this.content.process.steps.splice(evt.newIndex, 0, item);
                            // Re-number steps
                            this.content.process.steps.forEach((s, i) => s.number = i + 1);
                        }
                    });
                }

                // Initialize Sortable for Founders
                if (this.$refs.foundersList) {
                    new Sortable(this.$refs.foundersList, {
                        animation: 150,
                        handle: '.drag-handle',
                        onEnd: (evt) => {
                            const item = this.content.founders.list.splice(evt.oldIndex, 1)[0];
                            this.content.founders.list.splice(evt.newIndex, 0, item);
                        }
                    });
                }
            }, 500);
        },

        initEditor(ref, path) {
            if (this.editors[path]) return;
            const target = this.$refs[ref];
            if (!target) return;
            if (typeof ClassicEditor === 'undefined') return;

            ClassicEditor.create(target, {
                toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'undo', 'redo'],
            }).then(editor => {
                this.editors[path] = editor;
                editor.model.document.on('change:data', () => {
                    this.setDeepValue(this.content, path, editor.getData());
                });
            });
        },

        setDeepValue(obj, path, value) {
            const keys = path.split('.');
            let current = obj;
            for (let i = 0; i < keys.length - 1; i++) {
                if (!current[keys[i]]) current[keys[i]] = {};
                current = current[keys[i]];
            }
            current[keys[keys.length - 1]] = value;
        },

        assetUrl(path) {
            if (!path) return 'https://ui-avatars.com/api/?name=Image&background=f1f5f9&color=94a3b8';
            return path.startsWith('http') ? path : '/' + path;
        },

        triggerUpload(id) { document.getElementById(id).click(); },

        async handleFileUpload(event, path, key) {
            const file = event.target.files[0];
            if (!file) return;
            const formData = new FormData();
            formData.append('image', file);
            formData.append('folder', 'about');
            try {
                const response = await axios.post('{{ route("admin.upload") }}', formData);
                if (response.data.success) {
                    this.setDeepValue(this.content, path + (key ? '.' + key : ''), response.data.path);
                    window.toast('Image uploaded');
                    this.$nextTick(() => lucide.createIcons());
                }
            } catch (e) { window.toast('Upload failed', 'error'); }
        },

        async openMediaLibrary(path, key) {
            this.mediaTargetPath = path;
            this.mediaTargetKey = key;
            this.showMediaLibrary = true;
            this.isLoadingMedia = true;
            try {
                const response = await axios.get('{{ route("admin.media-list") }}?folder=uploads/about');
                if (response.data.success) {
                    this.mediaFiles = response.data.files;
                }
            } catch (e) { window.toast('Failed to load media', 'error'); }
            finally { 
                this.isLoadingMedia = false; 
                this.$nextTick(() => lucide.createIcons());
            }
        },

        selectMedia(path) {
            this.setDeepValue(this.content, this.mediaTargetPath + (this.mediaTargetKey ? '.' + this.mediaTargetKey : ''), path);
            this.showMediaLibrary = false;
            window.toast('Image selected');
            this.$nextTick(() => lucide.createIcons());
        },

        openPreview() {
            window.dispatchEvent(new CustomEvent('open-preview', { detail: this.content }));
        },

        async restoreVersion(id) {
            window.confirmAction('Restore this version?', 'Your current unsaved changes will be overwritten.', async () => {
                this.isRestoring = true;
                try {
                    const response = await axios.post(`/admin/about/restore/${id}`);
                    if (response.data.success) {
                        this.content = response.data.content;
                        this.originalContent = JSON.parse(JSON.stringify(this.content));
                        this.hasUnsavedChanges = false;
                        
                        // Sync CKEditors
                        for (let path in this.editors) {
                            this.editors[path].setData(this.getDeepValue(this.content, path));
                        }

                        window.toast('Version restored!');
                        this.showHistory = false;
                        this.$nextTick(() => lucide.createIcons());
                    }
                } catch (e) { window.toast('Restore failed', 'error'); }
                finally { this.isRestoring = false; }
            });
        },

        addFounder() {
            this.content.founders.list.push({
                name: 'New Founder',
                role: 'Founder Role',
                degree: 'MBBS/Engineer',
                bio: 'Bio text here...',
                image: '',
                reverse: false
            });
            this.$nextTick(() => lucide.createIcons());
        },

        removeFounder(index) {
            window.confirmAction('Delete Profile?', 'This founder will be removed.', () => {
                this.content.founders.list.splice(index, 1);
            });
        },

        addFeature() {
            this.content.features.push({
                icon: 'award',
                title: 'New Pledge',
                description: 'Describe your quality pledge here...'
            });
            this.$nextTick(() => lucide.createIcons());
        },

        removeFeature(index) {
            window.confirmAction('Remove Pledge?', 'This feature will be removed.', () => {
                this.content.features.splice(index, 1);
            });
        },

        addStep() {
            const nextNum = this.content.process.steps.length + 1;
            this.content.process.steps.push({
                number: nextNum,
                title: 'Next Step',
                description: 'Step description...',
                image: ''
            });
            this.$nextTick(() => lucide.createIcons());
        },

        removeStep(index) {
            window.confirmAction('Delete Step?', 'This process step will be removed.', () => {
                this.content.process.steps.splice(index, 1);
                // Re-number steps
                this.content.process.steps.forEach((s, i) => s.number = i + 1);
            });
        },

        openFeatureIconPicker(index) {
            this.currentFeatureIndex = index;
            this.showIconPicker = true;
            this.$nextTick(() => lucide.createIcons());
        },

        selectFeatureIcon(icon) {
            this.content.features[this.currentFeatureIndex].icon = icon;
            this.showIconPicker = false;
            this.$nextTick(() => lucide.createIcons());
        },

        addCertification() {
            this.content.certifications.list.push({
                id: 'new',
                name: 'New Certification',
                desc: 'Short Description'
            });
            this.$nextTick(() => lucide.createIcons());
        },

        removeCertification(index) {
            window.confirmAction('Delete Certification?', 'This certification will be removed.', () => {
                this.content.certifications.list.splice(index, 1);
            });
        },

        resetChanges() {
            window.confirmAction('Discard Changes?', 'You will lose all unsaved progress.', () => {
                this.content = JSON.parse(JSON.stringify(this.originalContent));
                this.hasUnsavedChanges = false;
                for (let path in this.editors) {
                    this.editors[path].setData(this.getDeepValue(this.content, path));
                }
            });
        },

        getDeepValue(obj, path) {
            return path.split('.').reduce((prev, curr) => prev ? prev[curr] : null, obj);
        },

        async save() {
            this.isSaving = true;
            try {
                const response = await axios.post('{{ route("admin.about.update") }}', {
                    content: this.content,
                    status: this.status
                });
                if (response.data.success) {
                    this.originalContent = JSON.parse(JSON.stringify(this.content));
                    this.hasUnsavedChanges = false;
                    window.toast(response.data.message);
                }
            } catch (e) { window.toast('Save failed', 'error'); }
            finally { this.isSaving = false; }
        }
     }">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">About Page CMS</h1>
            <p class="text-slate-500 text-sm mt-1 tracking-tight">Manage all brand story sections in one place.</p>
        </div>
        <div class="flex items-center gap-4">
            <!-- Status Toggle -->
            <div class="hidden md:flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shadow-inner">
                <button @click="status = 'draft'; hasUnsavedChanges = true" 
                        :class="status === 'draft' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-400 hover:text-slate-600'" 
                        class="px-4 py-1.5 text-[10px] font-black uppercase tracking-[0.1em] rounded-lg transition-all flex items-center gap-2">
                    <div class="h-1.5 w-1.5 rounded-full" :class="status === 'draft' ? 'bg-slate-400' : 'bg-slate-200'"></div>
                    Draft
                </button>
                <button @click="status = 'published'; hasUnsavedChanges = true" 
                        :class="status === 'published' ? 'bg-white text-orange-600 shadow-sm' : 'text-slate-400 hover:text-slate-600'" 
                        class="px-4 py-1.5 text-[10px] font-black uppercase tracking-[0.1em] rounded-lg transition-all flex items-center gap-2">
                    <div class="h-1.5 w-1.5 rounded-full" :class="status === 'published' ? 'bg-orange-500 animate-pulse' : 'bg-slate-200'"></div>
                    Published
                </button>
            </div>

            <div class="flex items-center gap-3">
                <button @click="showHistory = true" class="saas-btn-secondary text-xs">
                    <i data-lucide="history" class="w-4 h-4"></i>
                    History
                </button>
                <button @click="openPreview()" class="saas-btn-secondary text-xs !bg-slate-900 !text-white hover:!bg-black">
                    <i data-lucide="zap" class="w-4 h-4"></i>
                    Live Preview
                </button>
                <a href="{{ route('about') }}" target="_blank" class="saas-btn-secondary text-xs">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    View Store
                </a>
                <button @click="save()" class="saas-btn-primary text-xs uppercase tracking-widest" :disabled="isSaving">
                    <span x-show="!isSaving" class="flex items-center gap-2"><i data-lucide="save" class="w-4 h-4"></i> Save All</span>
                    <span x-show="isSaving" class="flex items-center gap-2"><i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Saving...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-3 lg:sticky lg:top-6 z-30">
            <div class="bg-white rounded-2xl border border-slate-200 p-2 shadow-sm overflow-x-auto scrollbar-hide">
                <div class="flex lg:flex-col gap-1 lg:gap-1 p-1">
                    <p class="hidden lg:block px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Section List</p>
                    @foreach([
                        ['id' => 'hero', 'label' => 'Hero Banner', 'icon' => 'layout'],
                        ['id' => 'features', 'label' => 'Quality Pledge', 'icon' => 'award'],
                        ['id' => 'vision_mission', 'label' => 'Vision & Mission', 'icon' => 'target'],
                        ['id' => 'process', 'label' => 'Standard Process', 'icon' => 'settings'],
                        ['id' => 'founders', 'label' => 'Founders', 'icon' => 'users'],
                        ['id' => 'certifications', 'label' => 'Certifications', 'icon' => 'shield-check'],
                        ['id' => 'cta', 'label' => 'Bottom CTA', 'icon' => 'megaphone'],
                        ['id' => 'seo', 'label' => 'SEO Optimization', 'icon' => 'search'],
                    ] as $s)
                    <button @click="activeTab = '{{ $s['id'] }}'" 
                            class="flex items-center justify-between px-4 py-3 rounded-xl text-xs lg:text-sm font-semibold transition-all group lg:w-full"
                            :class="activeTab === '{{ $s['id'] }}' ? 'bg-orange-50 text-orange-600 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'">
                        <div class="flex items-center gap-3">
                            <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4" :class="activeTab === '{{ $s['id'] }}' ? 'text-orange-600' : 'text-slate-400'"></i>
                            {{ $s['label'] }}
                        </div>
                        <div x-show="activeTab === '{{ $s['id'] }}'" class="hidden lg:block h-1.5 w-1.5 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.6)]"></div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-9 flex-1 min-w-0">
            <div class="bg-white rounded-2xl p-4 sm:p-8 min-h-[700px] border border-slate-200 shadow-sm relative overflow-visible">
                
                <!-- Hero Section -->
                <div x-show="activeTab === 'hero'" class="space-y-8 animate-in fade-in duration-300">
                    <header><h2 class="text-xl font-bold text-slate-900">Hero Banner</h2><p class="text-slate-500 text-sm">Update main headline and introduction.</p></header>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="space-y-1"><label class="saas-label">Tagline</label><input type="text" x-model="content.hero.tag" class="saas-input"></div>
                            <div class="space-y-1"><label class="saas-label">Headline</label><textarea x-model="content.hero.title" rows="2" class="saas-input !h-auto py-3 font-bold text-lg"></textarea></div>
                            <div class="space-y-1"><label class="saas-label">Description</label><div class="ck-editor-wrapper"><textarea x-ref="heroDescEditor" x-model="content.hero.description"></textarea></div></div>
                        </div>
                        <div class="space-y-4">
                            <label class="saas-label">Banner Image</label>
                            <div class="relative group aspect-square rounded-3xl overflow-hidden bg-slate-50 border-2 border-dashed border-slate-200 cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-all flex flex-col items-center justify-center overflow-hidden" @click="triggerUpload('hero-image')">
                                <template x-if="content.hero.image">
                                    <img :src="assetUrl(content.hero.image)" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!content.hero.image">
                                    <div class="flex flex-col items-center gap-2 text-slate-400 group-hover:text-orange-500">
                                        <i data-lucide="upload-cloud" class="w-10 h-10"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Upload Hero</span>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center gap-2 transition-all">
                                    <div class="flex items-center gap-2">
                                        <button @click.stop="triggerUpload('hero-image')" class="h-10 w-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="camera" class="w-5 h-5 text-white"></i></button>
                                        <button @click.stop="openMediaLibrary('hero', 'image')" class="h-10 w-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="image" class="w-5 h-5 text-white"></i></button>
                                    </div>
                                    <span class="text-[10px] font-black uppercase text-white tracking-widest">Update Image</span>
                                </div>
                            </div>
                            <input type="file" id="hero-image" class="hidden" @change="handleFileUpload($event, 'hero', 'image')">
                        </div>
                    </div>
                </div>

                <!-- Quality Pledge -->
                <div x-show="activeTab === 'features'" class="space-y-6 animate-in fade-in duration-300">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-900">Quality Pledge</h2>
                        <button @click="addFeature()" class="saas-btn-primary !py-2 !px-4 text-xs">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add Pledge
                        </button>
                    </div>
                    <div x-ref="featuresList" class="grid grid-cols-1 gap-4">
                        <template x-for="(f, i) in content.features" :key="i">
                            <div class="p-6 rounded-2xl border border-slate-100 bg-slate-50/50 flex items-center gap-6 relative group">
                                <div class="drag-handle absolute left-2 top-1/2 -translate-y-1/2 cursor-grab active:cursor-grabbing opacity-0 group-hover:opacity-100 transition-all p-2 text-slate-300 hover:text-orange-500 z-10">
                                    <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                                </div>
                                <button @click="removeFeature(i)" class="absolute top-2 right-2 h-6 w-6 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-rose-500 hover:text-white transition-all">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </button>
                                <div @click="openFeatureIconPicker(i)" class="h-14 w-14 shrink-0 rounded-2xl bg-white shadow-sm border border-slate-200 flex items-center justify-center cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition-all group/icon">
                                    <i :data-lucide="f.icon" class="w-6 h-6 text-orange-500 group-hover/icon:scale-110 transition-transform"></i>
                                </div>
                                <div class="flex-1 grid grid-cols-2 gap-4">
                                    <input type="text" x-model="f.title" class="saas-input font-bold" placeholder="Title">
                                    <div class="relative">
                                        <input type="text" x-model="f.icon" class="saas-input text-[10px] pr-8" placeholder="Lucide Icon Name">
                                        <button @click="openFeatureIconPicker(i)" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-orange-500">
                                            <i data-lucide="search" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                    <textarea x-model="f.description" class="saas-input col-span-2 !h-auto py-2 text-xs" rows="2"></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Vision & Mission -->
                <div x-show="activeTab === 'vision_mission'" class="space-y-8 animate-in fade-in duration-300">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <h3 class="text-xs font-black uppercase text-orange-500 tracking-widest">Our Vision</h3>
                            <input type="text" x-model="content.vision.title" class="saas-input font-bold">
                            <textarea x-model="content.vision.description" rows="5" class="saas-input !h-auto py-3 text-sm"></textarea>
                            <div class="aspect-video rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 relative group cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-all flex flex-col items-center justify-center">
                                <template x-if="content.vision.image">
                                    <img :src="assetUrl(content.vision.image)" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!content.vision.image">
                                    <div class="flex flex-col items-center gap-1 text-slate-400 group-hover:text-orange-500">
                                        <i data-lucide="image-plus" class="w-6 h-6"></i>
                                        <span class="text-[8px] font-black uppercase tracking-tighter">Upload Vision</span>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-3 transition-all">
                                    <button @click.stop="triggerUpload('v-img')" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="camera" class="w-4 h-4 text-white"></i></button>
                                    <button @click.stop="openMediaLibrary('vision', 'image')" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="image" class="w-4 h-4 text-white"></i></button>
                                </div>
                                <input type="file" id="v-img" class="hidden" @change="handleFileUpload($event, 'vision', 'image')">
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-xs font-black uppercase text-slate-900 tracking-widest">Our Mission</h3>
                            <input type="text" x-model="content.mission.title" class="saas-input font-bold">
                            <textarea x-model="content.mission.description" rows="5" class="saas-input !h-auto py-3 text-sm"></textarea>
                            <div class="aspect-video rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 relative group cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-all flex flex-col items-center justify-center">
                                <template x-if="content.mission.image">
                                    <img :src="assetUrl(content.mission.image)" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!content.mission.image">
                                    <div class="flex flex-col items-center gap-1 text-slate-400 group-hover:text-orange-500">
                                        <i data-lucide="image-plus" class="w-6 h-6"></i>
                                        <span class="text-[8px] font-black uppercase tracking-tighter">Upload Mission</span>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-3 transition-all">
                                    <button @click.stop="triggerUpload('m-img')" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="camera" class="w-4 h-4 text-white"></i></button>
                                    <button @click.stop="openMediaLibrary('mission', 'image')" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="image" class="w-4 h-4 text-white"></i></button>
                                </div>
                                <input type="file" id="m-img" class="hidden" @change="handleFileUpload($event, 'mission', 'image')">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Standard Process -->
                <div x-show="activeTab === 'process'" class="space-y-6 animate-in fade-in duration-300">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-900">Standard Process</h2>
                        <button @click="addStep()" class="saas-btn-primary !py-2 !px-4 text-xs">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add Step
                        </button>
                    </div>
                    <div x-ref="processList" class="space-y-4">
                        <template x-for="(step, i) in content.process.steps" :key="i">
                            <div class="flex flex-col sm:flex-row gap-6 p-6 rounded-2xl border border-slate-100 hover:border-orange-200 transition-all bg-slate-50/30 group relative">
                                <div class="drag-handle absolute left-2 top-4 cursor-grab active:cursor-grabbing opacity-0 group-hover:opacity-100 transition-all p-2 text-slate-300 hover:text-orange-500 z-10">
                                    <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                                </div>
                                <button @click="removeStep(i)" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-rose-500 hover:text-white transition-all z-10">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                                <div class="flex sm:flex-col gap-4 sm:w-24 shrink-0">
                                    <div class="h-12 w-12 sm:h-16 sm:w-16 rounded-2xl bg-orange-500 text-white flex items-center justify-center text-xl font-black shadow-lg shadow-orange-200" x-text="step.number"></div>
                                    <div class="flex-1 space-y-1">
                                        <label class="text-[9px] font-black uppercase text-slate-400">Step #</label>
                                        <input type="number" x-model="step.number" class="saas-input !py-1 text-center font-bold">
                                    </div>
                                </div>
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black uppercase text-slate-400">Title & Details</label>
                                            <input type="text" x-model="step.title" class="saas-input font-bold" placeholder="Step Title">
                                        </div>
                                        <textarea x-model="step.description" rows="3" class="saas-input !h-auto py-2 text-xs" placeholder="Description"></textarea>
                                    </div>
                                    <div class="aspect-video rounded-xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 relative group cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-all flex flex-col items-center justify-center" @click="triggerUpload('p-img-'+i)">
                                         <template x-if="step.image">
                                             <img :src="assetUrl(step.image)" class="w-full h-full object-cover">
                                         </template>
                                         <template x-if="!step.image">
                                             <div class="flex flex-col items-center gap-1 text-slate-400 group-hover:text-orange-500">
                                                 <i data-lucide="image-plus" class="w-5 h-5"></i>
                                                 <span class="text-[8px] font-black uppercase tracking-widest">Upload</span>
                                             </div>
                                         </template>
                                         <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition-all">
                                            <button @click.stop="triggerUpload('p-img-'+i)" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="camera" class="w-4 h-4 text-white"></i></button>
                                            <button @click.stop="openMediaLibrary('process.steps.'+i, 'image')" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="image" class="w-4 h-4 text-white"></i></button>
                                         </div>
                                     </div>
                                    <input type="file" :id="'p-img-'+i" class="hidden" @change="handleFileUpload($event, 'process.steps.'+i, 'image')">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Founders -->
                <div x-show="activeTab === 'founders'" class="space-y-6 animate-in fade-in duration-300">
                    <div class="flex items-center justify-between"><h2 class="text-xl font-bold text-slate-900">Founders Profile</h2><button @click="addFounder()" class="saas-btn-primary !py-2 !px-4 text-xs"><i data-lucide="plus" class="w-4 h-4"></i> Add Founder</button></div>
                    <div x-ref="foundersList" class="space-y-6">
                        <template x-for="(founder, i) in content.founders.list" :key="i">
                            <div class="p-8 rounded-2xl border border-slate-200 bg-slate-50/30 relative group">
                                <div class="drag-handle absolute left-2 top-1/2 -translate-y-1/2 cursor-grab active:cursor-grabbing opacity-0 group-hover:opacity-100 transition-all p-2 text-slate-300 hover:text-orange-500 z-10">
                                    <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                                </div>
                                <button @click="removeFounder(i)" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <div class="flex flex-col lg:flex-row gap-8">
                                    <div class="w-full lg:w-40 shrink-0 space-y-4">
                                        <div class="aspect-square w-full max-w-[200px] mx-auto lg:max-w-none rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 relative group cursor-pointer hover:border-orange-400 hover:bg-orange-50/30 transition-all flex flex-col items-center justify-center" @click="triggerUpload('f-img-'+i)">
                                            <template x-if="founder.image">
                                                <img :src="assetUrl(founder.image)" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!founder.image">
                                                <div class="flex flex-col items-center gap-1 text-slate-400 group-hover:text-orange-500">
                                                    <i data-lucide="user-plus" class="w-6 h-6"></i>
                                                    <span class="text-[8px] font-black uppercase tracking-widest">Upload Profile</span>
                                                </div>
                                            </template>
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition-all">
                                                <button @click.stop="triggerUpload('f-img-'+i)" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="camera" class="w-4 h-4 text-white"></i></button>
                                                <button @click.stop="openMediaLibrary('founders.list.'+i, 'image')" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="image" class="w-4 h-4 text-white"></i></button>
                                            </div>
                                        </div>
                                        <input type="file" :id="'f-img-'+i" class="hidden" @change="handleFileUpload($event, 'founders.list.'+i, 'image')">
                                        <div class="flex items-center justify-between p-2 bg-white rounded-xl border border-slate-100"><span class="text-[9px] font-black uppercase text-slate-400">Reverse</span><input type="checkbox" x-model="founder.reverse" class="rounded border-slate-300 text-orange-500"></div>
                                    </div>
                                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="space-y-1 sm:col-span-1">
                                            <label class="text-[9px] font-black uppercase text-slate-400 px-1">Name</label>
                                            <input type="text" x-model="founder.name" class="saas-input font-bold" placeholder="Name">
                                        </div>
                                        <div class="space-y-1 sm:col-span-1">
                                            <label class="text-[9px] font-black uppercase text-slate-400 px-1">Credentials</label>
                                            <input type="text" x-model="founder.degree" class="saas-input font-bold" placeholder="Credentials">
                                        </div>
                                        <div class="space-y-1 col-span-1 sm:col-span-2">
                                            <label class="text-[9px] font-black uppercase text-slate-400 px-1">Role</label>
                                            <input type="text" x-model="founder.role" class="saas-input font-bold" placeholder="Role">
                                        </div>
                                        <div class="space-y-1 col-span-1 sm:col-span-2">
                                            <label class="text-[9px] font-black uppercase text-slate-400 px-1">Biography</label>
                                            <textarea x-model="founder.bio" rows="5" class="saas-input !h-auto py-3 text-sm leading-relaxed" placeholder="Biography"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Certifications -->
                <div x-show="activeTab === 'certifications'" class="space-y-6 animate-in fade-in duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Certifications</h2>
                            <p class="text-slate-500 text-sm">Trust marks and industry recognition.</p>
                        </div>
                        <button @click="addCertification()" class="saas-btn-primary !py-2 !px-4 text-xs">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add More
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <template x-for="(cert, i) in content.certifications.list" :key="i">
                            <div class="group relative bg-white rounded-2xl border border-slate-200 p-6 transition-all hover:shadow-xl hover:border-orange-200">
                                <button @click="removeCertification(i)" 
                                        class="absolute top-3 right-3 h-7 w-7 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-rose-500 hover:text-white transition-all z-10">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                </button>
                                
                                <div class="flex flex-col items-center text-center space-y-4">
                                    <div class="relative">
                                        <div class="h-20 w-20 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center group-hover:border-orange-400 group-hover:bg-orange-50 transition-all cursor-pointer relative overflow-hidden group/img mx-auto" @click="triggerUpload('cert-img-' + i)">
                                            <template x-if="cert.image">
                                                <img :src="cert.image ? assetUrl(cert.image) : '/images/icons/' + cert.id + '.png'" 
                                                     x-on:error="$el.src='https://ui-avatars.com/api/?name=' + cert.name + '&background=f1f5f9&color=94a3b8'"
                                                     class="w-full h-full object-contain">
                                            </template>
                                            <template x-if="!cert.image && !cert.id">
                                                <i data-lucide="image-plus" class="w-6 h-6 text-slate-300 group-hover:text-orange-500"></i>
                                            </template>
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-all">
                                                <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                                            </div>
                                        </div>
                                        <input type="file" :id="'cert-img-' + i" class="hidden" @change="handleFileUpload($event, 'certifications.list.' + i, 'image')">
                                        
                                        <div class="mt-4 space-y-2 w-full">
                                            <div class="space-y-1">
                                                <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Icon ID / Key</label>
                                                <input type="text" x-model="cert.id" class="w-full text-center bg-slate-50 border-none rounded-lg py-1 text-[10px] font-mono focus:ring-1 focus:ring-orange-500" placeholder="e.g. fssai">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full space-y-3">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Title</label>
                                            <input type="text" x-model="cert.name" class="w-full text-center bg-transparent border-none p-0 focus:ring-0 text-sm font-bold text-slate-900" placeholder="Name">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-black uppercase text-slate-400 tracking-wider">Subtitle</label>
                                            <textarea x-model="cert.desc" rows="2" class="w-full text-center bg-transparent border-none p-0 focus:ring-0 text-xs text-slate-500 leading-relaxed resize-none" placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- CTA Section -->
                <div x-show="activeTab === 'cta'" class="space-y-6 animate-in fade-in duration-300">
                    <h2 class="text-xl font-bold text-slate-900">Bottom CTA</h2>
                    <div class="bg-white rounded-[2.5rem] p-10 text-slate-900 border border-slate-200 relative overflow-hidden shadow-sm">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center relative z-10">
                            <div class="space-y-6">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase text-slate-400">Headline</label>
                                    <input type="text" x-model="content.cta.title" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-slate-900 font-bold outline-none focus:border-orange-500 transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase text-slate-400">Description</label>
                                    <textarea x-model="content.cta.description" rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-slate-900 text-sm outline-none focus:border-orange-500 transition-all"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black uppercase text-slate-400">WhatsApp</label>
                                        <input type="text" x-model="content.cta.whatsapp" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-slate-900 text-xs outline-none focus:border-orange-500 transition-all" placeholder="WhatsApp">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black uppercase text-slate-400">Shop URL</label>
                                        <input type="text" x-model="content.cta.shop_url" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-slate-900 text-xs outline-none focus:border-orange-500 transition-all" placeholder="Shop URL">
                                    </div>
                                </div>
                            </div>
                            <div class="aspect-square bg-slate-50 rounded-3xl flex flex-col items-center justify-center relative group cursor-pointer border-2 border-dashed border-slate-200 hover:border-orange-400 hover:bg-orange-50/30 transition-all" @click="triggerUpload('c-img')">
                                <template x-if="content.cta.image">
                                    <img :src="assetUrl(content.cta.image)" class="h-40 w-auto object-contain transition-all duration-700 group-hover:scale-110">
                                </template>
                                <template x-if="!content.cta.image">
                                    <div class="flex flex-col items-center gap-2 text-slate-400 group-hover:text-orange-500">
                                        <i data-lucide="image-plus" class="w-8 h-8"></i>
                                        <span class="text-[9px] font-black uppercase tracking-widest">Upload Image</span>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center gap-1 transition-all rounded-3xl">
                                    <div class="flex items-center gap-2">
                                        <button @click.stop="triggerUpload('c-img')" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="camera" class="w-4 h-4 text-white"></i></button>
                                        <button @click.stop="openMediaLibrary('cta', 'image')" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all"><i data-lucide="image" class="w-4 h-4 text-white"></i></button>
                                    </div>
                                    <span class="text-[8px] font-black uppercase text-white tracking-widest">Update</span>
                                </div>
                                <input type="file" id="c-img" class="hidden" @change="handleFileUpload($event, 'cta', 'image')">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div x-show="activeTab === 'seo'" class="space-y-8 animate-in fade-in duration-300">
                    <header>
                        <h2 class="text-xl font-bold text-slate-900">SEO Optimization</h2>
                        <p class="text-slate-500 text-sm">Configure meta tags for better search engine visibility.</p>
                    </header>
                    
                    <div class="space-y-6">
                        <div class="space-y-1">
                            <label class="saas-label">Meta Title</label>
                            <input type="text" x-model="content.seo.title" class="saas-input" placeholder="Browser tab title">
                        </div>
                        <div class="space-y-1">
                            <label class="saas-label">Meta Description</label>
                            <textarea x-model="content.seo.description" rows="5" class="saas-input !h-auto py-3 text-sm" placeholder="Brief summary for search results"></textarea>
                        </div>
                        
                        <!-- Preview Card -->
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 mt-12">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Search Result Preview</h4>
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                                <h5 class="text-[#1a0dab] text-xl font-medium hover:underline cursor-pointer" x-text="content.seo.title || 'Remenant Health | Premium Bioavailable Nutrition'"></h5>
                                <p class="text-[#006621] text-sm py-1">https://remenanthealth.com/about</p>
                                <p class="text-[#545454] text-sm leading-snug" x-text="content.seo.description || 'Description will appear here when you type...'"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Footer -->
    <div class="fixed bottom-6 left-0 right-0 z-50 pointer-events-none transition-all duration-500" :class="hasUnsavedChanges ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'">
        <div class="max-w-xl mx-auto px-6 pointer-events-auto">
            <div class="bg-slate-900 text-white rounded-2xl p-4 flex items-center justify-between shadow-2xl">
                <div class="flex items-center gap-3 px-2"><div class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></div><span class="text-xs font-bold uppercase tracking-widest">Unsaved Changes</span></div>
                <div class="flex items-center gap-2"><button @click="resetChanges()" class="px-4 py-2 text-xs font-bold text-slate-400 hover:text-white transition-colors">Discard</button><button @click="save()" class="px-6 py-2 bg-orange-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-orange-600 transition-all">Save Changes</button></div>
            </div>
    <!-- Icon Picker Modal -->
    <template x-teleport="body">
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
                        <p class="text-xs text-slate-500 mt-1">Select a visual for this quality pledge</p>
                    </div>
                    <button @click="showIconPicker = false" class="h-10 w-10 rounded-full hover:bg-white flex items-center justify-center text-slate-400 transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto p-8">
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
                                    @click="selectFeatureIcon(icon)"
                                    class="aspect-square rounded-2xl border-2 border-slate-50 flex flex-col items-center justify-center gap-2 hover:border-orange-500 hover:bg-orange-50 transition-all group">
                                <i :data-lucide="icon" class="w-6 h-6 text-slate-400 group-hover:text-orange-500"></i>
                                <span class="text-[8px] font-black uppercase text-slate-300 group-hover:text-orange-400" x-text="icon"></span>
                            </button>
                        </template>
                        
                        <!-- Custom Icon Option -->
                        <div x-show="iconSearch && !filteredIcons.includes(iconSearch)" class="col-span-full mt-4">
                            <button type="button" 
                                    @click="selectFeatureIcon(iconSearch)"
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
    </template>

    <!-- Media Library Modal -->
    <template x-teleport="body">
        <div x-show="showMediaLibrary" 
             x-cloak         x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[120] bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-6" 
             @click.self="showMediaLibrary = false">
            <div class="bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Media Library</h3>
                        <p class="text-xs text-slate-500 mt-1">Select an existing image from your uploads</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="openMediaLibrary(mediaTargetPath, mediaTargetKey)" class="h-10 w-10 rounded-full hover:bg-white flex items-center justify-center text-slate-400 transition-all"><i data-lucide="refresh-cw" class="w-4 h-4" :class="isLoadingMedia ? 'animate-spin' : ''"></i></button>
                        <button @click="showMediaLibrary = false" class="h-10 w-10 rounded-full hover:bg-white flex items-center justify-center text-slate-400 transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
                    </div>
                </div>
                
                <div class="flex-1 overflow-y-auto p-8">
                    <div x-show="isLoadingMedia" class="flex flex-col items-center justify-center py-20 space-y-4">
                        <div class="h-12 w-12 border-4 border-orange-500/20 border-t-orange-500 rounded-full animate-spin"></div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Loading Media...</p>
                    </div>

                    <div x-show="!isLoadingMedia && mediaFiles.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="h-20 w-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-4">
                            <i data-lucide="image-off" class="w-10 h-10 text-slate-200"></i>
                        </div>
                        <h4 class="text-slate-900 font-bold">No Media Found</h4>
                        <p class="text-xs text-slate-500 mt-1">Upload some images first to see them here.</p>
                    </div>

                    <div x-show="!isLoadingMedia && mediaFiles.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        <template x-for="file in mediaFiles" :key="file.path">
                            <div class="group relative aspect-square rounded-2xl overflow-hidden border-2 border-slate-50 hover:border-orange-500 transition-all cursor-pointer bg-slate-50"
                                 @click="selectMedia(file.path)">
                                <img :src="file.url" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                    <i data-lucide="check-circle" class="w-8 h-8 text-white"></i>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/60 to-transparent">
                                    <p class="text-[8px] text-white font-bold truncate" x-text="file.name"></p>
                                    <p class="text-[7px] text-white/60" x-text="file.size"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest" x-text="mediaFiles.length + ' Images Available'"></p>
                    <button @click="triggerUpload('library-upload')" class="saas-btn-primary !py-2 !px-4 text-[10px] uppercase tracking-widest">
                        <i data-lucide="upload" class="w-3 h-3"></i> Upload New
                    </button>
                    <input type="file" id="library-upload" class="hidden" @change="handleFileUpload($event, mediaTargetPath, mediaTargetKey); showMediaLibrary = false;">
                </div>
            </div>
        </div>
    </template>

    <!-- Global Preview Engine -->
    <x-admin.preview-modal :route="route('admin.about.preview')" preview-url="remenant.com/about" />

    <!-- Version History Modal -->
    <template x-teleport="body">
        <div x-show="showHistory" 
             x-cloak         x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[120] bg-slate-950/60 backdrop-blur-md flex items-center justify-center p-6" 
             @click.self="showHistory = false">
            <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Version History</h3>
                        <p class="text-xs text-slate-500 mt-1">Rollback to a previous state of this page</p>
                    </div>
                    <button @click="showHistory = false" class="h-10 w-10 rounded-full hover:bg-white flex items-center justify-center text-slate-400 transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    <template x-for="version in versions" :key="version.id">
                        <div class="p-6 rounded-3xl border border-slate-100 hover:border-orange-200 hover:bg-orange-50/30 transition-all group flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-orange-500 group-hover:border-orange-200 transition-all shadow-sm">
                                    <i data-lucide="history" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-black text-slate-900" x-text="new Date(version.created_at).toLocaleString()"></p>
                                        <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-slate-100 text-slate-500" x-text="version.status"></span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5" x-text="'Modified by ' + (version.user ? version.user.name : 'Unknown')"></p>
                                    <p class="text-[10px] text-orange-500 font-black mt-1" x-text="version.version_note || 'Manual Save'"></p>
                                </div>
                            </div>
                            <button @click="restoreVersion(version.id)" 
                                    :disabled="isRestoring"
                                    class="saas-btn-secondary !py-2 !px-4 text-[10px] uppercase tracking-widest hover:!bg-orange-500 hover:!text-white transition-all">
                                <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Restore
                            </button>
                        </div>
                    </template>
                    
                    <div x-show="versions.length === 0" class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="h-20 w-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-4">
                            <i data-lucide="history" class="w-10 h-10 text-slate-200"></i>
                        </div>
                        <h4 class="text-slate-900 font-bold">No History Available</h4>
                        <p class="text-xs text-slate-500 mt-1">Versions will appear here as you save changes.</p>
                    </div>
                </div>
                
                <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-center">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Showing last 10 versions</p>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    .ck-editor__editable { min-height: 200px; border-radius: 0 0 12px 12px !important; border-color: #E2E8F0 !important; }
    .ck-toolbar { border-radius: 12px 12px 0 0 !important; background: #F8FAFC !important; border-color: #E2E8F0 !important; }
</style>
@endsection
