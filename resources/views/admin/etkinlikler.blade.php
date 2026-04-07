@extends('layouts.admin')

@section('title', 'Etkinlik Yönetimi')
@section('page_title', 'Etkinlik Yönetimi')
@section('data-page', 'events')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
@endpush

@section('content')

<!-- Top Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-end gap-3 mb-6">
    <div class="flex items-center gap-3">
        <select id="status-filter" class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm">
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
        <div class="p-6 overflow-y-auto flex-1 custom-scrollbar space-y-5">
            <form id="event-form" action="{{ route('admin.etkinlikler.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Görsel -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Etkinlik Görseli (Afiş)</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors group">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-primary text-[24px]">cloud_upload</span>
                        </div>
                        <p class="text-sm font-semibold text-slate-800">Fotoğraf yüklemek için tıklayın</p>
                        <p class="text-xs text-slate-500 mt-1">SVG, PNG, JPG veya GIF (Maks. 5MB)</p>
                        <input type="file" name="image" class="hidden" accept="image/*"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Etkinlik Adı <span class="text-red-500">*</span></label>
                            <input type="text" name="title" placeholder="Örn: Kariyer Zirvesi 2024" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Başlangıç Tarihi ve Saati <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="start_time" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Konum / Salon</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">location_on</span>
                                <input type="text" name="location" placeholder="Örn: Mühendislik Fakültesi Konferans Salonu" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm pl-11 pr-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                            <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                                <option value="">Seçiniz...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp <span class="text-red-500">*</span></label>
                            <select name="club_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                                <option value="">Seçiniz...</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Yayın Durumu</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                                <option value="published">Hemen Yayınla</option>
                                <option value="draft">Taslak Olarak Kaydet</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Etkinlik Açıklaması</label>
                    <textarea name="description" rows="4" placeholder="Etkinlik hakkında detaylı bilgi giriniz..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm resize-none"></textarea>
                </div>
            </form>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
            <button onclick="hideEventModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
            <button type="submit" form="event-form" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md shadow-primary/20 flex items-center gap-2 active:scale-95">
                <span class="material-symbols-outlined text-[18px]">done</span>Kaydet ve Yayınla
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
            <div class="p-6 overflow-y-auto flex-1 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Etkinlik Adı <span class="text-red-500">*</span></label>
                        <input id="edit-etkinlik-adi" name="title" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
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
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Başlangıç Tarihi <span class="text-red-500">*</span></label>
                        <input name="start_time" type="datetime-local" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konum</label>
                        <input name="location" type="text" placeholder="Konum..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                        <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">Seçiniz...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp <span class="text-red-500">*</span></label>
                        <select name="club_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">Seçiniz...</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Açıklama <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideEtkinlikDuzenle()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Güncelle
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
            {data: 'event_info', name: 'title', orderable: false, searchable: false},
            {data: 'category_name', name: 'category.name', orderable: false},
            {data: 'date', name: 'start_time'},
            {data: 'participants', name: 'current_participants'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json",
            paginate: { previous: "Önceki", next: "Sonraki" }
        },
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4"l f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4"i p>',
    });

    $('#status-filter').change(function(){
        table.draw();
    });
});

function showEtkinlikDetay(id, adi) {
    document.getElementById('detay-etkinlik-adi').textContent = adi;
    document.getElementById('detay-etkinlik-id').textContent = '#' + id;
    document.getElementById('detay-etkinlik-durum').textContent = 'Yükleniyor...';
    document.getElementById('etkinlik-detay-modal').classList.remove('hidden');
}
function hideEtkinlikDetay() {
    document.getElementById('etkinlik-detay-modal').classList.add('hidden');
}
function showEtkinlikDuzenle(id, adi, durum) {
    document.getElementById('edit-etkinlik-adi').value = adi;
    document.getElementById('edit-etkinlik-durum').value = durum;
    document.getElementById('etkinlik-duzenle-form').action = '/admin/etkinlikler/' + id;
    document.getElementById('etkinlik-duzenle-modal').classList.remove('hidden');
}
function hideEtkinlikDuzenle() {
    document.getElementById('etkinlik-duzenle-modal').classList.add('hidden');
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
