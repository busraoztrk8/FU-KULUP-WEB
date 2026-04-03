<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Girişi - Fırat Üniversitesi</title>
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
</head>
<body class="bg-slate-50 font-body min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md animate-fade-in-up">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-primary rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-primary/30 transform rotate-12 hover:rotate-0 transition-transform duration-500">
                <span class="text-white font-black text-3xl font-headline -rotate-12 hover:rotate-0 transition-transform duration-500">FÜ</span>
            </div>
            <h1 class="text-3xl font-headline font-black text-slate-900 tracking-tight">Erişim Paneli</h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">Yönetim merkezine hoş geldiniz.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.05)]">
            <form action="{{ route('admin.index') }}" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2.5 ml-1">Kurumsal E-Posta</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">mail</span>
                        <input type="email" placeholder="admin@firat.edu.tr" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-4 pl-12 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none"/>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2.5 ml-1">
                        <label class="block text-sm font-bold text-slate-700">Güvenlik Şifresi</label>
                        <a href="#" class="text-primary text-xs font-bold hover:text-primary-dark transition-colors">Şifremi Unuttum</a>
                    </div>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">lock</span>
                        <input type="password" placeholder="••••••••" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-4 pl-12 text-sm focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none"/>
                    </div>
                </div>

                <div class="flex items-center gap-3 ml-1">
                    <input type="checkbox" id="remember" class="w-5 h-5 rounded-lg border-slate-300 text-primary focus:ring-primary/20 transition-all cursor-pointer"/>
                    <label for="remember" class="text-sm font-bold text-slate-600 cursor-pointer select-none">Oturumu Açık Tut</label>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-4 rounded-2xl font-black font-headline text-[16px] shadow-xl shadow-primary/20 active:scale-[0.97] transition-all duration-200">
                    Sisteme Giriş Yap
                </button>
            </form>
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-slate-800 transition-all group">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                Ana Sayfaya Dön
            </a>
        </div>
    </div>

</body>
</html>
