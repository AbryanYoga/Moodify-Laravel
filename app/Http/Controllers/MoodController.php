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
        $tracks = [];

        if (Auth::check() && Auth::user()->spotify_token) {
            $token = Auth::user()->spotify_token;
            
            // Bersihkan whitespace/newline pada genre
            $cleanGenre = trim(strtolower($mood->genre));
            
            // Map ke query yang terbukti sukses di Spotify
            $genreMap = [
                'pop' => 'genre:pop',
                'acoustic' => 'genre:acoustic',
                'lofi' => 'lofi study',
                'instrumental' => 'genre:instrumental',
                'sad pop' => 'sad pop',
                'edm' => 'genre:edm'
            ];
            
            $searchQuery = isset($genreMap[$cleanGenre]) ? $genreMap[$cleanGenre] : $cleanGenre;
            $query = urlencode($searchQuery);

            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->get("https://api.spotify.com/v1/search?q={$query}&type=track&limit=12");

            if ($response->successful() && isset($response->json()['tracks']['items'])) {
                $tracks = $response->json()['tracks']['items'];
            } else {
                // Jika gagal (401 Unauthorized, 400 Bad Request, dll), kemungkinan token tidak valid/expired.
                // Logout paksa agar user login lagi dan mendapatkan token baru yang fresh.
                Auth::logout();
                return redirect('/auth/spotify')->with('error', 'Sesi Spotify tidak valid atau telah habis, silakan login kembali.');
            }
        }

        return view('mood.show', compact('mood', 'tracks'));
    }

    public function saveTrack(Request $request)
    {
        if (!Auth::check() || !Auth::user()->spotify_token) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $trackId = $request->track_id;
        $token = Auth::user()->spotify_token;

        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->put("https://api.spotify.com/v1/me/tracks?ids={$trackId}");

        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Lagu berhasil disimpan ke Spotify']);
        }

        return response()->json(['success' => false, 'message' => 'Gagal menyimpan lagu'], 400);
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
        ->scopes([
            'user-read-email',
            'user-read-private',
            'user-library-modify'
        ])
        ->redirect();
}

public function callbackSpotify()
{
    $spotifyUser = Socialite::driver('spotify')->user();

    $email = $spotifyUser->email
        ?? $spotifyUser->id . '@spotify.com';

    $user = User::updateOrCreate(
        [
            'email' => $email
        ],
        [
            'name' => $spotifyUser->name ?? 'Spotify User',
            'spotify_id' => $spotifyUser->id,
            'avatar' => $spotifyUser->avatar,
            'spotify_token' => $spotifyUser->token,
            'password' => bcrypt(\Illuminate\Support\Str::random(24))
        ]
    );

    \Illuminate\Support\Facades\Auth::login($user);

    return redirect('/mood');
}
}