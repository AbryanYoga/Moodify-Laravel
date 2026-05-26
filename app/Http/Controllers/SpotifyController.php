<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Mood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\SpotifyTokenService;
use Carbon\Carbon;

class SpotifyController extends Controller
{
    protected $tokenService;

    public function __construct(SpotifyTokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Redirect the user to the Spotify authentication page.
     */
    public function redirectSpotify()
    {
        return Socialite::driver('spotify')
            ->scopes([
                'user-read-email',
                'user-read-private',
            ])
            ->with(['show_dialog' => 'true']) // Force show authorization dialog
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

            $user = User::where('email', $email)->first();
            
            $dataToUpdate = [
                'name' => $spotifyUser->name ?? 'Spotify User',
                'spotify_id' => $spotifyUser->id,
                'avatar' => $spotifyUser->avatar,
                'spotify_token' => $spotifyUser->token,
                'spotify_token_expires_at' => Carbon::now()->addSeconds($spotifyUser->expiresIn ?? 3600),
            ];

            // Hanya timpa refresh_token jika ada dari Spotify (menghindari null)
            if (!empty($spotifyUser->refreshToken)) {
                $dataToUpdate['spotify_refresh_token'] = $spotifyUser->refreshToken;
            }

            if ($user) {
                $user->update($dataToUpdate);
            } else {
                $dataToUpdate['email'] = $email;
                $dataToUpdate['password'] = bcrypt(Str::random(24));
                $user = User::create($dataToUpdate);
            }

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
            $token = $this->tokenService->getValidToken(Auth::user());

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

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari Spotify API: ' . $response->body(),
                'status_code' => $response->status()
            ], $response->status());

        } catch (\Exception $e) {
            $isAuthError = $e->getCode() == 401;
            return response()->json([
                'success' => false,
                'message' => $isAuthError ? 'Spotify authorization expired' : 'Terjadi kesalahan pada server: ' . $e->getMessage(),
                'status_code' => $isAuthError ? 401 : 500
            ], $isAuthError ? 401 : 500);
        }
    }

    // Removed saveTrack logic as it is now handled by Local FavoriteController
}
