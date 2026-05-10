<footer class="mt-20 py-8 border-t border-slate-100">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'Remenant Health') }} Control Center
        </p>
        <div class="flex items-center gap-6">
            <a href="#" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-orange-500 transition-colors">Documentation</a>
            <a href="#" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-orange-500 transition-colors">Support</a>
            <div class="h-4 w-px bg-slate-100"></div>
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-500 flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                System Operational
            </span>
        </div>
    </div>
</footer>
