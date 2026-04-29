@extends('layouts.app')

@section('title', __('site.profile_title'))

@section('content')
<div class="min-h-[60vh] bg-surface-bright py-12 md:py-20 px-4 sm:px-6">
    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb style info -->
        <div class="flex items-center gap-2 text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-6 opacity-60">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">{{ __('site.profile_breadcrumb_home') }}</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span>{{ __('site.profile_breadcrumb') }}</span>
        </div>

        <!-- Personal Info Card -->
        <div class="bg-white rounded-[2rem] p-8 md:p-12 border border-black/5 shadow-sm overflow-hidden relative group">
            <!-- Decorative background element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[4rem] group-hover:scale-110 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row items-center gap-6 md:gap-10 mb-10">
                    {{-- Profil Fotoğrafı --}}
                    <div class="relative group/photo shrink-0">
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-[2rem] overflow-hidden shadow-xl shadow-primary/20">
                            @if($user->profile_photo)
                                @php
                                    $pPhoto = $user->profile_photo;
                                    $pUrl = str_starts_with($pPhoto, 'http') ? $pPhoto : (file_exists(public_path('uploads/' . $pPhoto)) ? asset('uploads/' . $pPhoto) : asset('storage/' . $pPhoto));
                                @endphp
                                <img src="{{ $pUrl }}" id="photo-preview"
                                     class="w-full h-full object-cover" alt="">
                            @else
                                <div id="photo-placeholder" class="w-full h-full bg-gradient-to-br from-primary to-[#8b1d35] flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-4xl md:text-5xl">person</span>
                                </div>
                                <img src="" id="photo-preview" class="w-full h-full object-cover hidden" alt="">
                            @endif
                        </div>
                        {{-- Fotoğraf değiştir butonu --}}
                        <label for="photo-input" class="absolute -bottom-2 -right-2 w-9 h-9 bg-primary text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-[#8b1d35] transition-colors" title="{{ __('site.change_photo') }}">
                            <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                        </label>
                    </div>

                    <div class="text-center md:text-left">
                        <div class="inline-block px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-tighter rounded-full mb-3">
                            {{ __('site.role_' . (auth()->user()->role ? auth()->user()->role->name : 'student')) }}
                        </div>
                        <h1 class="text-3xl md:text-4xl font-headline font-extrabold text-on-surface mb-1 tracking-tight">{{ $user->full_name }}</h1>
                        <p class="text-on-surface-variant font-medium opacity-70">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- Gizli fotoğraf yükleme formu --}}
                @if(session('photo_success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl text-green-700 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ __('site.photo_updated') }}
                </div>
                @endif
                <form id="photo-form" action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="photo-input" name="profile_photo" accept="image/*" class="hidden">
                </form>

                <div class="pt-10 border-t border-black/5">
                    <!-- Dynamic Clubs List -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-primary mb-4">
                            <span class="material-symbols-outlined text-[22px]">groups</span>
                            <span class="text-sm font-bold uppercase tracking-widest">{{ __('site.my_clubs_label') }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($user->clubMemberships as $membership)
                                <div class="flex items-center justify-between p-4 bg-surface-bright rounded-2xl border border-black/5 group/item hover:border-primary/20 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary border border-black/5">
                                            <span class="material-symbols-outlined text-[20px]">hub</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">{{ app()->getLocale() == 'en' && $membership->club->name_en ? $membership->club->name_en : $membership->club->name }}</p>
                                            <p class="text-[10px] text-on-surface-variant opacity-60">{{ __('site.application_date', ['date' => $membership->created_at->format('d.m.Y')]) }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 {{ $membership->status === 'approved' ? 'bg-green-500/10 text-green-600' : 'bg-amber-500/10 text-amber-600' }} text-[10px] font-bold rounded-lg uppercase tracking-tight">
                                        {{ $membership->status === 'approved' ? __('site.member_status') : __('site.pending_status') }}
                                    </span>
                                </div>
                            @empty
                                <div class="md:col-span-2 p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-sm text-slate-500 mb-4">{{ __('site.no_club_membership') }}</p>
                                    <a href="{{ route('kulupler') }}" class="inline-flex items-center gap-2 text-primary text-sm font-bold hover:underline">
                                        {{ __('site.discover_clubs_link') }} <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Info Message -->
                <div class="mt-12 flex gap-4 p-5 bg-primary/5 rounded-2xl border border-primary/10">
                    <span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">info</span>
                    <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed font-medium">
                        {!! __('site.cas_info_message') !!}
                    </p>
                </div>

                <!-- Logout Button -->
                <div class="mt-8 flex justify-center md:justify-start">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-red-500 font-bold hover:text-red-700 transition-colors text-sm px-4 py-2 hover:bg-red-50 rounded-xl">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            {{ __('site.safe_logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('photo-input').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photo-preview');
            const placeholder = document.getElementById('photo-placeholder');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(this.files[0]);
        document.getElementById('photo-form').submit();
    }
});
</script>
@endpush
