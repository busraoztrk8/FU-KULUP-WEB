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

        // Sticky Navbar Scroll Effect — only shadow, NO height change
        window.addEventListener('scroll', function () {
            var nav = document.getElementById('main-nav');
            if (nav) {
                if (window.scrollY > 20) {
                    nav.classList.add('scrolled');
                } else {
                    nav.classList.remove('scrolled');
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

    // ── STATS COUNTER ─────────────────────────────────────────
    function initStatsCounter() {
        var counters = document.querySelectorAll('.stat-counter');
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    var el = e.target;
                    var target = parseInt(el.dataset.target);
                    var count = 0;
                    var duration = 2000; // 2 seconds
                    var increment = target / (duration / 16); // 60fps approx
                    
                    var updateCount = function() {
                        count += increment;
                        if (count < target) {
                            el.innerText = Math.ceil(count);
                            requestAnimationFrame(updateCount);
                        } else {
                            el.innerText = target;
                        }
                    };
                    
                    updateCount();
                    obs.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(function (c) {
            obs.observe(c);
        });
    }

    // ── GALLERY SWIPER ──────────────────────────────────────
    function initGallerySwiper() {
        if (!document.querySelector('.gallery-swiper')) return;
        
        new Swiper('.gallery-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 24,
            centeredSlides: false,
            grabCursor: true,
            slidesOffsetBefore: 16,
            slidesOffsetAfter: 16,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            breakpoints: {
                320: {
                    spaceBetween: 16,
                    slidesOffsetBefore: 16,
                    slidesOffsetAfter: 16,
                },
                1024: {
                    spaceBetween: 32,
                    slidesOffsetBefore: 32,
                    slidesOffsetAfter: 32,
                }
            }
        });
    }

    // ── GALLERY LIGHTBOX ────────────────────────────────────
    function initGalleryLightbox() {
        // Collect all gallery images
        var galleryImages = document.querySelectorAll('.gallery-swiper img');
        if (!galleryImages.length) return;

        var allSrcs = [];
        var allAlts = [];
        galleryImages.forEach(function(img) {
            allSrcs.push(img.src);
            allAlts.push(img.alt || '');
        });

        var currentIndex = 0;

        // Create lightbox overlay
        var overlay = document.createElement('div');
        overlay.id = 'gallery-lightbox';
        overlay.className = 'gallery-lightbox';
        overlay.innerHTML = 
            '<div class="lightbox-backdrop"></div>' +
            '<button class="lightbox-close" aria-label="Kapat">' +
                '<span class="material-symbols-outlined">close</span>' +
            '</button>' +
            '<button class="lightbox-arrow lightbox-prev" aria-label="Önceki">' +
                '<span class="material-symbols-outlined">chevron_left</span>' +
            '</button>' +
            '<button class="lightbox-arrow lightbox-next" aria-label="Sonraki">' +
                '<span class="material-symbols-outlined">chevron_right</span>' +
            '</button>' +
            '<div class="lightbox-content">' +
                '<img class="lightbox-img" src="" alt="" />' +
                '<p class="lightbox-caption"></p>' +
                '<p class="lightbox-counter"></p>' +
            '</div>';
        document.body.appendChild(overlay);

        var lightboxImg = overlay.querySelector('.lightbox-img');
        var lightboxCaption = overlay.querySelector('.lightbox-caption');
        var lightboxCounter = overlay.querySelector('.lightbox-counter');
        var prevBtn = overlay.querySelector('.lightbox-prev');
        var nextBtn = overlay.querySelector('.lightbox-next');
        var closeBtn = overlay.querySelector('.lightbox-close');
        var backdrop = overlay.querySelector('.lightbox-backdrop');

        function showImage(index) {
            currentIndex = index;
            lightboxImg.src = allSrcs[index];
            lightboxImg.alt = allAlts[index];
            lightboxCaption.textContent = allAlts[index];
            lightboxCounter.textContent = (index + 1) + ' / ' + allSrcs.length;
            // Hide arrows if only one image
            prevBtn.style.display = allSrcs.length > 1 ? '' : 'none';
            nextBtn.style.display = allSrcs.length > 1 ? '' : 'none';
        }

        function openLightbox(index) {
            showImage(index);
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        function goNext() {
            showImage((currentIndex + 1) % allSrcs.length);
        }

        function goPrev() {
            showImage((currentIndex - 1 + allSrcs.length) % allSrcs.length);
        }

        // Attach click to each gallery image
        galleryImages.forEach(function(img, i) {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openLightbox(i);
            });
        });

        closeBtn.addEventListener('click', closeLightbox);
        backdrop.addEventListener('click', closeLightbox);
        nextBtn.addEventListener('click', function(e) { e.stopPropagation(); goNext(); });
        prevBtn.addEventListener('click', function(e) { e.stopPropagation(); goPrev(); });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!overlay.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') goNext();
            if (e.key === 'ArrowLeft') goPrev();
        });
    }
    
    // ── ACTIVE CLUBS SWIPER (HOMEPAGE) ──────────────────────
    function initActiveClubsSwiper() {
        if (!document.querySelector('.active-clubs-swiper')) return;
        
        new Swiper('.active-clubs-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            grabCursor: true,
            pagination: {
                el: '.active-clubs-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 32,
                }
            }
        });
    }
    
    // ── INIT ────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        initNavbarBehavior();
        initHeroSlider();
        initAnimations();
        initStatsCounter();
        initGallerySwiper();
        initActiveClubsSwiper();
        initGalleryLightbox();

        var page = getPage();
        if (page === 'events') initEventsTabs();
        if (page === 'clubs') initClubsFilter();
    });
})();
