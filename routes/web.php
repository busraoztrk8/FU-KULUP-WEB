<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KayitController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ClubController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HaberController;
use App\Http\Controllers\Admin\DuyuruController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes
Route::get('/etkinlikler', function () {
    $events = \App\Models\Event::with(['category', 'club'])
        ->where('status', 'published')
        ->orderBy('start_time', 'asc')
        ->get();
        
    return view('etkinlikler', compact('events'));
})->name('etkinlikler');

Route::get('/tum-etkinlikler', function () {
    $events = \App\Models\Event::with(['category', 'club'])
        ->where('status', 'published')
        ->orderBy('start_time', 'desc')
        ->paginate(12);
        
    return view('tum-etkinlikler', compact('events'));
})->name('tum-etkinlikler');

Route::get('/kulupler', function () {
    $clubs = \App\Models\Club::with('category')
        ->where('is_active', true)
        ->latest()
        ->get();
    $categories = \App\Models\Category::all();
    return view('kulupler', compact('clubs', 'categories'));
})->name('kulupler');

Route::get('/etkinlikler/{slug}', function ($slug) {
    $event = \App\Models\Event::with(['club', 'category'])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    $similar = \App\Models\Event::with(['club', 'category'])
        ->where('status', 'published')
        ->where('id', '!=', $event->id)
        ->where('category_id', $event->category_id)
        ->latest()
        ->take(3)
        ->get();

    return view('etkinlik-detay', compact('event', 'similar'));
})->name('etkinlik.detay');

Route::get('/kulupler/{slug}', function ($slug) {
    $club = \App\Models\Club::with(['category', 'president', 'events' => function($q) {
        $q->where('status', 'published')->latest()->take(4);
    }])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    return view('kulup-detay', compact('club'));
})->name('kulup.detay');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kulüp kayıt
    Route::post('/kulupler/{club}/kayit', [KayitController::class, 'kulupKayit'])->name('kulup.kayit');
    Route::delete('/kulupler/{club}/ayril', [KayitController::class, 'kulupAyril'])->name('kulup.ayril');

    // Etkinlik kayıt
    Route::post('/etkinlikler/{event}/kayit', [KayitController::class, 'etkinlikKayit'])->name('etkinlik.kayit');
    Route::delete('/etkinlikler/{event}/iptal', [KayitController::class, 'etkinlikIptal'])->name('etkinlik.iptal');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,editor'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggle-status');

    // Events
    Route::get('/etkinlikler', [EventController::class, 'index'])->name('etkinlikler');
    Route::post('/etkinlikler', [EventController::class, 'store'])->name('etkinlikler.store');
    Route::put('/etkinlikler/{event}', [EventController::class, 'update'])->name('etkinlikler.update');
    Route::delete('/etkinlikler/{event}', [EventController::class, 'destroy'])->name('etkinlikler.destroy');

    // Clubs
    Route::get('/kulupler', [ClubController::class, 'index'])->name('kulupler');
    Route::post('/kulupler', [ClubController::class, 'store'])->name('kulupler.store');
    Route::put('/kulupler/{club}', [ClubController::class, 'update'])->name('kulupler.update');
    Route::delete('/kulupler/{club}', [ClubController::class, 'destroy'])->name('kulupler.destroy');

    // Kulüp Üyelik Yönetimi
    Route::get('/kulupler/{club}/uyeler', [ClubController::class, 'members'])->name('kulupler.uyeler');
    Route::post('/kulup-uyelik/{member}/onayla', [ClubController::class, 'approveMember'])->name('kulupler.uyeler.onayla');
    Route::post('/kulup-uyelik/{member}/reddet', [ClubController::class, 'rejectMember'])->name('kulupler.uyeler.reddet');
    Route::delete('/kulup-uyelik/{member}', [ClubController::class, 'removeMember'])->name('kulupler.uyeler.sil');

    // Categories
    Route::get('/kategoriler', [CategoryController::class, 'index'])->name('kategoriler');
    Route::post('/kategoriler', [CategoryController::class, 'store'])->name('kategoriler.store');
    Route::put('/kategoriler/{category}', [CategoryController::class, 'update'])->name('kategoriler.update');
    Route::delete('/kategoriler/{category}', [CategoryController::class, 'destroy'])->name('kategoriler.destroy');

    // Users
    Route::get('/kullanicilar', [UserController::class, 'index'])->name('kullanicilar');
    Route::post('/kullanicilar', [UserController::class, 'store'])->name('kullanicilar.store');
    Route::put('/kullanicilar/{user}', [UserController::class, 'update'])->name('kullanicilar.update');
    Route::delete('/kullanicilar/{user}', [UserController::class, 'destroy'])->name('kullanicilar.destroy');

    // Settings & Reports (static views)
    Route::get('/ayarlar', fn() => view('admin.ayarlar'))->name('ayarlar');
    Route::get('/raporlar', fn() => view('admin.raporlar'))->name('raporlar');

    // Haberler
    Route::get('/haberler', [HaberController::class, 'index'])->name('haberler');
    Route::post('/haberler', [HaberController::class, 'store'])->name('haberler.store');
    Route::put('/haberler/{news}', [HaberController::class, 'update'])->name('haberler.update');
    Route::delete('/haberler/{news}', [HaberController::class, 'destroy'])->name('haberler.destroy');

    // Duyurular
    Route::get('/duyurular', [DuyuruController::class, 'index'])->name('duyurular');
    Route::post('/duyurular', [DuyuruController::class, 'store'])->name('duyurular.store');
    Route::put('/duyurular/{announcement}', [DuyuruController::class, 'update'])->name('duyurular.update');
    Route::delete('/duyurular/{announcement}', [DuyuruController::class, 'destroy'])->name('duyurular.destroy');

    // Slider
    Route::get('/slider', [SliderController::class, 'index'])->name('slider');
    Route::post('/slider', [SliderController::class, 'store'])->name('slider.store');
    Route::put('/slider/{slider}', [SliderController::class, 'update'])->name('slider.update');
    Route::delete('/slider/{slider}', [SliderController::class, 'destroy'])->name('slider.destroy');
    Route::post('/slider/reorder', [SliderController::class, 'reorder'])->name('slider.reorder');

    // Menü Yönetimi
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
    Route::patch('/menu/{menu}/toggle', [MenuController::class, 'toggle'])->name('menu.toggle');

    // Galeri Yönetimi
    Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery');
    Route::post('/galeri', [GalleryController::class, 'store'])->name('gallery.store');
    Route::put('/galeri/{gallery}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/galeri/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
});

require __DIR__.'/auth.php';
