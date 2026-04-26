@extends('layouts.admin')

@section('title', 'Üyelik Yönetimi')
@section('page_title', 'Üyelik Yönetimi')
@section('data-page', 'members')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_filter input {
            @apply bg-slate-50 border-slate-200 rounded-xl text-sm px-4 py-2 focus:ring-primary/20 focus:border-primary transition-all ml-2;
            width: 240px;
        }
        .dataTables_wrapper .dataTables_length select {
            @apply bg-slate-50 border-slate-200 rounded-xl text-sm px-8 py-2 focus:ring-primary/20 focus:border-primary transition-all mx-2;
        }
        /* Tablo kayma fix */
        #uyelikler-table thead th {
            white-space: normal !important;
            min-width: 100px;
        }
        #uyelikler-table td {
            white-space: normal !important;
        }
    </style>
@endpush

@section('content')

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold font-headline text-slate-800">Üyelik Yönetimi</h2>
            <p class="text-sm text-slate-500">Tüm kulüp üyelik başvurularını ve aktif üyeleri buradan yönetebilirsiniz.</p>
        </div>
        <button onclick="showAssignmentModal()" class="flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-dim text-white rounded-xl font-bold text-sm transition-all shadow-sm active:scale-95 whitespace-nowrap">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Yeni Üyelik / Atama
        </button>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">error</span>{{ session('error') }}
        </div>
    @endif

    <div class="admin-card p-6 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="admin-table w-full no-footer" id="uyelikler-table">
                <thead>
                    <tr>
                        <th class="w-12 text-center text-slate-500 font-bold uppercase text-[10px] tracking-widest whitespace-nowrap">ID</th>
                        <th class="text-left text-slate-500 font-bold uppercase text-[10px] tracking-widest whitespace-nowrap">ÖĞRENCİ</th>
                        <th class="text-left text-slate-500 font-bold uppercase text-[10px] tracking-widest whitespace-nowrap">KULÜP</th>
                        <th class="text-left text-slate-500 font-bold uppercase text-[10px] tracking-widest whitespace-nowrap">BAŞVURU TARİHİ</th>
                        <th class="text-left text-slate-500 font-bold uppercase text-[10px] tracking-widest whitespace-nowrap">DURUM</th>
                        <th class="text-right text-slate-500 font-bold uppercase text-[10px] tracking-widest whitespace-nowrap">İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <!-- DataTables manually handles this -->
                </tbody>
            </table>
        </div>
    </div>

    {{-- Manuel Atama Modalı --}}
    <div id="assignment-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="hideAssignmentModal()"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-lg font-bold font-headline text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                    </span>
                    Yeni Üyelik Oluştur (Atama)
                </h3>
                <button onclick="hideAssignmentModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form action="{{ route('admin.members.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Öğrenci Seçiniz</label>
                    <select name="user_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="">Öğrenci arayın veya seçin...</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp Seçiniz</label>
                    <select name="club_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="">Kulüp seçin...</option>
                        @foreach($clubs as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Başvuru Durumu</label>
                    <select name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="approved">Onaylı (Aktif Üye)</option>
                        <option value="pending">Beklemede (Başvuru)</option>
                        <option value="rejected">Reddedildi</option>
                    </select>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="hideAssignmentModal()" class="flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
                    <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-sm active:scale-95">Kaydı Oluştur</button>
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
            let table = $('#uyelikler-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.members.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-slate-500 font-medium text-xs' },
                    { data: 'student_info', name: 'user.name' },
                    { data: 'club_info', name: 'club.name' },
                    { data: 'date', name: 'created_at' },
                    { data: 'status_label', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' },
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json",
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-6"l f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-6"i p>',
                drawCallback: function() {
                    // Custom styles for pagination if needed
                }
            });
        });

        function showAssignmentModal() {
            document.getElementById('assignment-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideAssignmentModal() {
            document.getElementById('assignment-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Form Data Modal Functions
        function showFormDataModal(memberId, studentName, jsonData) {
            document.getElementById('form-data-student-name').textContent = studentName;
            
            const container = document.getElementById('form-data-container');
            container.innerHTML = '';
            
            try {
                const data = JSON.parse(jsonData);
                if (data && data.length > 0) {
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'bg-white rounded-2xl p-4 border border-slate-100 shadow-none mb-3';
                        
                        let displayValue = item.value;
                        if (displayValue === "1" || displayValue === "on" || displayValue === true || displayValue === "true") {
                            displayValue = "Evet";
                        } else if (displayValue === "0" || displayValue === "off" || displayValue === false || displayValue === "false") {
                            displayValue = "Hayır";
                        }
                        
                        // Note: Using a helper function esc() if defined, or simple template literal
                        const labelHtml = typeof esc === 'function' ? esc(item.label) : item.label;
                        const valueHtml = typeof esc === 'function' ? esc(displayValue || '-') : (displayValue || '-');
                        
                        div.innerHTML = `<p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">${labelHtml}</p>
                                         <p class="text-[15px] text-slate-800 font-bold leading-tight">${valueHtml}</p>`;
                        container.appendChild(div);
                    });
                }
 else {
                    container.innerHTML = '<p class="text-sm text-slate-500 italic">Bu üye için form verisi bulunamadı.</p>';
                }
            } catch (e) {
                container.innerHTML = '<p class="text-sm text-red-500">Veri okunamadı.</p>';
            }
            
            document.getElementById('form-data-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideFormDataModal() {
            document.getElementById('form-data-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endpush

{{-- Form Data Modal --}}
<div id="form-data-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="hideFormDataModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <span class="material-symbols-outlined rounded">description</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Başvuru Formu</h3>
                        <p class="text-xs text-slate-500"><span id="form-data-student-name" class="font-semibold text-slate-700"></span> adlı öğrencinin verileri</p>
                    </div>
                </div>
                <button onclick="hideFormDataModal()" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto" id="form-data-container">
                {{-- Data will be populated here via JS --}}
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 shrink-0 bg-slate-50">
                <button type="button" onclick="hideFormDataModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-xl transition-colors">
                    Kapat
                </button>
            </div>
        </div>
    </div>
</div>

