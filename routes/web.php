<?php

use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PrayerViewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function(){
    return view('welcome');
});

Route::get('/prayer-guide', function(){
    return view('prayerguide');
});


Route::get('/Place', [PlaceController::class, 'index'])->name('place');

Route::prefix('v1')->group(function () {
    Route::get('/search', [PlaceController::class, 'searchPlaces']);
    Route::get('/search/{type}', [PlaceController::class, 'searchByType'])
        ->where('type', 'restaurant|mosque|hotel');
    Route::get('/mapbox-search', [PlaceController::class, 'fetchFromMapbox']);
});

Route::get('/prayer-times', [PrayerViewController::class, 'index'])->name('prayer-times');




Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('reviews', ReviewController::class);
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
