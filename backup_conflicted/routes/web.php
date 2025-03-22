<?php

<<<<<<< Updated upstream
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PrayerViewController;
use Illuminate\Support\Facades\Route;

=======
use App\Http\Controllers\ProfileController;
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
>>>>>>> Stashed changes

Route::get('/', function(){
    return view('welcome');
});

<<<<<<< Updated upstream
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



=======
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
>>>>>>> Stashed changes
