<div class="bg-slate-50/50 backdrop-blur-md border-b border-black/5 py-3">
    <div class="max-w-7xl mx-auto px-8 flex items-center justify-between">
        <div class="flex items-center gap-2 text-slate-400 text-xs font-bold tracking-widest uppercase">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Ana Sayfa</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary">@yield('page-title', 'Sayfa')</span>
        </div>
        <h1 class="text-sm font-bold font-headline text-slate-800 tracking-tight uppercase">
            @yield('page-title')
        </h1>
    </div>
</div>
