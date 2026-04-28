@extends('layouts.admin')

@section('title', __('site.profile_settings'))
@section('page_title', __('site.profile_settings'))

@section('content')
<div class="max-w-2xl space-y-6">

    @if(session('photo_success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ __('site.photo_updated') }}
    </div>
    @endif

    @if(session('status') === 'profile-updated')
    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ __('site.profile_updated') }}
    </div>
    @endif

    {{-- Profil Kartı --}}
    <div class="admin-card shadow-sm">

        {{-- Fotoğraf + İsim --}}
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 pb-8 border-b border-slate-100">
            <div class="relative shrink-0">
                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-primary/10 flex items-center justify-center shadow-sm">
                    @if($user->profile_photo)
                        @php
                            $editPhoto = $user->profile_photo;
                            $editPUrl = str_starts_with($editPhoto, 'http') ? $editPhoto : (file_exists(public_path('uploads/' . $editPhoto)) ? asset('uploads/' . $editPhoto) : asset('storage/' . $editPhoto));
                        @endphp
                        <img src="{{ $editPUrl }}"
                             id="photo-preview" class="w-full h-full object-cover" alt="">
                    @else
                        <div id="photo-placeholder" class="w-full h-full bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[40px]">account_circle</span>
                        </div>
                        <img src="" id="photo-preview" class="w-full h-full object-cover hidden" alt="">
                    @endif
                </div>
                <label for="photo-input" title="{{ __('site.change_photo') }}"
                       class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center cursor-pointer shadow-md hover:bg-[#8b1d35] transition-colors">
                    <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                </label>
                <form id="photo-form" action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="photo-input" name="profile_photo" accept="image/*" class="hidden">
                </form>
            </div>

            <div class="text-center sm:text-left">
                <p class="text-xl font-bold font-headline text-slate-800">{{ $user->full_name }}</p>
                <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wider rounded-full">
                    {{ $user->role?->label ?? 'Kullanıcı' }}
                </span>
            </div>
        </div>

        {{-- Ad Güncelleme Formu --}}
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf @method('PATCH')

            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">{{ __('site.full_name') }}</label>
                <input id="name" name="name" type="text"
                       value="{{ old('name', $user->full_name) }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                       required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">{{ __('site.email') }}</label>
                <div class="w-full bg-slate-100 border border-slate-200 rounded-xl text-sm px-4 py-3 text-slate-500 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-slate-400">lock</span>
                    {{ $user->email }}
                    <span class="ml-auto text-[10px] text-slate-400 font-medium">{{ __('site.email_managed_by_cas') }}</span>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-primary hover:bg-[#8b1d35] text-white rounded-xl font-bold text-sm transition-all shadow-sm active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">done</span>
                    {{ __('site.save') }}
                </button>
            </div>
        </form>

        {{-- CAS Bilgi Notu --}}
        <div class="mt-8 p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-start gap-3">
            <span class="material-symbols-outlined text-slate-400 text-[18px] shrink-0 mt-0.5">info</span>
            <p class="text-xs text-slate-500 leading-relaxed">
                {!! __('site.cas_admin_info') !!}
            </p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('photo-input').addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
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
