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

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <h2 class="text-xl font-bold font-headline text-slate-800">Menü Öğeleri</h2>
    <button onclick="showMenuModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Ana Menü Öğesi Ekle
    </button>
</div>

<div class="admin-card overflow-hidden p-0 shadow-sm border border-slate-200">
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
        <h3 class="font-bold text-slate-700 text-sm">Hiyerarşik Menü Listesi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
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
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2 text-slate-300">
                                <span class="material-symbols-outlined text-[14px]">remove</span>
                                <span class="text-sm font-medium text-slate-600">{{ $counter++ }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 text-[15px]">{{ $item->label }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium border border-slate-200">
                                <span class="material-symbols-outlined text-[14px]">bolt</span>
                                {{ $item->url }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" {{ $item->is_active ? 'checked' : '' }} onchange="toggleMenuStatus({{ $item->id }})">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                                <span class="text-xs font-bold text-slate-500">{{ $item->is_active ? 'Aktif' : 'Pasif' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="showSubMenuModal({{ $item->id }}, '{{ addslashes($item->label) }}')" class="h-9 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm shadow-blue-100 active:scale-95">
                                    <span class="material-symbols-outlined text-[16px]">add</span> Alt Menü
                                </button>
                                <button onclick="showMenuDuzenle({{ $item->id }}, '{{ addslashes($item->label) }}', '{{ addslashes($item->url) }}', '{{ $item->parent_id }}', '{{ $item->target }}', {{ $item->order }}, {{ $item->is_active ? 'true' : 'false' }})" class="w-9 h-9 bg-amber-100 hover:bg-amber-200 text-amber-600 rounded-lg flex items-center justify-center transition-all active:scale-95" title="Düzenle">
                                    <span class="material-symbols-outlined text-[18px]">edit_note</span>
                                </button>
                                <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" onsubmit="return confirm('Silmek istediğine emin misin?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all active:scale-95" title="Sil">
                                        <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Rows for sub-menus --}}
                    @foreach($item->children as $child)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-3 text-center text-slate-400 text-xs font-medium">0</td>
                        <td class="px-6 py-3 pl-12">
                            <div class="flex items-center gap-2">
                                <span class="text-slate-300">-</span>
                                <span class="text-slate-600 font-medium text-sm">{{ $child->label }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white text-blue-500 rounded-lg text-[11px] border border-blue-100">
                                <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                {{ $child->url }}
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2 opacity-80">
                                <label class="relative inline-flex items-center cursor-pointer scale-75 origin-left">
                                    <input type="checkbox" class="sr-only peer" {{ $child->is_active ? 'checked' : '' }} onchange="toggleMenuStatus({{ $child->id }})">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $child->is_active ? 'Aktif' : 'Pasif' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="showSubMenuModal({{ $child->id }}, '{{ addslashes($child->label) }}')" class="h-8 px-2.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-[10px] font-bold flex items-center gap-1 transition-all active:scale-95">
                                    <span class="material-symbols-outlined text-[14px]">add</span> Alt Menü
                                </button>
                                <button onclick="showMenuDuzenle({{ $child->id }}, '{{ addslashes($child->label) }}', '{{ addslashes($child->url) }}', '{{ $child->parent_id }}', '{{ $child->target }}', {{ $child->order }}, {{ $child->is_active ? 'true' : 'false' }})" class="w-8 h-8 text-amber-500 hover:bg-amber-50 rounded-lg flex items-center justify-center transition-all" title="Düzenle">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <form action="{{ route('admin.menu.destroy', $child) }}" method="POST" onsubmit="return confirm('Silmek istediğine emin misin?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 text-red-500 hover:bg-red-50 rounded-lg flex items-center justify-center transition-all" title="Sil">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
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
function hideMenuModal() {
    document.getElementById('menu-modal').classList.add('hidden');
}
</script>
@endpush
