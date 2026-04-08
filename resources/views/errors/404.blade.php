<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Sayfa Bulunamadı | Fırat Üniversitesi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        <div class="mb-8">
            <img src="{{ asset('images/logo_orj.png') }}" alt="Fırat Üniversitesi Logo" class="h-24 mx-auto mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-primary/10 rounded-full mb-6">
                <span class="text-primary text-4xl font-bold">404</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 mb-4">Aps! Sayfa Bulunamadı</h1>
            <p class="text-slate-600 mb-8 leading-relaxed">
                Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir. Lütfen ana sayfaya dönerek tekrar deneyin.
            </p>
        </div>
        
        <div class="flex flex-col gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-primary hover:bg-primary-dim text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg active:scale-95 text-sm">
                Ana Sayfaya Dön
            </a>
            <a href="javascript:history.back()" class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-600 font-bold py-3 px-6 rounded-2xl hover:bg-slate-50 transition-all text-sm">
                Önceki Sayfaya Git
            </a>
        </div>
        
        <div class="mt-12 text-slate-400 text-xs font-medium uppercase tracking-widest">
            Fırat Üniversitesi &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
