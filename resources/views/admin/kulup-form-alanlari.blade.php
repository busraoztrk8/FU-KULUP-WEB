@extends('layouts.admin')
@section('title', e($club->name) . ' - Form Alanları')
@section('page_title', e($club->name) . ' - Kayıt Formu Yönetimi')
@section('data-page', 'clubs')

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">error</span>{{ session('error') }}
</div>
@endif

{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('admin.kulupler') }}" class="hover:text-primary transition-colors font-medium">Kulüpler</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-slate-800 font-bold">{{ $club->name }}</span>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-primary font-bold">Kayıt Formu</span>
</div>

{{-- Kulüp Bilgi Kartı --}}
<div class="admin-card mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 shadow-sm">
    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
        @if($club->logo)
            <img src="{{ asset('storage/' . $club->logo) }}" class="w-14 h-14 rounded-xl object-cover" alt="">
        @else
            <span class="material-symbols-outlined text-primary text-[28px]">groups</span>
        @endif
    </div>
    <div class="flex-1">
        <h2 class="text-xl font-bold font-headline text-slate-800">{{ $club->name }} - Kayıt Formu</h2>
        <p class="text-sm text-slate-500">Kulübe katılmak isteyenlerden alınacak bilgileri buradan yönetebilirsiniz.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.kulupler.uyeler', $club->id) }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 active:scale-95 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">group</span>Üyeler
        </a>
        <a href="{{ route('admin.kulupler') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 active:scale-95 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>Kulüplere Dön
        </a>
    </div>
</div>

{{-- Aksiyonlar --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2">
        <span class="text-sm text-slate-500 font-medium">Toplam <strong class="text-slate-800">{{ $fields->count() }}</strong> form alanı</span>
    </div>
    <div class="flex items-center gap-3">
        @if($fields->count() === 0)
        <form action="{{ route('admin.kulupler.form-alanlari.varsayilan', $club->id) }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm transition-all active:scale-95">
                <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                Varsayılan Alanları Ekle
            </button>
        </form>
        @endif
        <button onclick="showAddFieldModal()" class="flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-dim text-white rounded-xl font-bold text-sm transition-all shadow-sm active:scale-95">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Yeni Alan Ekle
        </button>
    </div>
</div>

{{-- Form Alanları Tablosu --}}
<div class="admin-card p-0 overflow-hidden shadow-sm">
    @if($fields->count() > 0)
    <div class="overflow-x-auto w-full">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider w-12">Sıra</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">Alan Adı</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">Tür</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">Zorunlu</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">Durum</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50" id="sortable-fields">
                @foreach($fields as $field)
                <tr class="hover:bg-slate-50/80 transition-colors" data-id="{{ $field->id }}">
                    <td class="px-6 py-5 text-sm text-slate-400 font-mono">{{ $field->sort_order }}</td>
                    <td class="px-6 py-5">
                        <div class="max-w-md">
                            <p class="font-bold text-slate-800 text-[15px] leading-tight">{{ $field->label }}</p>
                            @if($field->options)
                                <p class="text-[11px] text-slate-400 mt-1 font-medium italic">Seçenekler: {{ implode(', ', $field->options) }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        @php
                            $typeLabels = [
                                'text' => ['Metin', 'bg-blue-50 text-blue-600 border-blue-100'],
                                'email' => ['E-posta', 'bg-purple-50 text-purple-600 border-purple-100'],
                                'tel' => ['Telefon', 'bg-green-50 text-green-600 border-green-100'],
                                'textarea' => ['Uzun Metin', 'bg-amber-50 text-amber-600 border-amber-100'],
                                'checkbox' => ['Onay Kutusu', 'bg-rose-50 text-rose-600 border-rose-100'],
                                'select' => ['Seçim', 'bg-indigo-50 text-indigo-600 border-indigo-100'],
                            ];
                            $t = $typeLabels[$field->type] ?? ['Bilinmiyor', 'bg-slate-50 text-slate-600 border-slate-100'];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[11px] font-bold border {{ $t[1] }}">{{ $t[0] }}</span>
                    </td>
                    <td class="px-6 py-5">
                        @if($field->is_required)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 shadow-sm bg-red-50/50 px-2.5 py-1.5 rounded-xl border border-red-100"><span class="material-symbols-outlined text-[16px]">check_circle</span>Zorunlu</span>
                        @else
                            <span class="text-xs text-slate-400 font-bold px-2.5 py-1.5 border border-slate-100 rounded-xl bg-slate-50/50">İsteğe Bağlı</span>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        @if($field->is_active)
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-[11px] font-bold bg-green-50 text-green-600 border border-green-100">Aktif</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-[11px] font-bold bg-slate-100 text-slate-400 border border-slate-200 opacity-60">Pasif</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick='showEditFieldModal(@json($field))' class="w-9 h-9 flex items-center justify-center bg-white text-blue-500 rounded-xl hover:bg-blue-50 transition-all border border-slate-200 shadow-sm active:scale-90" title="Düzenle">
                                <span class="material-symbols-outlined text-[18px]">edit_square</span>
                            </button>
                            <button onclick="showDeleteFieldModal({{ $field->id }}, '{{ e(addslashes($field->label)) }}')" class="w-9 h-9 flex items-center justify-center bg-white text-red-500 rounded-xl hover:bg-red-50 transition-all border border-slate-200 shadow-sm active:scale-90" title="Sil">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-16">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-slate-400 text-[36px]">dynamic_form</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-slate-700 mb-2">Henüz form alanı eklenmemiş</h3>
        <p class="text-sm text-slate-500 mb-4">Varsayılan alanları ekleyerek hızlıca başlayabilirsiniz.</p>
    </div>
    @endif
</div>

{{-- Yeni Alan Ekleme Modalı --}}
<div id="add-field-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="hideAddFieldModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-lg font-bold font-headline text-slate-800 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                </span>
                Yeni Form Alanı Ekle
            </h3>
            <button onclick="hideAddFieldModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form action="{{ route('admin.kulupler.form-alanlari.store', $club->id) }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alan Adı <span class="text-red-500">*</span></label>
                <input type="text" name="label" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Örn: Adınız - Soyadınız">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alan Türü <span class="text-red-500">*</span></label>
                    <select name="type" required id="add-field-type" onchange="toggleOptionsField('add')" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="text">Metin</option>
                        <option value="email">E-posta</option>
                        <option value="tel">Telefon</option>
                        <option value="textarea">Uzun Metin</option>
                        <option value="checkbox">Onay Kutusu</option>
                        <option value="select">Seçim (Dropdown)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Placeholder</label>
                    <input type="text" name="placeholder" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Yanıtınız">
                </div>
            </div>

            <div id="add-options-field" class="hidden">
                <label class="block text-sm font-bold text-slate-700 mb-2">Seçenekler <span class="text-xs text-slate-400">(virgülle ayırın)</span></label>
                <input type="text" name="options" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Seçenek 1, Seçenek 2, Seçenek 3">
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="is_required" value="1" checked class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20 transition-all">
                    <span class="text-sm font-bold text-slate-700">Zorunlu alan</span>
                </label>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="hideAddFieldModal()" class="flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
                <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-sm active:scale-95">Ekle</button>
            </div>
        </form>
    </div>
</div>

{{-- Düzenleme Modalı --}}
<div id="edit-field-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="hideEditFieldModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-lg font-bold font-headline text-slate-800 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">edit_square</span>
                </span>
                Form Alanını Düzenle
            </h3>
            <button onclick="hideEditFieldModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form id="edit-field-form" method="POST" class="p-8 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alan Adı <span class="text-red-500">*</span></label>
                <input type="text" name="label" id="edit-label" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alan Türü</label>
                    <select name="type" id="edit-type" onchange="toggleOptionsField('edit')" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="text">Metin</option>
                        <option value="email">E-posta</option>
                        <option value="tel">Telefon</option>
                        <option value="textarea">Uzun Metin</option>
                        <option value="checkbox">Onay Kutusu</option>
                        <option value="select">Seçim (Dropdown)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Placeholder</label>
                    <input type="text" name="placeholder" id="edit-placeholder" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
            </div>

            <div id="edit-options-field" class="hidden">
                <label class="block text-sm font-bold text-slate-700 mb-2">Seçenekler <span class="text-xs text-slate-400">(virgülle ayırın)</span></label>
                <input type="text" name="options" id="edit-options" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="is_required" id="edit-required" value="1" class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary/20 transition-all">
                    <span class="text-sm font-bold text-slate-700">Zorunlu</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" id="edit-active" value="1" class="w-5 h-5 rounded border-slate-300 text-green-600 focus:ring-green-500/20 transition-all">
                    <span class="text-sm font-bold text-slate-700">Aktif</span>
                </label>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="hideEditFieldModal()" class="flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
                <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-sm active:scale-95">Güncelle</button>
            </div>
        </form>
    </div>
</div>

{{-- Silme Onay Modalı --}}
<div id="delete-field-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteFieldModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[28px]">warning</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Form Alanını Sil</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-field-name"></span>" alanı kalıcı olarak silinecektir.</p>
        <form id="delete-field-form" method="POST" class="flex gap-3">
            @csrf @method('DELETE')
            <button type="button" onclick="hideDeleteFieldModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showAddFieldModal() {
    document.getElementById('add-field-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function hideAddFieldModal() {
    document.getElementById('add-field-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function showEditFieldModal(field) {
    document.getElementById('edit-field-form').action = "/admin/form-alanlari/" + field.id;
    document.getElementById('edit-label').value = field.label;
    document.getElementById('edit-type').value = field.type;
    document.getElementById('edit-placeholder').value = field.placeholder || '';
    document.getElementById('edit-required').checked = field.is_required;
    document.getElementById('edit-active').checked = field.is_active;
    
    if (field.options && Array.isArray(field.options)) {
        document.getElementById('edit-options').value = field.options.join(', ');
    } else {
        document.getElementById('edit-options').value = '';
    }
    
    toggleOptionsField('edit');
    document.getElementById('edit-field-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function hideEditFieldModal() {
    document.getElementById('edit-field-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function showDeleteFieldModal(id, name) {
    document.getElementById('delete-field-name').textContent = name;
    document.getElementById('delete-field-form').action = "/admin/form-alanlari/" + id;
    document.getElementById('delete-field-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function hideDeleteFieldModal() {
    document.getElementById('delete-field-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function toggleOptionsField(prefix) {
    const type = document.getElementById(prefix + '-field-type') || document.getElementById(prefix + '-type');
    const optionsDiv = document.getElementById(prefix + '-options-field');
    if (type && optionsDiv) {
        optionsDiv.classList.toggle('hidden', type.value !== 'select');
    }
}
</script>
@endpush
