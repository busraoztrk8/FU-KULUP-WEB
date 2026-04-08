@extends('layouts.admin')
@section('title', 'Menü Yönetimi')
@section('page_title', 'Menü Yönetimi')
@section('data-page', 'menu')

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">menu</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['total'] }}">0</p>
            <p class="text-sm text-slate-500">Toplam Öğe</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-blue-600">account_tree</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['main'] }}">0</p>
            <p class="text-sm text-slate-500">Ana Menü</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['active'] }}">0</p>
            <p class="text-sm text-slate-500">Aktif Öğeler</p>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-end gap-3 mb-6">
    <div class="flex items-center gap-3">
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">search</span>
            <input type="text" id="menu-search" placeholder="Menü öğelerinde ara..." 
                class="bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 w-64 focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm transition-all">
        </div>
    </div>
    <button onclick="showMenuModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Ana Menü Öğesi Ekle
    </button>
</div>

<div class="admin-card p-0 overflow-hidden shadow-sm border border-slate-200">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-left" id="menu-table">
            <thead>
                <tr class="border-b border-slate-100 bg-white">
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center w-16">#</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Menü Yapısı</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Bağlantı</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Durum</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 bg-white">
                @php $counter = 1; @endphp
                @forelse($mainMenus as $item)
                    {{-- Row for main menu --}}
                    <tr class="hover:bg-slate-50/50 transition-colors group border-b border-slate-50 menu-row" data-id="{{ $item->id }}">
                        <td class="px-6 py-4 text-center">
                            @if($item->children->count() > 0)
                                <button onclick="toggleSubmenu({{ $item->id }}, this)" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-primary/10 hover:text-primary transition-all">
                                    <span class="material-symbols-outlined text-[20px]">add</span>
                                </button>
                            @else
                                <span class="text-xs font-bold text-slate-300">{{ $counter++ }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:shadow-sm transition-all">
                                    <span class="material-symbols-outlined text-[20px]">link</span>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-[15px] search-target">{{ $item->label }}</p>
                                    <p class="text-[11px] text-slate-400 font-medium">Ana Menü Öğesi</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-500 font-medium">{{ Str::limit($item->url, 40) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" {{ $item->is_active ? 'checked' : '' }} onchange="toggleMenuStatus({{ $item->id }})">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600 shadow-inner"></div>
                                </label>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tight">{{ $item->is_active ? 'Aktif' : 'Pasif' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="showSubMenuModal({{ $item->id }}, '{{ addslashes($item->label) }}')" 
                                    class="h-9 px-3 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all active:scale-95">
                                    <span class="material-symbols-outlined text-[16px]">add</span> Alt Menü
                                </button>
                                <button onclick="showMenuDuzenle({{ $item->id }}, '{{ addslashes($item->label) }}', '{{ addslashes($item->url) }}', '{{ $item->parent_id }}', '{{ $item->target }}', {{ $item->order }}, {{ $item->is_active ? 'true' : 'false' }})" 
                                    class="w-9 h-9 bg-slate-100 hover:bg-amber-100 hover:text-amber-600 text-slate-500 rounded-xl flex items-center justify-center transition-all active:scale-95" title="Düzenle">
                                    <span class="material-symbols-outlined text-[18px]">edit_note</span>
                                </button>
                                <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="w-9 h-9 bg-slate-100 hover:bg-red-100 hover:text-red-600 text-slate-500 rounded-xl flex items-center justify-center transition-all active:scale-95" title="Sil">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Rows for sub-menus --}}
                    @foreach($item->children as $child)
                    <tr class="hidden bg-slate-50/30 transition-all border-b border-slate-50 submenu-row parent-{{ $item->id }}" data-parent="{{ $item->id }}">
                        <td class="px-6 py-3 text-center">
                            <span class="text-[10px] font-bold text-slate-300">└</span>
                        </td>
                        <td class="px-6 py-3 pl-14">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span class="text-slate-600 font-bold text-sm search-target">{{ $child->label }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <span class="text-xs text-slate-400 font-medium italic">{{ $child->url }}</span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2 opacity-80">
                                <label class="relative inline-flex items-center cursor-pointer scale-75 origin-left">
                                    <input type="checkbox" class="sr-only peer" {{ $child->is_active ? 'checked' : '' }} onchange="toggleMenuStatus({{ $child->id }})">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $child->is_active ? 'Aktif' : 'Pasif' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="showMenuDuzenle({{ $child->id }}, '{{ addslashes($child->label) }}', '{{ addslashes($child->url) }}', '{{ $child->parent_id }}', '{{ $child->target }}', {{ $child->order }}, {{ $child->is_active ? 'true' : 'false' }})" 
                                    class="w-8 h-8 text-slate-400 hover:text-amber-500 hover:bg-white hover:shadow-sm rounded-lg flex items-center justify-center transition-all" title="Düzenle">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>
                                <form action="{{ route('admin.menu.destroy', $child) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="w-8 h-8 text-slate-400 hover:text-red-500 hover:bg-white hover:shadow-sm rounded-lg flex items-center justify-center transition-all" title="Sil">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2 opacity-20">
                                <span class="material-symbols-outlined text-[48px]">menu</span>
                                <p class="text-sm font-bold">Henüz menü öğesi eklenmemiş.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-between items-center">
        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Toplam {{ $mainMenus->count() + $mainMenus->sum(fn($m) => $m->children->count()) }} öğe</span>
    </div>
</div>

{{-- Ekle/Düzenle Modal --}}
<div id="menu-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideMenuModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 id="menu-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Menü Öğesi</h3>
            <button onclick="hideMenuModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="menu-form" action="{{ route('admin.menu.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="menu-method" value="POST"/>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Etiket <span class="text-red-500">*</span></label>
                    <input id="menu-label" type="text" name="label" required placeholder="Menüde görünecek isim..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">URL <span class="text-red-500">*</span></label>
                    <input id="menu-url" type="text" name="url" required placeholder="/sayfa-adi veya https://..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Üst Menü (Opsiyonel)</label>
                    <select id="menu-parent_id" name="parent_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="">-- Ana Menü (Üst Yok) --</option>
                        @foreach($allMenus as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Hedef</label>
                    <select id="menu-target" name="target"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="_self">Aynı Sekme</option>
                        <option value="_blank">Yeni Sekme</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Sıra</label>
                    <input id="menu-order" type="number" name="order" placeholder="0" min="0"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="menu-aktif" name="is_active" value="1" checked
                        class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                    <label for="menu-aktif" class="text-sm font-semibold text-slate-600 cursor-pointer">Aktif olarak göster</label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideMenuModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Stats Counter Animation
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

    // Toggle Submenu logic
    function toggleSubmenu(parentId, btn) {
        const subrows = document.querySelectorAll(`.parent-${parentId}`);
        const icon = btn.querySelector('span');
        const isHidden = subrows[0].classList.contains('hidden');
        
        subrows.forEach(row => {
            if (isHidden) {
                row.classList.remove('hidden');
                setTimeout(() => row.style.opacity = '1', 10);
            } else {
                row.style.opacity = '0';
                setTimeout(() => row.classList.add('hidden'), 300);
            }
        });
        
        icon.textContent = isHidden ? 'remove' : 'add';
        btn.classList.toggle('bg-primary/10', isHidden);
        btn.classList.toggle('text-primary', isHidden);
    }

    // Search Filter logic
    document.getElementById('menu-search').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.menu-row');
        
        rows.forEach(row => {
            const label = row.querySelector('.search-target').textContent.toLowerCase();
            const parentId = row.dataset.id;
            const subrows = document.querySelectorAll(`.parent-${parentId}`);
            
            let childMatch = false;
            subrows.forEach(child => {
                const childLabel = child.querySelector('.search-target').textContent.toLowerCase();
                if (childLabel.includes(term)) childMatch = true;
            });

            if (label.includes(term) || childMatch) {
                row.classList.remove('hidden');
                if (childMatch && term.length > 0) {
                    subrows.forEach(c => c.classList.remove('hidden'));
                }
            } else {
                row.classList.add('hidden');
                subrows.forEach(c => c.classList.add('hidden'));
            }
        });
    });

    function showMenuModal() {
        document.getElementById('menu-modal-title').textContent = 'Yeni Menü Öğesi';
        document.getElementById('menu-form').action = '{{ route('admin.menu.store') }}';
        document.getElementById('menu-method').value = 'POST';
        document.getElementById('menu-label').value = '';
        document.getElementById('menu-url').value = '';
        document.getElementById('menu-parent_id').value = '';
        document.getElementById('menu-target').value = '_self';
        document.getElementById('menu-order').value = '';
        document.getElementById('menu-aktif').checked = true;
        document.getElementById('menu-modal').classList.remove('hidden');
    }

    function showSubMenuModal(parentId, parentLabel) {
        showMenuModal();
        document.getElementById('menu-modal-title').textContent = parentLabel + ' için Alt Menü Ekle';
        document.getElementById('menu-parent_id').value = parentId;
    }

    function showMenuDuzenle(id, label, url, parent_id, target, order, aktif) {
        document.getElementById('menu-modal-title').textContent = 'Menü Öğesini Düzenle';
        document.getElementById('menu-form').action = '/admin/menu/' + id;
        document.getElementById('menu-method').value = 'PUT';
        document.getElementById('menu-label').value = label;
        document.getElementById('menu-url').value = url;
        document.getElementById('menu-parent_id').value = parent_id || '';
        document.getElementById('menu-target').value = target;
        document.getElementById('menu-order').value = order;
        document.getElementById('menu-aktif').checked = aktif;
        document.getElementById('menu-modal').classList.remove('hidden');
    }

    function toggleMenuStatus(id) {
        fetch(`/admin/menu/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                showToast('Durum güncellendi', 'success');
            } else {
                showToast('Bir hata oluştu', 'error');
            }
        });
    }

    function confirmDelete(btn) {
        if(confirm('Bu öğeyi silmek istediğinizden emin misiniz?')) {
            btn.closest('form').submit();
        }
    }

    function hideMenuModal() {
        document.getElementById('menu-modal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', animateCounters);
</script>
@endpush
