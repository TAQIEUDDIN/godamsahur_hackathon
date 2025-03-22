<?php

use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PrayerViewController;
use Illuminate\Support\Facades\Route;


Route::get('/', function(){
    return view('welcome');
});

Route::get('/prayer-guide', function(){
    return view('prayerguide');
});


Route::get('/Place', [PlaceController::class, 'index'])->name('home');

Route::prefix('v1')->group(function () {
    Route::get('/search', [PlaceController::class, 'searchPlaces']);
    Route::get('/search/{type}', [PlaceController::class, 'searchByType'])
        ->where('type', 'restaurant|mosque|hotel');
    Route::get('/mapbox-search', [PlaceController::class, 'fetchFromMapbox']);
});

Route::get('/prayer-times', [PrayerViewController::class, 'index'])->name('prayer-times');



