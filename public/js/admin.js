// ============================================================
// FIRAT ÜNİVERSİTESİ - Admin Panel JavaScript (Optimized for Laravel)
// Optimized: Sidebar and Header are now handled by Laravel Blade
// ============================================================
(function () {
    'use strict';

    // ── SIDEBAR TOGGLE (Mobile) ──────────────────────────────
    function initSidebarToggle() {
        var toggle = document.getElementById('sidebar-toggle');
        var sidebar = document.getElementById('admin-sidebar');
        var overlay = document.getElementById('sidebar-overlay');
        
        if (toggle && sidebar) {
            toggle.addEventListener('click', function () {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('-translate-x-full');
                if (overlay) overlay.classList.toggle('hidden');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', function () {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.add('hidden');
                overlay.classList.add('hidden');
            });
        }
    }

    // ── DELETE CONFIRMATION MODAL ───────────────────────────
    window.showDeleteModal = function (itemName) {
        var m = document.getElementById('delete-modal');
        if (!m) return;
        var nameEl = document.getElementById('delete-item-name');
        if (nameEl) nameEl.textContent = itemName;
        m.classList.remove('hidden');
    };
    window.hideDeleteModal = function () {
        var m = document.getElementById('delete-modal');
        if (m) m.classList.add('hidden');
    };

    // ── EVENT MODAL ─────────────────────────────────────────
    window.showEventModal = function () {
        var m = document.getElementById('event-modal');
        if (!m) return;
        m.classList.remove('hidden');
        var overlay = document.getElementById('event-modal-overlay');
        var content = document.getElementById('event-modal-content');
        
        requestAnimationFrame(function() {
            if(overlay) overlay.classList.remove('opacity-0');
            if(content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        });
    };
    
    window.hideEventModal = function () {
        var m = document.getElementById('event-modal');
        if (!m) return;
        var overlay = document.getElementById('event-modal-overlay');
        var content = document.getElementById('event-modal-content');
        
        if(overlay) overlay.classList.add('opacity-0');
        if(content) {
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
        }
        
        setTimeout(function() {
            m.classList.add('hidden');
        }, 300); // Wait for transition
    };

    // ── STATS COUNTER ANIMATION ─────────────────────────────
    function animateCounters() {
        document.querySelectorAll('[data-count]').forEach(function (el) {
            var target = parseInt(el.dataset.count, 10);
            var duration = 1500;
            var start = 0;
            var startTime = null;
            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(eased * target).toLocaleString('tr-TR');
                if (progress < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        });
    }

    // ── TABLE ROW ACTIONS ───────────────────────────────────
    function initTableActions() {
        var selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                document.querySelectorAll('.row-checkbox').forEach(function (cb) {
                    cb.checked = selectAll.checked;
                });
            });
        }
    }

    // ── TOAST NOTIFICATIONS ─────────────────────────────────
    window.showToast = function (message, type) {
        type = type || 'success';
        var container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-4 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(container);
        }

        var icons = {
            'success': 'check_circle',
            'info': 'info',
            'warning': 'warning',
            'error': 'error'
        };
        var colors = {
            'success': 'bg-green-600',
            'info': 'bg-blue-600',
            'warning': 'bg-amber-600',
            'error': 'bg-red-600'
        };

        var toast = document.createElement('div');
        toast.className = 'flex items-center gap-3 px-5 py-3.5 rounded-2xl text-white shadow-xl transform translate-y-4 opacity-0 transition-all duration-300 ' + colors[type];
        toast.innerHTML = 
            '<span class="material-symbols-outlined text-[22px]">' + icons[type] + '</span>' +
            '<span class="text-sm font-bold">' + message + '</span>' +
            '<button onclick="this.parentElement.remove()" class="ml-2 opacity-70 hover:opacity-100 transition-opacity"><span class="material-symbols-outlined text-[18px]">close</span></button>';
        
        container.appendChild(toast);

        requestAnimationFrame(function() {
            toast.classList.remove('translate-y-4', 'opacity-0');
        });

        setTimeout(function () {
            toast.classList.add('opacity-0', 'translate-y-4');
            setTimeout(function () {
                if (toast.parentElement) toast.remove();
            }, 300);
        }, 3000);
    };

    // ── INIT ────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        initSidebarToggle();
        animateCounters();
        initTableActions();
    });
})();
