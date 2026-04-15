@extends('layouts.admin')

@section('title', 'Etkinlik Yönetimi')
@section('page_title', 'Etkinlik Yönetimi')
@section('data-page', 'events')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
@endpush

@section('content')

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">event_available</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['total'] }}">0</p>
            <p class="text-sm text-slate-500 font-medium">Toplam</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-green-600">verified</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['active'] }}">0</p>
            <p class="text-sm text-slate-500 font-medium">Aktif</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-blue-600">task_alt</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['completed'] }}">0</p>
            <p class="text-sm text-slate-500 font-medium">Tamamlandı</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-red-600">event_busy</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['cancelled'] }}">0</p>
            <p class="text-sm text-slate-500 font-medium">İptal</p>
        </div>
    </div>
</div>

<!-- Top Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-end gap-3 mb-6">
    <div class="flex items-center gap-3">
        <select id="status-filter" class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm transition-all">
            <option value="all">Tüm Durumlar</option>
            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Aktif</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal</option>
        </select>
    </div>
    <button onclick="showEventModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm shadow-primary/10 active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Etkinlik
    </button>
</div>

<!-- Table -->
<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full pt-4">
        <table class="w-full" id="etkinlikler-table">
            <thead>
                <tr>
                    <th class="w-12 text-center text-slate-500 font-bold uppercase text-xs tracking-wider">ID</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">ETKİNLİK</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">KULÜP</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">KATEGORİ</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">TARİH</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">KAYIT</th>
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

<!-- Add Event Modal -->
<div id="event-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity opacity-0" id="event-modal-overlay" onclick="hideEventModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col shadow-2xl transform scale-95 opacity-0 transition-all duration-300" id="event-modal-content">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-bold font-headline text-slate-800">Yeni Etkinlik Ekle</h3>
            <button onclick="hideEventModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
            <form id="event-form" action="{{ route('admin.etkinlikler.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-5">
                        <!-- Görsel -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Etkinlik Görseli (Afiş)</label>
                            <div id="event-upload-area" class="border-2 border-dashed border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors group overflow-hidden relative"
                                onclick="document.getElementById('event-image-input').click()">
                                <div id="event-upload-placeholder" class="flex flex-col items-center">
                                    <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-primary text-[20px]">cloud_upload</span>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-800">Yüklemek için tıklayın</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Maks. 10MB</p>
                                </div>
                                <img id="event-image-preview" src="" class="hidden absolute inset-0 w-full h-full object-cover"/>
                                <input id="event-image-input" type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this, 'event-image-preview', 'event-upload-placeholder')"/>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Etkinlik Adı <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required maxlength="100" placeholder="Örn: Kariyer Zirvesi 2024" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all has-char-counter"/>
                            <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/100</span></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Başlangıç <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="start_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Bitiş</label>
                                <input type="datetime-local" name="end_time" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kısa Açıklama</label>
                            <textarea name="short_description" rows="2" maxlength="150" placeholder="Liste sayfalarında görünecek kısa özet..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none has-char-counter"></textarea>
                            <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/150</span></div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                            <div class="flex gap-2">
                                <select name="category_id" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                    <option value="">Seçiniz...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="addQuickCategory(this)" class="bg-primary/10 text-primary w-11 h-11 rounded-xl flex items-center justify-center hover:bg-primary/20 transition-all shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">add</span>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp <span class="text-red-500">*</span></label>
                            <select name="club_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">Seçiniz...</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Katılımcı Sınırı</label>
                                <input type="number" name="max_participants" placeholder="Limitsiz için boş bırakın" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Yayın Durumu</label>
                                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                    <option value="published">Hemen Yayınla</option>
                                    <option value="draft">Taslak</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Konum / Salon</label>
                            <input type="text" name="location" maxlength="100" placeholder="Örn: Konferans Salonu" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harita / Konum URL</label>
                            <input type="url" name="location_url" placeholder="Google Haritalar linki..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                            <span class="text-sm font-bold text-slate-700">Öne Çıkarılan Etkinlik</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Detaylı Açıklama <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" required maxlength="800" placeholder="Etkinlik detayları..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none has-char-counter"></textarea>
                    <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/800</span></div>
                </div>

                <!-- Program Akışı Section -->
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">schedule</span>
                            Program Akışı
                        </h4>
                        <button type="button" onclick="addProgramRow('add')" class="text-primary text-xs font-bold hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span> Satır Ekle
                        </button>
                    </div>
                    <div id="add-program-container" class="space-y-3">
                        <!-- Dynamic rows will be added here -->
                    </div>
                </div>

                <!-- Konuşmacılar Section -->
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
                            Konuşmacılar
                        </h4>
                        <button type="button" onclick="addSpeakerRow('add')" class="text-primary text-xs font-bold hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">person_add</span> Konuşmacı Ekle
                        </button>
                    </div>
                    <div id="add-speakers-container" class="space-y-4">
                        <!-- Dynamic rows will be added here -->
                    </div>
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
            <button onclick="hideEventModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all">İptal</button>
            <button type="submit" form="event-form" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md shadow-primary/20">
                Kaydet ve Yayınla
            </button>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[28px]">warning</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Silmek istediğinize emin misiniz?</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-item-name"></span>" kalıcı olarak silinecektir. Bu işlem geri alınamaz.</p>
        <form id="delete-form" method="POST" class="flex gap-3">
            @csrf @method('DELETE')
            <button type="button" onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </form>
    </div>
</div>

{{-- Etkinlik Detay Modal --}}
<div id="etkinlik-detay-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideEtkinlikDetay()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold font-headline text-slate-800">Etkinlik Detayı</h3>
            <button onclick="hideEtkinlikDetay()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6">
            <div class="w-full h-40 bg-slate-100 rounded-xl mb-4 flex items-center justify-center">
                <span class="material-symbols-outlined text-slate-300 text-[48px]">event</span>
            </div>
            <h4 id="detay-etkinlik-adi" class="text-xl font-bold text-slate-800 mb-3"></h4>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Durum</p>
                    <p id="detay-etkinlik-durum" class="text-sm font-bold text-slate-800"></p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">ID</p>
                    <p id="detay-etkinlik-id" class="text-sm font-bold text-slate-800"></p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Etkinlik Düzenle Modal --}}
<div id="etkinlik-duzenle-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideEtkinlikDuzenle()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-bold font-headline text-slate-800">Etkinliği Düzenle</h3>
            <button onclick="hideEtkinlikDuzenle()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="etkinlik-duzenle-form" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf @method('PUT')
            <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Etkinlik Adı <span class="text-red-500">*</span></label>
                            <input id="edit-etkinlik-adi" name="title" type="text" maxlength="100" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all has-char-counter"/>
                            <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/100</span></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Başlangıç <span class="text-red-500">*</span></label>
                                <input name="start_time" type="datetime-local" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Bitiş</label>
                                <input name="end_time" type="datetime-local" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kısa Açıklama</label>
                            <textarea name="short_description" rows="2" maxlength="150" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none has-char-counter"></textarea>
                            <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/150</span></div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Etkinlik Görseli (Afiş)</label>
                            <div id="edit-event-upload-area" class="border-2 border-dashed border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors group relative overflow-hidden min-h-[100px]"
                                 onclick="document.getElementById('edit-event-image-input').click()">
                                
                                <div id="edit-event-placeholder" class="flex items-center gap-4">
                                    <span class="material-symbols-outlined text-primary text-[24px]">cloud_upload</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Fotoğrafı değiştir</p>
                                        <p class="text-xs text-slate-400">PNG, JPG (Maks. 10MB)</p>
                                    </div>
                                </div>
                                <img id="edit-event-image-preview" src="" class="hidden absolute inset-0 w-full h-full object-cover"/>
                                <input id="edit-event-image-input" type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this, 'edit-event-image-preview', 'edit-event-placeholder')"/>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                                <div class="flex gap-2">
                                    <select name="category_id" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                        <option value="">Seçiniz...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="addQuickCategory(this)" class="bg-primary/10 text-primary w-11 h-11 rounded-xl flex items-center justify-center hover:bg-primary/20 transition-all shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">add</span>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Durum</label>
                                <select id="edit-etkinlik-durum" name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                    <option value="published">Aktif</option>
                                    <option value="draft">Taslak</option>
                                    <option value="cancelled">İptal</option>
                                    <option value="completed">Tamamlandı</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp <span class="text-red-500">*</span></label>
                            <select name="club_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">Seçiniz...</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Katılımcı Sınırı</label>
                                <input type="number" name="max_participants" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Konum</label>
                                <input name="location" type="text" maxlength="100" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harita / Konum URL</label>
                            <input type="url" name="location_url" placeholder="Google Haritalar linki..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" id="edit-etkinlik-featured" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                            <span class="text-sm font-bold text-slate-700">Öne Çıkarılan Etkinlik</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Detaylı Açıklama <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required maxlength="800" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none has-char-counter"></textarea>
                    <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/800</span></div>
                </div>

                <!-- Program Akışı Section (Edit) -->
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">schedule</span>
                            Program Akışı
                        </h4>
                        <button type="button" onclick="addProgramRow('edit')" class="text-primary text-xs font-bold hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span> Satır Ekle
                        </button>
                    </div>
                    <div id="edit-program-container" class="space-y-3"></div>
                </div>

                <!-- Konuşmacılar Section (Edit) -->
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
                            Konuşmacılar
                        </h4>
                        <button type="button" onclick="addSpeakerRow('edit')" class="text-primary text-xs font-bold hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">person_add</span> Konuşmacı Ekle
                        </button>
                    </div>
                    <div id="edit-speakers-container" class="space-y-4"></div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideEtkinlikDuzenle()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2">
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    let table = $('#etkinlikler-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.etkinlikler') }}",
            data: function (d) {
                d.status = $('#status-filter').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-slate-600 font-medium'},
            {data: 'event_info', name: 'title', orderable: false, searchable: true},
            {data: 'club_name', name: 'club.name', orderable: false},
            {data: 'category_name', name: 'category.name', orderable: false},
            {data: 'date', name: 'start_time'},
            {data: 'participants', name: 'current_participants'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[0, 'asc']],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json",
            paginate: { previous: "Önceki", next: "Sonraki" }
        },
        dom: '<"grid"l f>rt<"grid"i p>',
    });

    // İstatistik animasyonu
    $('[data-count]').each(function () {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).data('count')
        }, {
            duration: 1500,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    $('#status-filter').change(function(){
        table.draw();
    });
});

function showEventModal() {
    // Reset form and clear containers
    document.getElementById('event-form').reset();
    document.getElementById('add-program-container').innerHTML = '';
    document.getElementById('add-speakers-container').innerHTML = '';
    document.getElementById('event-image-preview').classList.add('hidden');
    document.getElementById('event-upload-placeholder').classList.remove('hidden');

    document.getElementById('event-modal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('event-modal-overlay').classList.add('opacity-100');
        document.getElementById('event-modal-content').classList.remove('scale-95', 'opacity-0');
        document.getElementById('event-modal-content').classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideEventModal() {
    document.getElementById('event-modal-overlay').classList.remove('opacity-100');
    document.getElementById('event-modal-content').classList.remove('scale-100', 'opacity-100');
    document.getElementById('event-modal-content').classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        document.getElementById('event-modal').classList.add('hidden');
    }, 300);
}

function showEtkinlikDetay(id, adi) {
    document.getElementById('detay-etkinlik-adi').textContent = adi;
    document.getElementById('detay-etkinlik-id').textContent = '#' + id;
    document.getElementById('detay-etkinlik-durum').textContent = 'Yükleniyor...';
    document.getElementById('etkinlik-detay-modal').classList.remove('hidden');
}
function hideEtkinlikDetay() {
    document.getElementById('etkinlik-detay-modal').classList.add('hidden');
}
function showEtkinlikDuzenle(id) {
    // Modal açılmadan önce alanları temizle/hazırla
    document.getElementById('edit-etkinlik-adi').value = 'Yükleniyor...';
    document.getElementById('edit-event-image-preview').classList.add('hidden');
    document.getElementById('edit-event-placeholder').classList.remove('hidden');
    
    document.getElementById('etkinlik-duzenle-form').action = '/admin/etkinlikler/' + id;
    document.getElementById('etkinlik-duzenle-modal').classList.remove('hidden');

    fetch('/admin/etkinlikler/' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-etkinlik-adi').value = data.title;
            document.getElementById('edit-etkinlik-durum').value = data.status;
            
            // Diğer alanları doldur
            let form = document.getElementById('etkinlik-duzenle-form');
            form.querySelector('input[name="start_time"]').value = data.start_time ? data.start_time.substring(0, 16) : '';
            form.querySelector('input[name="end_time"]').value = data.end_time ? data.end_time.substring(0, 16) : '';
            form.querySelector('input[name="location"]').value = data.location || '';
            form.querySelector('input[name="location_url"]').value = data.location_url || '';
            form.querySelector('input[name="max_participants"]').value = data.max_participants || '';
            const categorySelect = form.querySelector('select[name="category_id"]');
            categorySelect.value = data.category_id || '';
            categorySelect.dispatchEvent(new Event('change'));

            form.querySelector('textarea[name="short_description"]').value = data.short_description || '';
            form.querySelector('textarea[name="description"]').value = data.description || '';
            
            // Programs & Speakers Populate
            populateEventDetails(data);

            // Sayaçları güncelle
            setTimeout(() => {
                form.querySelectorAll('.has-char-counter').forEach(el => {
                    const counter = el.parentElement.querySelector('.char-counter');
                    if (counter) {
                        const max = el.getAttribute('maxlength');
                        counter.textContent = `${el.value.length}/${max}`;
                    }
                });
            }, 100);
            
            // Checkbox/Toggle
            document.getElementById('edit-etkinlik-featured').checked = (data.is_featured == 1);

            // Mevcut Resmi Göster
            if (data.image) {
                const previewImg = document.getElementById('edit-event-image-preview');
                const placeholder = document.getElementById('edit-event-placeholder');
                previewImg.src = '/storage/' + data.image;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('Veriler yüklenirken bir hata oluştu.');
        });
}

function addProgramRow(context, data = null) {
    const container = document.getElementById(`${context}-program-container`);
    const index = container.children.length;
    const html = `
        <div class="flex gap-3 items-start animate-fade-in group">
            <div class="w-24 shrink-0">
                <input type="text" name="program[${index}][time]" value="${data?.time || ''}" placeholder="09:00" class="w-full bg-slate-50 border border-slate-200 rounded-lg text-xs px-3 py-2 focus:bg-white transition-all"/>
            </div>
            <div class="flex-1">
                <input type="text" name="program[${index}][title]" value="${data?.title || ''}" placeholder="Açılış Konuşması" class="w-full bg-slate-50 border border-slate-200 rounded-lg text-xs px-3 py-2 focus:bg-white transition-all"/>
            </div>
            <div class="w-32">
                <input type="text" name="program[${index}][location]" value="${data?.location || ''}" placeholder="Salon A" class="w-full bg-slate-50 border border-slate-200 rounded-lg text-xs px-3 py-2 focus:bg-white transition-all"/>
            </div>
            <button type="button" onclick="this.closest('.flex').remove()" class="mt-2 text-slate-300 hover:text-red-500 transition-colors">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function addSpeakerRow(context, data = null) {
    const container = document.getElementById(`${context}-speakers-container`);
    const index = container.children.length;
    
    const previewSrc = data?.image ? `/storage/${data.image}` : '';
    const hasImage = data?.image ? '' : 'hidden';
    const noImage = data?.image ? 'hidden' : '';

    const html = `
        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 relative group animate-fade-in">
            <button type="button" onclick="this.closest('.bg-slate-50').remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 shadow-sm z-10">
                <span class="material-symbols-outlined text-[14px]">close</span>
            </button>
            <div class="flex gap-4">
                <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 overflow-hidden shrink-0 relative cursor-pointer group/img" onclick="$(this).find('input').click()">
                    <img src="${previewSrc}" class="w-full h-full object-cover ${hasImage} speaker-preview"/>
                    <div class="absolute inset-0 flex items-center justify-center text-slate-300 ${noImage} speaker-placeholder">
                        <span class="material-symbols-outlined text-[20px]">add_a_photo</span>
                    </div>
                    <input type="file" name="speakers[${index}][image]" class="hidden" accept="image/*" onchange="previewSpeakerImage(this)"/>
                    ${data?.image ? `<input type="hidden" name="speakers[${index}][existing_image]" value="${data.image}"/>` : ''}
                </div>
                <div class="flex-1 space-y-3">
                    <input type="text" name="speakers[${index}][name]" value="${data?.name || ''}" placeholder="Konuşmacı Adı" class="w-full bg-white border border-slate-200 rounded-lg text-xs px-3 py-2 focus:ring-1 focus:ring-primary/20 transition-all"/>
                    <input type="text" name="speakers[${index}][title]" value="${data?.title || ''}" placeholder="Ünvan (Örn: CEO, Akademisyen)" class="w-full bg-white border border-slate-200 rounded-lg text-[10px] px-3 py-1.5 focus:ring-1 focus:ring-primary/20 transition-all text-slate-500 font-medium"/>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function previewSpeakerImage(input) {
    const file = input.files[0];
    const parent = $(input).closest('.relative');
    const preview = parent.find('.speaker-preview');
    const placeholder = parent.find('.speaker-placeholder');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.attr('src', e.target.result).removeClass('hidden');
            placeholder.addClass('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function populateEventDetails(data) {
    const progContainer = document.getElementById('edit-program-container');
    const speakContainer = document.getElementById('edit-speakers-container');
    progContainer.innerHTML = '';
    speakContainer.innerHTML = '';

    if (data.program && data.program.length > 0) {
        data.program.forEach(p => addProgramRow('edit', p));
    }
    if (data.speakers && data.speakers.length > 0) {
        data.speakers.forEach(s => addSpeakerRow('edit', s));
    }
}

function previewImage(input, previewId, placeholderId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

$(document).on('input', '.has-char-counter', function() {
    const counter = $(this).parent().find('.char-counter');
    const max = $(this).attr('maxlength');
    const len = $(this).val().length;
    counter.text(len + '/' + max);
    if (len >= max) {
        counter.addClass('text-red-500').removeClass('text-slate-400');
    } else {
        counter.addClass('text-slate-400').removeClass('text-red-500');
    }
});
function hideEtkinlikDuzenle() {
    document.getElementById('etkinlik-duzenle-modal').classList.add('hidden');
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
    document.getElementById('delete-form').action = "/admin/etkinlikler/" + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}
function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>
@endpush
