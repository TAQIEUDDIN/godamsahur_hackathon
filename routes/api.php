<?php

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\Api\PrayerTimeController;
use App\Http\Controllers\Api\LocationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // Search endpoints
    Route::get('/search', [PlaceController::class, 'searchPlaces']);
    Route::get('/search/{type}', [PlaceController::class, 'searchByType'])
        ->where('type', 'restaurant|mosque|hotel');
    Route::get('/mapbox-search', [PlaceController::class, 'fetchFromMapbox']);

    // Prayer Times route
    Route::get('/prayer-times', [PrayerTimeController::class, 'getPrayerTimes']);

    // Location endpoints
    Route::get('/locations/search', [LocationController::class, 'search']);
    Route::get('/places/nearby', [PlaceController::class, 'nearby']);
    Route::get('/places/{id}', [PlaceController::class, 'details']);

    // Qibla route
    Route::get('/qibla', [QiblaController::class, 'getQiblaDirection']);
});

