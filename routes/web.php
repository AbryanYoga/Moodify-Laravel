<?php

use App\Http\Controllers\ProfileController;
use App\Models\Favorite;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodController;

/*
|--------------------------------------------------------------------------
| Route Mood
|--------------------------------------------------------------------------
*/

Route::get('/mood', [MoodController::class, 'index'])
    ->name('mood.index');

Route::get('/mood/{id}', [MoodController::class, 'show'])
    ->name('mood.show');

Route::get('/spotify/login', [MoodController::class, 'spotifyLogin']);

Route::get('/callback', [MoodController::class, 'spotifyCallback']);

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
| Route Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/favorite', [MoodController::class, 'favorite']);

    Route::get('/favorite', [MoodController::class, 'favoriteList']);
});

Route::post('/favorite',[MoodController::class, 'favorite']);
Route::get('/favorite', [MoodController::class, 'favoriteList']);

Route::delete('/favorite/{id}',
    [MoodController::class, 'deleteFavorite']);
require __DIR__.'/auth.php';

Route::get('/dashboard-mood', [MoodController::class, 'dashboard']);

Route::get('/auth/spotify', [MoodController::class, 'redirectSpotify']);

Route::get('/auth/spotify/callback', [MoodController::class, 'callbackSpotify']);