<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_orj.png') }}">
    <title>Kayıt Ol - Fırat Üniversitesi</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
</head>
<body class="font-body min-h-screen bg-slate-50 flex">

    <!-- Sol Panel (Dekoratif) -->
    <div class="hidden md:flex md:w-1/2 lg:w-[45%] bg-gradient-to-br from-primary to-primary-dark relative overflow-hidden flex-col items-center justify-center p-12">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-[-80px] right-[-80px] w-96 h-96 bg-white rounded-full"></div>
            <div class="absolute bottom-[-60px] left-[-60px] w-72 h-72 bg-white rounded-full"></div>
        </div>

        <div class="relative z-10 text-white max-w-sm w-full">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-[1.8rem] flex items-center justify-center mb-8 shadow-2xl border border-white/30">
                <span class="text-white font-black text-3xl font-headline">FÜ</span>
            </div>
            <h1 class="text-3xl font-black font-headline tracking-tight mb-3">Aramıza Katıl</h1>
            <p class="text-white/75 text-base leading-relaxed mb-10">Fırat Üniversitesi'nin kulüp ve etkinlik dünyasına adım at.</p>

            <!-- Steps -->
            <div class="space-y-5">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center shrink-0 border border-white/30">
                        <span class="text-white font-black text-sm">1</span>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Hesap oluştur</p>
                        <p class="text-white/60 text-xs mt-0.5">Birkaç saniyede kayıt ol</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center shrink-0 border border-white/30">
                        <span class="text-white font-black text-sm">2</span>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Kulüpleri keşfet</p>
                        <p class="text-white/60 text-xs mt-0.5">İlgi alanına göre kulüp bul</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center shrink-0 border border-white/30">
                        <span class="text-white font-black text-sm">3</span>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Etkinliklere katıl</p>
                        <p class="text-white/60 text-xs mt-0.5">Kampüs hayatını dolu yaşa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sağ Panel (Form) -->
    <div class="w-full md:w-1/2 lg:w-[55%] flex items-center justify-center p-6 sm:p-10 overflow-y-auto">
        <div class="w-full max-w-lg py-8">

            <!-- Mobil logo -->
            <div class="md:hidden text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 shadow-xl shadow-primary/30">
                    <span class="text-white font-black text-2xl font-headline">FÜ</span>
                </div>
                <h1 class="text-2xl font-black font-headline text-slate-900">Fırat Üniversitesi</h1>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-black font-headline text-slate-900 tracking-tight">Hesap Oluştur</h2>
                <p class="text-slate-500 text-sm mt-2">Bilgilerini doldur, hemen başla.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Ad Soyad -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Ad Soyad</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">person</span>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="Adın ve soyadın"
                            class="w-full bg-slate-50 border @error('name') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-2xl px-4 py-3.5 pl-12 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none"/>
                    </div>
                    @error('name')
                        <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">E-Posta Adresi</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">mail</span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            placeholder="ornek@firat.edu.tr"
                            class="w-full bg-slate-50 border @error('email') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-2xl px-4 py-3.5 pl-12 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none"/>
                    </div>
                    @error('email')
                        <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Şifre alanları yan yana (md+) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Şifre</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">lock</span>
                            <input id="password" type="password" name="password" required
                                placeholder="En az 8 karakter"
                                class="w-full bg-slate-50 border @error('password') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-2xl px-4 py-3.5 pl-12 pr-11 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none"/>
                            <button type="button" onclick="togglePassword('password', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Şifre Tekrar</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-[20px]">lock_reset</span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                placeholder="Şifreni tekrar gir"
                                class="w-full bg-slate-50 border @error('password_confirmation') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-2xl px-4 py-3.5 pl-12 pr-11 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none"/>
                            <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Şifre gücü göstergesi -->
                <div id="password-strength" class="hidden">
                    <div class="flex gap-1.5 mb-1">
                        <div class="h-1 flex-1 rounded-full bg-slate-200 strength-bar"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 strength-bar"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 strength-bar"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 strength-bar"></div>
                    </div>
                    <p id="strength-text" class="text-xs text-slate-400 ml-1"></p>
                </div>

                <!-- Kullanım koşulları -->
                <div class="flex items-start gap-3 ml-1">
                    <input id="terms" type="checkbox" required
                        class="mt-0.5 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer shrink-0"/>
                    <label for="terms" class="text-sm text-slate-600 cursor-pointer leading-relaxed">
                        <a href="#" class="font-bold text-primary hover:underline">Kullanım Koşulları</a>'nı ve
                        <a href="#" class="font-bold text-primary hover:underline">Gizlilik Politikası</a>'nı okudum, kabul ediyorum.
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-3.5 rounded-2xl font-black font-headline text-[15px] shadow-xl shadow-primary/20 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                    Hesap Oluştur
                </button>
            </form>

            <!-- Login link -->
            <p class="text-center text-sm text-slate-500 mt-6">
                Zaten hesabın var mı?
                <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary-dark transition-colors ml-1">Giriş yap</a>
            </p>

            <div class="text-center mt-6">
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

        // Şifre gücü göstergesi
        document.getElementById('password').addEventListener('input', function () {
            const val = this.value;
            const strengthEl = document.getElementById('password-strength');
            const bars = document.querySelectorAll('.strength-bar');
            const text = document.getElementById('strength-text');

            if (val.length === 0) { strengthEl.classList.add('hidden'); return; }
            strengthEl.classList.remove('hidden');

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
            const labels = ['Çok zayıf', 'Zayıf', 'Orta', 'Güçlü'];

            bars.forEach((bar, i) => {
                bar.className = 'h-1 flex-1 rounded-full ' + (i < score ? colors[score - 1] : 'bg-slate-200');
            });
            text.textContent = labels[score - 1] || '';
            text.className = 'text-xs ml-1 ' + (score <= 1 ? 'text-red-500' : score === 2 ? 'text-orange-500' : score === 3 ? 'text-yellow-600' : 'text-green-600');
        });
    </script>
</body>
</html>
