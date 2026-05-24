<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\SpotifyController;
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

    Route::post('/favorite', [MoodController::class, 'favorite']);
    Route::get('/favorite', [MoodController::class, 'favoriteList']);
    Route::delete('/favorite/{id}', [MoodController::class, 'deleteFavorite']);

    // Spotify actions
    Route::post('/spotify/save-track', [SpotifyController::class, 'saveTrack']);
    Route::get('/spotify/recommendation/{mood}', [SpotifyController::class, 'searchTracks']);
});

Route::get('/dashboard-mood', [MoodController::class, 'dashboard']);

require __DIR__.'/auth.php';