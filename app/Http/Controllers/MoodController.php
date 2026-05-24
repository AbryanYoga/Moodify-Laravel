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

        return view('mood.show', compact('mood'));
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


}