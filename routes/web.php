<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminMoodController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Default
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Route Mood
|--------------------------------------------------------------------------
*/

Route::get('/mood', [MoodController::class, 'index'])->name('mood.index');
Route::get('/mood/{id}', [MoodController::class, 'show'])->name('mood.show');

/*
|--------------------------------------------------------------------------
| Route Spotify Auth
|--------------------------------------------------------------------------
*/

Route::get('/auth/spotify', [SpotifyController::class, 'redirectSpotify']);
Route::get('/auth/spotify/callback', [SpotifyController::class, 'callbackSpotify']);

/*
|--------------------------------------------------------------------------
| Route Profile & Auth Required
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Favorite (Local)
    Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite.index');
    Route::post('/favorite/save', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/favorite/{id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');

    // Spotify actions
    Route::get('/spotify/recommendation/{mood}', [SpotifyController::class, 'searchTracks']);
});

Route::get('/dashboard-mood', [MoodController::class, 'dashboard']);

/*
|--------------------------------------------------------------------------
| Route Admin CRUD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminMoodController::class, 'dashboard'])->name('dashboard');
    Route::resource('moods', AdminMoodController::class);
});

require __DIR__.'/auth.php';