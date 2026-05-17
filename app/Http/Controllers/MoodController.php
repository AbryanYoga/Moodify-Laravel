<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Mood;
use Illuminate\Http\Request;
use App\Models\Favorite;

class MoodController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $moods = Mood::when($search, function ($query) use ($search) {

            $query->where('nama', 'like', "%{$search}%");

        })->get();

        return view('mood.index',
            compact('moods', 'search'));
    }

    public function show($id)
    {
        $mood = Mood::findOrFail($id);

        $playlists = [];

        if ($mood->genre == 'pop') {

            $playlists = [
                'Happy Hits',
                'Pop Rising',
                'Mood Booster'
            ];

        } elseif ($mood->genre == 'acoustic') {

            $playlists = [
                'Sad Acoustic',
                'Acoustic Chill',
                'Late Night Songs'
            ];

        } elseif ($mood->genre == 'lofi') {

            $playlists = [
                'Lofi Study',
                'Chill Beats',
                'Deep Focus'
            ];

        } elseif ($mood->genre == 'instrumental') {

            $playlists = [
                'Focus Flow',
                'Deep Concentration',
                'Study Session'
            ];

        } elseif ($mood->genre == 'sad pop') {

            $playlists = [
                'Broken Heart',
                'Sad Vibes',
                'Midnight Tears'
            ];

        } elseif ($mood->genre == 'edm') {

            $playlists = [
                'EDM Party',
                'Workout Energy',
                'Festival Hits'
            ];

        } else {

            $playlists = [
                'Daily Mix',
                'Random Playlist'
            ];
        }

        return view('mood.show',
            compact('mood', 'playlists'));
    }

    public function favorite(Request $request)
    {
        Favorite::create([
            'playlist' => $request->playlist,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()
            ->with(
                'success',
                'Playlist berhasil ditambahkan ke favorite'
            );
    }

    public function favoritelist()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('mood.favorite', compact('favorites'));
    }

    public function deleteFavorite($id)
    {
        $favorite = Favorite::findOrFail($id);

        $favorite->delete();

        return redirect()->back()
            ->with(
                'success',
                'Favorite berhasil dihapus'
            );
    }

    public function dashboard()
    {
        $totalMood = Mood::count();

        $totalFavorite = Favorite::count();

        $latestFavorites = Favorite::latest()
            ->take(5)
            ->get();

        $genres = Mood::selectRaw('genre, COUNT(*) as total')
            ->groupBy('genre')
            ->get();

        return view('mood.dashboard', compact(
            'totalMood',
            'totalFavorite',
            'latestFavorites',
            'genres'
        ));
    }

    public function redirectSpotify()
    {
        return Socialite::driver('spotify')
            ->stateless()
            ->redirect();
    }

    public function callbackSpotify()
    {
        $spotifyUser = Socialite::driver('spotify')
            ->stateless()
            ->user();

        dd($spotifyUser);
    }
}