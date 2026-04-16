<!DOCTYPE html>
<html lang="tr" class="overflow-x-hidden">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_orj.png') }}">
    <title>Giriş Yap - Fırat Üniversitesi</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
</head>
<body class="font-body min-h-screen bg-slate-50 flex overflow-x-hidden">

    <!-- Mobile Header Inclusion -->
    <div class="md:hidden">
        @include('partials.header')
    </div>

    <!-- Sol Panel (Dekoratif) - Sadece md+ ekranlarda görünür -->
    <div class="hidden md:flex md:w-1/2 lg:w-[55%] bg-primary relative overflow-hidden flex-col items-center justify-center p-12">
        <!-- Arka plan dekorasyon -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div class="absolute top-[-80px] left-[-80px] w-96 h-96 bg-white rounded-full"></div>
            <div class="absolute bottom-[-60px] right-[-60px] w-72 h-72 bg-white rounded-full"></div>
            <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-white rounded-full"></div>
        </div>

        <div class="relative z-10 text-center text-white max-w-md">
            <!-- Logo -->
            <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-2xl border border-white/30">
                <span class="text-white font-black text-4xl font-headline">FÜ</span>
            </div>
            <h1 class="text-4xl font-black font-headline tracking-tight mb-4">Fırat Üniversitesi</h1>
            <p class="text-white/80 text-lg font-medium leading-relaxed mb-10">Kulüp ve Etkinlik Yönetim Platformuna hoş geldiniz.</p>

            <!-- Feature list -->
            <div class="space-y-4 text-left">
                <div class="flex items-center gap-3 bg-white/10 rounded-2xl px-5 py-3.5 backdrop-blur-sm border border-white/20">
                    <span class="material-symbols-outlined text-white/90 text-[22px]">event</span>
                    <span class="text-white/90 text-sm font-semibold">Etkinlikleri keşfet ve katıl</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-2xl px-5 py-3.5 backdrop-blur-sm border border-white/20">
                    <span class="material-symbols-outlined text-white/90 text-[22px]">groups</span>
                    <span class="text-white/90 text-sm font-semibold">Kulüplere üye ol</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-2xl px-5 py-3.5 backdrop-blur-sm border border-white/20">
                    <span class="material-symbols-outlined text-white/90 text-[22px]">campaign</span>
                    <span class="text-white/90 text-sm font-semibold">Duyurulardan haberdar ol</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sağ Panel (Form) -->
    <div class="w-full md:w-1/2 lg:w-[45%] flex items-center justify-center p-6 sm:p-10 pt-24 md:pt-10">
        <div class="w-full max-w-md">

            <!-- Mobil logo -->
            <div class="md:hidden text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 shadow-xl shadow-primary/30">
                    <span class="text-white font-black text-2xl font-headline">FÜ</span>
                </div>
                <h1 class="text-2xl font-black font-headline text-slate-900">Fırat Üniversitesi</h1>
                <p class="text-slate-500 text-sm mt-1">Kulüp & Etkinlik Platformu</p>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-black font-headline text-slate-900 tracking-tight">Tekrar hoş geldin</h2>
                <p class="text-slate-500 text-sm mt-2">Hesabına giriş yap ve devam et.</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-2xl text-sm text-green-700 font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">E-Posta Adresi</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">mail</span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="ornek@firat.edu.tr"
                            class="w-full bg-slate-50 border @error('email') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-2xl px-4 py-3.5 pl-12 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none"/>
                    </div>
                    @error('email')
                        <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2 ml-1">
                        <label for="password" class="text-sm font-bold text-slate-700">Şifre</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors">Şifremi unuttum</a>
                        @endif
                    </div>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">lock</span>
                        <input id="password" type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full bg-slate-50 border @error('password') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-2xl px-4 py-3.5 pl-12 pr-12 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none"/>
                        <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-3 ml-1">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4.5 h-4.5 rounded-lg border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                    <label for="remember_me" class="text-sm font-semibold text-slate-600 cursor-pointer select-none">Beni hatırla</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-3.5 rounded-2xl font-black font-headline text-[15px] shadow-xl shadow-primary/20 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">login</span>
                    Giriş Yap
                </button>
            </form>

            <!-- Register link -->
            <p class="text-center text-sm text-slate-500 mt-6">
                Hesabın yok mu?
                <a href="{{ route('register') }}" class="font-bold text-primary hover:text-primary-dark transition-colors ml-1">Kayıt ol</a>
            </p>

            <!-- Ana sayfa -->
            <div class="text-center mt-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-slate-700 transition-colors group">
                    <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('.material-symbols-outlined');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</body>
</html>
