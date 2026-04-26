@extends('layouts.admin')
@section('title', e($club->name) . ' - Üyelik Yönetimi')
@section('page_title', e($club->name) . ' - Üyelik Yönetimi')
@section('data-page', 'members')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
<style>
    .dataTables_wrapper .dataTables_filter input {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem;
        font-size: 0.875rem; padding: 0.5rem 1rem; margin-left: 0.5rem; width: 220px;
    }
    .dataTables_wrapper .dataTables_length select {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem;
        font-size: 0.875rem; padding: 0.5rem 2rem 0.5rem 0.75rem; margin: 0 0.5rem;
        appearance: auto;
    }
    /* Tablo kayma fix */
    #uyeler-table thead th, #uyeler-table td {
        white-space: normal !important;
    }
</style>
@endpush

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

<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('admin.kulupler') }}" class="hover:text-primary transition-colors font-medium">Kulüpler</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-slate-800 font-bold">{{ $club->name }}</span>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-primary font-bold">Üyelik Yönetimi</span>
</div>

<div class="admin-card mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 shadow-sm">
    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
        @if($club->logo)
            @php $logoUrl = str_starts_with($club->logo, 'http') ? $club->logo : (file_exists(public_path('uploads/' . $club->logo)) ? asset('uploads/' . $club->logo) : asset('storage/' . $club->logo)); @endphp
            <img src="{{ $logoUrl }}" class="w-14 h-14 rounded-xl object-cover" alt="">
        @else
            <span class="material-symbols-outlined text-primary text-[28px]">groups</span>
        @endif
    </div>
    <div class="flex-1">
        <h2 class="text-xl font-bold font-headline text-slate-800">{{ $club->name }}</h2>
        <p class="text-sm text-slate-500">Kulüp üyelik başvurularını buradan yönetebilirsiniz.</p>
    </div>
    <a href="{{ route('admin.kulupler.form-alanlari', $club->id) }}" class="px-4 py-2 rounded-xl border border-primary/20 text-primary font-bold text-sm hover:bg-primary/5 transition-all flex items-center gap-2 active:scale-95">
        <span class="material-symbols-outlined text-[16px]">dynamic_form</span>Form Alanları
    </a>
    <a href="{{ route('admin.kulupler') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 active:scale-95">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span>Kulüplere Dön
    </a>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-slate-100 rounded-2xl md:rounded-[1.5rem] p-5 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all duration-300">
        <div class="w-12 h-12 bg-slate-50 text-slate-500 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-[28px]">people</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800 leading-none mb-1">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Toplam</p>
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl md:rounded-[1.5rem] p-5 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all duration-300">
        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-[28px]">schedule</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-amber-600 leading-none mb-1">{{ $stats['pending'] }}</p>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Bekleyen</p>
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl md:rounded-[1.5rem] p-5 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all duration-300">
        <div class="w-12 h-12 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-[28px]">check_circle</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-green-600 leading-none mb-1">{{ $stats['approved'] }}</p>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Onaylandı</p>
        </div>
    </div>
    <div class="bg-white border border-slate-100 rounded-2xl md:rounded-[1.5rem] p-5 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all duration-300">
        <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-[28px]">cancel</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-red-600 leading-none mb-1">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Reddedildi</p>
        </div>
    </div>
</div>

<div class="flex items-center gap-2 flex-wrap mb-6">
    <button onclick="filterTable('all')" id="btn-all" class="filter-btn active px-4 py-2 rounded-xl text-sm font-bold transition-all bg-primary text-white shadow-sm">
        Tümü ({{ $stats['total'] }})
    </button>
    <button onclick="filterTable('pending')" id="btn-pending" class="filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
        Bekleyen ({{ $stats['pending'] }})
    </button>
    <button onclick="filterTable('approved')" id="btn-approved" class="filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
        Onaylı ({{ $stats['approved'] }})
    </button>
    <button onclick="filterTable('rejected')" id="btn-rejected" class="filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
        Reddedildi ({{ $stats['rejected'] }})
    </button>
</div>

<div class="admin-card shadow-sm overflow-hidden">
    <div class="p-6 overflow-x-auto">
        <table id="uyeler-table" class="w-full">
            <thead>
                <tr class="border-b border-slate-100">
                    <th>#</th>
                    <th>Kullanıcı</th>
                    <th>E-posta</th>
                    <th>Form</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Silme Modalı --}}
<div id="delete-member-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteMemberModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[28px]">warning</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Üyelik Kaydını Sil</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-member-name"></span>" adlı kullanıcının kaydı silinecektir.</p>
        <form id="delete-member-form" method="POST" class="flex gap-3">
            @csrf @method('DELETE')
            <button type="button" onclick="hideDeleteMemberModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">İptal</button>
            <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm">Sil</button>
        </form>
    </div>
</div>

{{-- Unvan Modalı --}}
<div id="title-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideTitleModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <h3 class="text-lg font-bold font-headline text-slate-800 mb-4">Unvan ve Yetki Belirle</h3>
        <p class="text-sm text-slate-500 mb-5">"<span id="title-member-name" class="font-semibold text-slate-700"></span>" adlı üyeye unvan atayın ve yetkilerini belirleyin.</p>
        <form id="title-form" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Üye Unvanı</label>
                <input type="text" id="title-input" name="title" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Örn: Başkan Yardımcısı, Sekreter...">
            </div>

            <div class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-100">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative">
                        <input type="checkbox" id="is-editor-input" name="is_editor" value="1" class="peer sr-only">
                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-green-500 transition-colors"></div>
                        <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></div>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-700 group-hover:text-primary transition-colors">Editör Yetkisi Ver</span>
                        <span class="block text-[11px] text-slate-500">Bu üye kulüp panelini (Haber, Duyuru vb.) yönetebilir.</span>
                    </div>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="hideTitleModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">İptal</button>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-primary hover:bg-primary-dim text-white font-semibold text-sm shadow-sm">Kaydet</button>
            </div>
        </form>
    </div>
</div>

{{-- Form Verisi Modalı --}}
<div id="form-data-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="hideFormDataModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                    <span class="material-symbols-outlined rounded">description</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold font-headline text-slate-800 leading-none">Başvuru Formu</h3>
                    <p class="text-xs text-slate-500 mt-1"><span id="form-data-name" class="font-semibold text-slate-700"></span> adlı öğrencinin verileri</p>
                </div>
            </div>
            <button onclick="hideFormDataModal()" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div id="form-data-content" class="p-6 space-y-3 overflow-y-auto flex-1 bg-slate-50/30"></div>
        <div class="px-8 py-4 border-t border-slate-100 flex justify-end gap-3 shrink-0 bg-slate-50">
            <button onclick="hideFormDataModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-xl transition-colors">Kapat</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
var currentFilter = 'all';
var uyelerTable;

$(document).ready(function () {
    uyelerTable = $('#uyeler-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.kulupler.uyeler", $club->id) }}',
            data: function (d) { d.status = currentFilter; }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_info', name: 'user.name' },
            { data: 'email', name: 'user.email' },
            { data: 'form_data', orderable: false, searchable: false },
            { data: 'date', name: 'created_at' },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false },
        ],
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json' },
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4"ip>',
        pageLength: 15,
    });
});

function filterTable(status) {
    currentFilter = status;
    var styles = {
        all:      'filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all bg-primary text-white shadow-sm',
        pending:  'filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all bg-amber-500 text-white shadow-sm',
        approved: 'filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all bg-green-600 text-white shadow-sm',
        rejected: 'filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all bg-red-600 text-white shadow-sm',
    };
    var inactive = 'filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50';
    ['all','pending','approved','rejected'].forEach(function(s) {
        document.getElementById('btn-' + s).className = s === status ? styles[s] : inactive;
    });
    uyelerTable.ajax.reload();
}

function showDeleteMemberModal(id, name) {
    document.getElementById('delete-member-name').textContent = name;
    document.getElementById('delete-member-form').action = '{{ url("admin/kulup-uyelik") }}/' + id;
    document.getElementById('delete-member-modal').classList.remove('hidden');
}
function hideDeleteMemberModal() { document.getElementById('delete-member-modal').classList.add('hidden'); }

function showTitleModal(id, name, title, isEditor) {
    document.getElementById('title-member-name').textContent = name;
    document.getElementById('title-input').value = title;
    document.getElementById('is-editor-input').checked = !!isEditor;
    document.getElementById('title-form').action = '{{ url("admin/kulup-uyelik") }}/' + id + '/update-title';
    document.getElementById('title-modal').classList.remove('hidden');
}
function hideTitleModal() { document.getElementById('title-modal').classList.add('hidden'); }

function showFormData(data, name) {
    document.getElementById('form-data-name').textContent = name;
    var container = document.getElementById('form-data-content');
    container.innerHTML = '';
    if (data && data.length > 0) {
        data.forEach(function(item) {
            var div = document.createElement('div');
            div.className = 'bg-white rounded-2xl p-4 border border-slate-100 shadow-none mb-3';
            
            var displayValue = item.value;
            if (displayValue === "1" || displayValue === "on" || displayValue === true || displayValue === "true") {
                displayValue = "Evet";
            } else if (displayValue === "0" || displayValue === "off" || displayValue === false || displayValue === "false") {
                displayValue = "Hayır";
            }
            
            div.innerHTML = '<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">' + esc(item.label) + '</p><p class="text-[15px] text-slate-800 font-bold leading-tight">' + esc(displayValue || '-') + '</p>';
            container.appendChild(div);
        });
    } else {
        container.innerHTML = '<p class="text-sm text-slate-400 italic">Form verisi bulunamadı.</p>';
    }
    document.getElementById('form-data-modal').classList.remove('hidden');
}
function hideFormDataModal() { document.getElementById('form-data-modal').classList.add('hidden'); }

function esc(t) {
    var d = document.createElement('div');
    d.appendChild(document.createTextNode(String(t)));
    return d.innerHTML;
}
</script>
@endpush
