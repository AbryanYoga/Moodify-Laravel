<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Mood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SpotifyController extends Controller
{
    /**
     * Redirect the user to the Spotify authentication page.
     */
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

    /**
     * Obtain the user information from Spotify.
     */
    public function callbackSpotify()
    {
        try {
            $spotifyUser = Socialite::driver('spotify')->user();

            $email = $spotifyUser->email ?? $spotifyUser->id . '@spotify.com';

            $user = User::updateOrCreate(
                [
                    'email' => $email
                ],
                [
                    'name' => $spotifyUser->name ?? 'Spotify User',
                    'spotify_id' => $spotifyUser->id,
                    'avatar' => $spotifyUser->avatar,
                    'spotify_token' => $spotifyUser->token,
                    'password' => bcrypt(Str::random(24))
                ]
            );

            Auth::login($user);

            return redirect('/mood')->with('success', 'Berhasil login dengan Spotify!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Gagal login dengan Spotify.');
        }
    }

    /**
     * Search tracks from Spotify API based on mood genre.
     */
    public function searchTracks(Request $request, $moodId)
    {
        if (!Auth::check() || !Auth::user()->spotify_token) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized. Harap login ke Spotify.',
                'status_code' => 401
            ], 401);
        }

        try {
            $mood = Mood::findOrFail($moodId);
            $token = Auth::user()->spotify_token;

            // Bersihkan whitespace/newline pada genre
            $cleanGenre = trim(strtolower($mood->genre));
            
            // Map genre ke query yang lebih akurat sesuai instruksi
            $genreMap = [
                'sad pop' => 'sad pop',
                'heartbreak' => 'heartbreak',
                'emotional' => 'emotional',
                'edm' => 'genre:edm',
                'workout' => 'workout',
                'energy boost' => 'energy',
                'chill' => 'chill',
                'acoustic' => 'genre:acoustic',
                'relaxing' => 'relaxing',
                'lofi' => 'lofi study',
                'instrumental' => 'genre:instrumental',
                'deep focus' => 'deep focus',
                'happy hits' => 'happy hits',
                'feel good' => 'feel good',
                'upbeat pop' => 'upbeat pop',
                'pop' => 'genre:pop' // Fallback mapping jika ada
            ];
            
            $searchQuery = isset($genreMap[$cleanGenre]) ? $genreMap[$cleanGenre] : $cleanGenre;
            
            // Query params (limit 10 sesuai instruksi)
            $query = urlencode($searchQuery);
            $limit = 10;
            
            $response = Http::withToken($token)
                ->get("https://api.spotify.com/v1/search?q={$query}&type=track&limit={$limit}");

            if ($response->successful()) {
                $tracks = $response->json()['tracks']['items'] ?? [];
                
                return response()->json([
                    'success' => true,
                    'data' => $tracks
                ]);
            }

            // Jika error 401 (Token Expired/Invalid)
            if ($response->status() == 401) {
                Auth::user()->update(['spotify_token' => null]); // Invalidate token
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi Spotify telah habis. Silakan login kembali.',
                    'status_code' => 401
                ], 401);
            }

            // Error lain dari Spotify
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari Spotify API.',
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * Save a track to the user's Spotify library.
     */
    public function saveTrack(Request $request)
    {
        if (!Auth::check() || !Auth::user()->spotify_token) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'track_id' => 'required|string'
        ]);

        $trackId = $request->track_id;
        $token = Auth::user()->spotify_token;

        try {
            $response = Http::withToken($token)
                ->put("https://api.spotify.com/v1/me/tracks?ids={$trackId}");

            if ($response->successful()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Lagu berhasil disimpan!'
                ]);
            }

            if ($response->status() == 401) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Sesi Spotify telah habis. Silakan login kembali.',
                    'status_code' => 401
                ], 401);
            }

            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan lagu ke Spotify.'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }
}
