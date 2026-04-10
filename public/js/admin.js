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
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 z-[9999] min-w-[320px] bg-white rounded-2xl shadow-2xl border-l-4 p-4 flex items-center gap-4 animate-fade-in-right transition-all duration-300 transform translate-x-0`;
        
        const colors = {
            success: 'border-green-500 text-green-700',
            error: 'border-red-500 text-red-700',
            info: 'border-blue-500 text-blue-700',
            warning: 'border-amber-500 text-amber-700'
        };

        const icons = {
            success: 'check_circle',
            error: 'error',
            info: 'info',
            warning: 'warning'
        };

        toast.className += ` ${colors[type] || colors.success}`;

        // Create icon element
        const iconDiv = document.createElement('div');
        iconDiv.className = 'w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center shrink-0';
        iconDiv.innerHTML = `<span class="material-symbols-outlined text-[24px]">${icons[type] || 'check_circle'}</span>`;
        
        // Create message element (SAFE: textContent)
        const msgSpan = document.createElement('span');
        msgSpan.className = 'text-sm font-bold flex-1';
        msgSpan.textContent = message;

        // Close button (SAFE: static HTML)
        const closeBtn = document.createElement('button');
        closeBtn.className = 'text-slate-400 hover:text-slate-600 transition-colors';
        closeBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">close</span>';
        closeBtn.onclick = () => {
            toast.classList.replace('translate-x-0', 'translate-x-[120%]');
            setTimeout(() => toast.remove(), 300);
        };

        toast.appendChild(iconDiv);
        toast.appendChild(msgSpan);
        toast.appendChild(closeBtn);

        document.body.appendChild(toast);

        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.replace('translate-x-0', 'translate-x-[120%]');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    };

    // ── INIT ────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        initSidebarToggle();
        animateCounters();
        initTableActions();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    window.toggleStatus = function (type, id) {
        $.ajax({
            url: '/admin/toggle-status',
            type: 'POST',
            data: {
                type: type,
                id: id
            },
            success: function (response) {
                if (response.success) {
                    showToast('Durum başarıyla güncellendi.', 'success');
                    // Reload DataTables if exist
                    if (typeof $.fn.DataTable !== 'undefined') {
                        var tables = $.fn.dataTable.tables(true);
                        $(tables).DataTable().ajax.reload(null, false);
                    }
                } else {
                    showToast('Bir hata oluştu.', 'error');
                }
            },
            error: function () {
                showToast('Sunucu hatası.', 'error');
            }
        });
    }
})();
