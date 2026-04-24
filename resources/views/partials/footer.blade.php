<!-- Footer -->
<footer class="bg-slate-50 w-full py-8 md:py-10 border-t border-black/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">

            <!-- Sol: Üniversite Bilgisi -->
            <div class="space-y-4 text-center lg:text-left">
                <a href="{{ route('home') }}"
                    class="font-headline font-bold text-primary text-xl md:text-2xl block hover:opacity-80 transition-opacity uppercase tracking-tighter">
                    {{ __('site.footer_university') }}
                </a>
                <p class="font-body text-sm text-on-surface-variant leading-relaxed opacity-90 max-w-md mx-auto lg:mx-0">
                    {{ __('site.footer_desc') }}
                </p>
                <p class="font-body text-xs text-on-surface-variant/80 font-bold">
                    © {{ date('Y') }} {{ \App\Models\SiteSetting::getVal('site_name', __('site.footer_university')) }}. {{ __('site.all_rights') }}
                </p>
            </div>

            <!-- Orta: Hızlı Bağlantılar -->
            <div class="space-y-3 text-center lg:text-left lg:pl-24">
                <h4 class="text-primary font-headline font-extrabold text-base uppercase tracking-widest border-b-2 border-primary/10 pb-2 inline-block">
                    {{ __('site.quick_links') }}
                </h4>
                @php
                    $footerMenus = \App\Models\Menu::where('show_in_footer', true)->active()->get();
                @endphp
                <ul class="space-y-2 font-body text-sm text-on-surface-variant font-bold pt-2">
                    @forelse($footerMenus as $fMenu)
                        <li><a class="hover:text-primary transition-colors" href="{{ $fMenu->url }}" target="{{ $fMenu->target }}">{{ $fMenu->label }}</a></li>
                    @empty
                        <li><a class="hover:text-primary transition-colors" href="{{ route('home') }}">{{ __('site.home') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('etkinlikler') }}">{{ __('site.events_page_title') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('duyurular') }}">{{ __('site.announcements_page_title') }}</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('kulupler') }}">{{ __('site.clubs_page_title') }}</a></li>
                    @endforelse
                </ul>
            </div>

            <!-- Sağ: Bizi Takip Edin -->
            <div class="space-y-4 text-center lg:text-left lg:items-start flex flex-col">
                <h4 class="text-primary font-headline font-extrabold text-base uppercase tracking-widest border-b-2 border-primary/10 pb-2 inline-block">
                    {{ __('site.follow_us') }}
                </h4>
                @php
                    $instagram = \App\Models\SiteSetting::getVal('social_instagram');
                    $twitter   = \App\Models\SiteSetting::getVal('social_twitter');
                    $facebook  = \App\Models\SiteSetting::getVal('social_facebook');
                    $email     = \App\Models\SiteSetting::getVal('contact_email');
                    $phone     = \App\Models\SiteSetting::getVal('contact_phone');
                @endphp
                <div class="flex gap-3 justify-center lg:justify-start">
                    @if($instagram)
                        <a class="w-10 h-10 border border-black/5 rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all shadow-sm"
                            href="https://instagram.com/{{ $instagram }}" target="_blank">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.332 3.608 1.308.975.975 1.246 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.332 2.633-1.308 3.608-.975.975-2.242 1.246-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.332-3.608-1.308-.975-.975-1.246-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.332-2.633 1.308-3.608.975-.975 2.242-1.246 3.608-1.308 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-1.277.059-2.148.262-2.911.558-.788.306-1.457.715-2.123 1.381s-1.075 1.335-1.381 2.123c-.296.763-.499 1.634-.558 2.911-.058 1.28-.072 1.688-.072 4.947s.014 3.667.072 4.947c.059 1.277.262 2.148.558 2.911.306.788.715 1.457 1.381 2.123s1.335 1.075 2.123 1.381c.763.296 1.634.499 2.911.558 1.28.058 1.688.072 4.947.072s3.667-.014 4.947-.072c1.277-.059 2.148-.262 2.911-.558.788-.306 1.457-.715 2.123-1.381s1.075-1.335 1.381-2.123c.296-.763.499-1.634.558-2.911.058-1.28.072-1.688.072-4.947s-.014-3.667-.072-4.947c-.059-1.277-.262-2.148-.558-2.911-.306-.788-.715-1.457-1.381-2.123s-1.335-1.075-2.123-1.381c-.763-.296-1.634-.499-2.911-.558-1.28-.058-1.688-.072-4.947-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    @endif
                    @if($twitter)
                        <a class="w-10 h-10 border border-black/5 rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all shadow-sm"
                            href="https://twitter.com/{{ $twitter }}" target="_blank">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    @endif
                    @if($facebook)
                        <a class="w-10 h-10 border border-black/5 rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all shadow-sm"
                            href="{{ str_contains($facebook, 'http') ? $facebook : 'https://facebook.com/' . $facebook }}" target="_blank">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.791-4.667 4.53-4.667 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif
                </div>
                @if($email || $phone)
                    <div class="space-y-2 mt-2">
                        @if($email)
                            <a href="mailto:{{ $email }}" class="flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors font-bold justify-center lg:justify-start">
                                <span class="material-symbols-outlined text-[18px]">mail</span> {{ $email }}
                            </a>
                        @endif
                        @if($phone)
                            <a href="tel:{{ $phone }}" class="flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors font-bold justify-center lg:justify-start">
                                <span class="material-symbols-outlined text-[18px]">call</span> {{ $phone }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
</footer>
