@extends('layouts.admin')

@section('title', 'Kulüp Yönetimi')
@section('page_title', 'Kulüp Yönetimi')
@section('data-page', 'clubs')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        background-color: rgb(248 250 252);
        border: 1px solid rgb(226 232 240);
        border-radius: 0.75rem;
        height: 48px;
        display: flex;
        items-center: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal;
        padding-left: 1rem;
        font-size: 0.875rem;
        color: rgb(71 85 105);
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
    }
    .select2-container--focus .select2-selection--single {
        border-color: var(--primary) !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 2px rgba(93, 16, 33, 0.1);
    }
    .select2-dropdown {
        border-radius: 1rem;
        border: 1px solid rgb(226 232 240);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        margin-top: 4px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
    <div class="flex items-center gap-2 mb-2"><span class="material-symbols-outlined text-[18px]">error</span><strong>Hata oluştu:</strong></div>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">diversity_3</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['total'] }}">0</p>
            <p class="text-sm text-slate-500">Toplam Kulüp</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['active'] }}">0</p>
            <p class="text-sm text-slate-500">Aktif Kulüp</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-amber-600">pending</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['inactive'] }}">0</p>
            <p class="text-sm text-slate-500">Pasif Kulüp</p>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-end gap-3 mb-6">
    <div class="flex items-center gap-3">
        <select id="kategori-filter" class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm transition-all">
            <option value="all">Tüm Kategoriler</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    @if(auth()->user()->isAdmin())
    <button onclick="showKulupEkle()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm shadow-primary/10 active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Kulüp
    </button>
    @endif
</div>

<!-- Table -->
<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full pt-4">
        <table class="w-full" id="kulupler-table">
            <thead>
                <tr>
                    <th class="w-12 text-center text-slate-500 font-bold uppercase text-xs tracking-wider">ID</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">KULÜP</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">KATEGORİ</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">BAŞKAN</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">ÜYE SAYISI</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">DURUM</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">İŞLEMLER</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will fill this -->
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="modal-overlay absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[28px]">warning</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Silmek istediğinize emin misiniz?</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-item-name"></span>" kalıcı olarak silinecektir.</p>
        <form id="delete-form" method="POST" class="flex gap-3">
            @csrf @method('DELETE')
            <button type="button" onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </form>
    </div>
</div>

{{-- Kulüp Detay Modal --}}
<div id="kulup-detay-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKulupDetay()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold font-headline text-slate-800">Kulüp Detayı</h3>
            <button onclick="hideKulupDetay()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-primary rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[28px]">groups</span>
                </div>
                <div>
                    <h4 id="detay-kulup-adi" class="text-xl font-bold text-slate-800"></h4>
                    <p id="detay-kulup-kategori" class="text-sm text-slate-500"></p>
                </div>
            </div>
            <p id="detay-kulup-aciklama" class="text-sm text-slate-600 leading-relaxed bg-slate-50 rounded-xl p-4"></p>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Durum</p>
                    <p id="detay-kulup-durum" class="text-sm font-bold text-slate-800"></p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Üye Sayısı</p>
                    <p id="detay-kulup-uye" class="text-sm font-bold text-slate-800"></p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Kulüp Düzenle Modal --}}
<div id="kulup-duzenle-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKulupDuzenle()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-bold font-headline text-slate-800">Kulübü Düzenle</h3>
            <button onclick="hideKulupDuzenle()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="kulup-duzenle-form" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf @method('PUT')
            <div class="p-6 overflow-y-auto flex-1 space-y-4">
                {{-- Global Errors --}}
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-medium mb-4">
                    <p class="font-bold mb-1 text-xs uppercase tracking-wider">Lütfen hataları düzeltin:</p>
                    <ul class="list-disc list-inside text-xs">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp Adı <span class="text-red-500">*</span></label>
                        <input id="edit-kulup-adi" name="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                        <div class="flex gap-2">
                            <select name="category_id" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">Seçiniz...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="addQuickCategory(this)" class="bg-primary/10 text-primary w-11 h-11 rounded-xl flex items-center justify-center hover:bg-primary/20 transition-all shrink-0" title="Yeni Kategori Ekle">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                            </button>
                        </div>
                    </div>
                </div>
                @if(auth()->user()->isAdmin())
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp Başkanı</label>
                    <select id="edit-kulup-president" name="president_id" class="w-full ajax-user-select">
                        <option value="">Seçiniz...</option>
                    </select>
                </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">Kurucu Adı</label>
                        <input id="edit-founder-name" name="founder_name" type="text" placeholder="Dr. Ahmet Tekin" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">Kuruluş Yılı</label>
                        <input id="edit-established-year" name="established_year" type="text" placeholder="2018" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">Web Sitesi</label>
                        <input id="edit-website-url" name="website_url" type="url" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">Instagram</label>
                        <input id="edit-instagram-url" name="instagram_url" type="url" placeholder="https://instagram.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">YouTube</label>
                        <input id="edit-youtube-url" name="youtube_url" type="url" placeholder="https://youtube.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">Twitter (X)</label>
                        <input id="edit-twitter-url" name="twitter_url" type="url" placeholder="https://twitter.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all"/>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Açıklama</label>
                    <textarea id="edit-kulup-aciklama" name="description" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Misyonumuz</label>
                        <textarea id="edit-mission" name="mission" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Vizyonumuz</label>
                        <textarea id="edit-vision" name="vision" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Logo</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors relative h-[72px] overflow-hidden"
                             onclick="document.getElementById('edit-logo-input').click()">
                            <img id="edit-logo-preview" class="absolute inset-0 w-full h-full object-contain p-1 hidden bg-white">
                            <div id="edit-logo-placeholder" class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-primary text-[24px]">cloud_upload</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Logo yükle</p>
                                </div>
                            </div>
                            <input id="edit-logo-input" type="file" name="logo" class="hidden" accept="image/*" onchange="previewImage(this, 'edit-logo-preview', 'edit-logo-placeholder')"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kapak Fotoğrafı</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors relative h-[72px] overflow-hidden"
                             onclick="document.getElementById('edit-cover-input').click()">
                            <img id="edit-cover-preview" class="absolute inset-0 w-full h-full object-cover hidden bg-white">
                            <div id="edit-cover-placeholder" class="flex items-center gap-4">
                                <span class="material-symbols-outlined text-primary text-[24px]">image</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Kapak seç</p>
                                </div>
                            </div>
                            <input id="edit-cover-input" type="file" name="cover_image" class="hidden" accept="image/*" onchange="previewImage(this, 'edit-cover-preview', 'edit-cover-placeholder')"/>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-5 mt-5">
                    <label class="block text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500 text-[20px]">photo_library</span>
                        Kulüp Galerisi
                    </label>
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 mb-4" id="edit-gallery-list">
                        {{-- AJAX ile dolacak --}}
                    </div>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors"
                         onclick="document.getElementById('edit-gallery-input').click()">
                        <span class="material-symbols-outlined text-slate-400 text-[32px] mb-2">add_a_photo</span>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Yeni Fotoğraflar Ekle</p>
                        <input id="edit-gallery-input" type="file" name="gallery[]" multiple class="hidden" accept="image/*"/>
                    </div>
                </div>
                @if(auth()->user()->isAdmin())
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="edit-kulup-aktif" name="is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                    <label for="edit-kulup-aktif" class="text-sm font-semibold text-slate-600 cursor-pointer">Aktif kulüp</label>
                </div>
                @endif
            </div>
            <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideKulupDuzenle()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

{{-- Kulüp Ekle Modal --}}
<div id="kulup-ekle-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKulupEkle()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-bold font-headline text-slate-800">Yeni Kulüp Ekle</h3>
            <button onclick="hideKulupEkle()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('admin.kulupler.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="p-6 overflow-y-auto flex-1 space-y-5">
                
                {{-- Global Errors --}}
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-medium">
                    <p class="font-bold mb-1">Lütfen hataları düzeltin:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Logo yükleme --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Logo</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors group relative min-h-[160px] overflow-hidden"
                         onclick="document.getElementById('logo-input').click()">
                        <img id="logo-preview" class="absolute inset-0 w-full h-full object-contain p-4 hidden bg-white">
                        <div id="logo-placeholder" class="flex flex-col items-center justify-center">
                            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-primary text-[24px]">cloud_upload</span>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Logo yüklemek için tıklayın</p>
                            <p class="text-xs text-slate-400 mt-1">PNG, JPG (Maks. 10MB)</p>
                        </div>
                        <input id="logo-input" type="file" name="logo" class="hidden" accept="image/*" onchange="previewImage(this, 'logo-preview', 'logo-placeholder')"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kurucu Adı</label>
                        <input type="text" name="founder_name" placeholder="Örn: Dr. Ahmet Tekin"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kuruluş Yılı</label>
                        <input type="text" name="established_year" placeholder="Örn: 2018"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp Adı <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Örn: Robotik Kulübü"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                        <div class="flex gap-2">
                            <select name="category_id" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                                <option value="">Seçiniz...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="addQuickCategory(this)" class="bg-primary/10 text-primary w-11 h-11 rounded-xl flex items-center justify-center hover:bg-primary/20 transition-all shrink-0" title="Yeni Kategori Ekle">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Misyonumuz</label>
                    <textarea name="mission" rows="2" placeholder="Kulübün misyonu..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Vizyonumuz</label>
                    <textarea name="vision" rows="2" placeholder="Kulübün vizyonu..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp Başkanı</label>
                        <select name="president_id" class="w-full ajax-user-select">
                            <option value="">Seçiniz...</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Durum</label>
                        <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">Web Sitesi</label>
                        <input name="website_url" type="url" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all shadow-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">Instagram</label>
                        <input name="instagram_url" type="url" placeholder="https://instagram.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all shadow-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">YouTube</label>
                        <input name="youtube_url" type="url" placeholder="https://youtube.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all shadow-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1 text-[11px] uppercase tracking-wider text-slate-400">Twitter (X)</label>
                        <input name="twitter_url" type="url" placeholder="https://twitter.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:bg-white transition-all shadow-sm"/>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kısa Açıklama</label>
                    <input type="text" name="short_description" placeholder="Kulüp hakkında kısa bir cümle..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Detaylı Açıklama</label>
                    <textarea name="description" rows="4" placeholder="Kulüp hakkında detaylı bilgilendirme..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm resize-none"></textarea>
                </div>

            </div>
            <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideKulupEkle()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md shadow-primary/20 flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Kulübü Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let table = $('#kulupler-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.kulupler') }}",
            data: function (d) {
                d.category_id = $('#kategori-filter').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-slate-600 font-medium'},
            {data: 'club_info', name: 'club_info', orderable: false, searchable: false},
            {data: 'category_name', name: 'category.name'},
            {data: 'president_name', name: 'president.name'},
            {data: 'members_count', name: 'members_count', orderable: false, searchable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json",
            paginate: { previous: "Önceki", next: "Sonraki" }
        },
        dom: '<"grid"l f>rt<"grid"i p>',
    });

    $('#kategori-filter').change(function(){
        table.draw();
    });

    // Select2 AJAX initialization
    $('.ajax-user-select').each(function() {
        const parentModal = $(this).closest('.fixed');
        $(this).select2({
            placeholder: 'Başkan seçmek için isim yazın...',
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ route('admin.kullanicilar.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            dropdownParent: parentModal.length ? parentModal : $(document.body)
        });
    });
});

function showKulupEkle() {
    document.getElementById('kulup-ekle-modal').classList.remove('hidden');
}
function hideKulupEkle() {
    document.getElementById('kulup-ekle-modal').classList.add('hidden');
}
// Hata varsa modalı otomatik aç
@if($errors->any())
document.addEventListener('DOMContentLoaded', function() { showKulupEkle(); });
@endif
function showKulupDetay(id, adi) {
    document.getElementById('detay-kulup-adi').textContent = adi;
    document.getElementById('detay-kulup-aciklama').textContent = 'Kulüp detayları yükleniyor...';
    document.getElementById('detay-kulup-durum').textContent = 'Aktif';
    document.getElementById('detay-kulup-uye').textContent = '-';
    document.getElementById('kulup-detay-modal').classList.remove('hidden');
}
function hideKulupDetay() {
    document.getElementById('kulup-detay-modal').classList.add('hidden');
}
function showKulupDuzenle(id) {
    // Modal açılmadan önce alanları temizle/hazırla
    document.getElementById('edit-kulup-adi').value = 'Yükleniyor...';
    document.getElementById('edit-kulup-aciklama').value = '';
    document.getElementById('edit-kulup-aktif').checked = false;
    document.getElementById('kulup-duzenle-form').querySelectorAll('select[name="category_id"]').forEach(el => el.value = '');
    
    document.getElementById('kulup-duzenle-form').action = '/admin/kulupler/' + id;
    document.getElementById('kulup-duzenle-modal').classList.remove('hidden');

    fetch('/admin/kulupler/' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-kulup-adi').value = data.name;
            document.getElementById('edit-kulup-aciklama').value = data.description || '';
            document.getElementById('edit-kulup-aktif').checked = (data.is_active == 1);
            
            // Kategori seçimi
            let catSelect = document.querySelector('#kulup-duzenle-form select[name="category_id"]');
            if (catSelect) catSelect.value = data.category_id || '';

            // Başkan ve Linkler
            const presSelect = document.getElementById('edit-kulup-president');
            if(presSelect) {
                if(data.president) {
                    // Mevcut başkanı Select2'ye ekle
                    var option = new Option(data.president.name + " (" + data.president.email + ")", data.president.id, true, true);
                    $(presSelect).append(option).trigger('change');
                } else {
                    $(presSelect).val(null).trigger('change');
                }
            }
            
            document.getElementById('edit-website-url').value = data.website_url || '';
            document.getElementById('edit-instagram-url').value = data.instagram_url || '';
            document.getElementById('edit-youtube-url').value = data.youtube_url || '';
            document.getElementById('edit-twitter-url').value = data.twitter_url || '';

            // Yeni alanlar
            if(document.getElementById('edit-mission')) document.getElementById('edit-mission').value = data.mission || '';
            if(document.getElementById('edit-vision')) document.getElementById('edit-vision').value = data.vision || '';
            if(document.getElementById('edit-founder-name')) document.getElementById('edit-founder-name').value = data.founder_name || '';
            if(document.getElementById('edit-established-year')) document.getElementById('edit-established-year').value = data.established_year || '';

            // Galeri yükle
            const galleryList = document.getElementById('edit-gallery-list');
            if(galleryList) {
                galleryList.innerHTML = '';
                if(data.images && data.images.length > 0) {
                    data.images.forEach(img => {
                        galleryList.innerHTML += `
                            <div class="relative group aspect-square rounded-lg overflow-hidden border border-slate-200">
                                <img src="/storage/${img.image_path}" class="w-full h-full object-cover">
                                <button type="button" onclick="deleteGalleryImage(${img.id}, this)" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </div>
                        `;
                    });
                } else {
                    galleryList.innerHTML = '<p class="col-span-full text-xs text-slate-400 italic">Henüz galeri resmi eklenmemiş.</p>';
                }
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('Veriler yüklenirken bir hata oluştu.');
        });
}
function hideKulupDuzenle() {
    document.getElementById('kulup-duzenle-modal').classList.add('hidden');
}

function addQuickCategory(btn) {
    const name = prompt("Yeni kategori adını giriniz:");
    if (!name || name.trim() === "") return;

    fetch("{{ route('admin.kategoriler.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: JSON.stringify({ name: name })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tüm kategori selectlerini bul ve güncelle
            const selects = document.querySelectorAll('select[name="category_id"]');
            selects.forEach(select => {
                const option = new Option(data.category.name, data.category.id);
                select.add(option);
            });
            // Tıklanan butonun yanındaki selecti seç
            const currentSelect = btn.previousElementSibling;
            if (currentSelect) currentSelect.value = data.category.id;
        } else {
            alert(data.message || "Kategori eklenirken bir hata oluştu. İsim zaten mevcut olabilir.");
        }
    })
    .catch(error => {
        console.error("Hata:", error);
        alert("Kategori eklenirken bir hata oluştu.");
    });
}
function showDeleteModal(id, baslik) {
    document.getElementById('delete-item-name').textContent = baslik;
    document.getElementById('delete-form').action = "/admin/kulupler/" + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}
function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
function deleteGalleryImage(id, btn) {
    if(!confirm('Bu resmi galeriden silmek istediğinize emin misiniz?')) return;
    
    fetch('/admin/kulup-gallery/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            btn.parentElement.remove();
        } else {
            alert('Silme sırasında bir hata oluştu.');
        }
    })
    .catch(error => {
        console.error('Hata:', error);
        alert('Silme sırasında bir hata oluştu.');
    });
}

function previewImage(input, previewId, placeholderId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('opacity-0');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
