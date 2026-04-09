<div class="bg-slate-50/50 backdrop-blur-md border-b border-black/5 py-2 md:py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 flex items-center justify-between">
        <div class="flex items-center gap-1.5 md:gap-2 text-slate-400 text-[10px] md:text-xs font-bold tracking-widest uppercase">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Ana Sayfa</a>
            <span class="material-symbols-outlined text-[12px] md:text-[14px]">chevron_right</span>
            <span class="text-primary truncate max-w-[150px] sm:max-w-none">{{ View::getSection('page-title', 'Sayfa') }}</span>
        </div>
        <h1 class="text-xs md:text-sm font-bold font-headline text-slate-800 tracking-tight uppercase hidden sm:block">
            {{ View::getSection('page-title') }}
        </h1>
    </div>
</div>
