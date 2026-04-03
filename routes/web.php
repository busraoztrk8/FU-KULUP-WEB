<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ClubController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HaberController;
use App\Http\Controllers\Admin\DuyuruController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public routes
Route::get('/etkinlikler', function () {
    return view('etkinlikler');
})->name('etkinlikler');

Route::get('/kulupler', function () {
    return view('kulupler');
})->name('kulupler');

Route::get('/etkinlikler/{slug}', function ($slug) {
    return view('etkinlik-detay');
})->name('etkinlik.detay');

Route::get('/kulupler/{slug}', function ($slug) {
    return view('kulup-detay');
})->name('kulup.detay');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

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

    // Duyurular
    Route::get('/duyurular', [DuyuruController::class, 'index'])->name('duyurular');

    // Slider
    Route::get('/slider', [SliderController::class, 'index'])->name('slider');
    Route::post('/slider', [SliderController::class, 'store'])->name('slider.store');
    Route::delete('/slider/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');

    // Menü Yönetimi
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
});

require __DIR__.'/auth.php';
