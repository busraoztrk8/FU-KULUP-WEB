// ============================================================
// FIRAT ÜNİVERSİTESİ - Main JavaScript
// Behavioral logic (mobile nav, scroll effects, tabs, filters)
// ============================================================
(function () {
    'use strict';

    function getPage() {
        return document.body.dataset.page || 'home';
    }

    // ── NAVBAR BEHAVIOR ─────────────────────────────────────
    function initNavbarBehavior() {
        // Mobile menu toggle logic
        var btn = document.getElementById('mob-menu-btn');
        var ov = document.getElementById('mob-overlay');
        var mn = document.getElementById('mob-menu');
        var cl = document.getElementById('mob-close');

        function open() { 
            if(ov) ov.classList.add('active'); 
            if(mn) mn.classList.add('active'); 
        }
        function close() { 
            if(ov) ov.classList.remove('active'); 
            if(mn) mn.classList.remove('active'); 
        }

        if (btn) btn.onclick = open;
        if (cl) cl.onclick = close;
        if (ov) ov.onclick = close;

        // Sticky Navbar Scroll Effect
        window.addEventListener('scroll', function () {
            var nav = document.getElementById('main-nav');
            if (nav) {
                if (window.scrollY > 20) {
                    nav.classList.add('scrolled');
                    nav.style.paddingTop = '8px';
                    nav.style.paddingBottom = '8px';
                } else {
                    nav.classList.remove('scrolled');
                    nav.style.paddingTop = '';
                    nav.style.paddingBottom = '';
                }
            }
        });
    }

    // ── EVENTS TAB SWITCHING ────────────────────────────────
    function initEventsTabs() {
        var btns = document.querySelectorAll('[data-tab-btn]');
        var contents = document.querySelectorAll('[data-tab-content]');
        btns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var t = btn.dataset.tabBtn;
                btns.forEach(function (b) {
                    b.classList.remove('bg-primary', 'text-white', 'shadow-md');
                    b.classList.add('text-slate-600');
                    b.classList.remove('active');
                });
                btn.classList.add('bg-primary', 'text-white', 'shadow-md', 'active');
                btn.classList.remove('text-slate-600');
                
                contents.forEach(function (tc) {
                    tc.classList.toggle('active', tc.dataset.tabContent === t);
                });
            });
        });
    }

    // ── CLUBS FILTER ────────────────────────────────────────
    function initClubsFilter() {
        var btns = document.querySelectorAll('[data-filter]');
        var cards = document.querySelectorAll('[data-category]');
        btns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var f = btn.dataset.filter;
                btns.forEach(function (b) {
                    b.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                    b.classList.add('bg-slate-50', 'text-slate-600');
                });
                btn.classList.add('bg-primary', 'text-white', 'shadow-sm');
                btn.classList.remove('bg-slate-50', 'text-slate-600');
                cards.forEach(function (card) {
                    card.style.display = (f === 'all' || card.dataset.category === f) ? '' : 'none';
                });
            });
        });

        var si = document.getElementById('club-search');
        if (si) {
            si.addEventListener('input', function (e) {
                var q = e.target.value.toLowerCase().trim();
                cards.forEach(function (card) {
                    var n = card.querySelector('[data-club-name]');
                    if (n) card.style.display = n.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
            });
        }
    }

    // ── SCROLL ANIMATIONS ───────────────────────────────────
    function initAnimations() {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('animate-fade-in-up');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('[data-animate]').forEach(function (el) {
            el.style.opacity = '0';
            obs.observe(el);
        });
    }

    // ── HERO SLIDER ────────────────────────────────────────
    function initHeroSlider() {
        var items = document.querySelectorAll('.hero-slider-item');
        if (items.length <= 1) return;
        var current = 0;
        setInterval(function () {
            items[current].classList.remove('active');
            current = (current + 1) % items.length;
            items[current].classList.add('active');
        }, 5000);
    }

    // ── INIT ────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        initNavbarBehavior();
        initHeroSlider();
        initAnimations();

        var page = getPage();
        if (page === 'events') initEventsTabs();
        if (page === 'clubs') initClubsFilter();
    });
})();
