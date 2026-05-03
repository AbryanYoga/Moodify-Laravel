<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodController;

Route::middleware('auth')->group(function () {

    Route::get('/mood', [MoodController::class, 'index'])->name('mood.index');

    Route::get('/mood/{id}', [MoodController::class, 'show'])->name('mood.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/spotify/login', [MoodController::class,'spotifyLogin']);
    Route::get('/callback', [MoodController::class,'spotifyCallback']);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';