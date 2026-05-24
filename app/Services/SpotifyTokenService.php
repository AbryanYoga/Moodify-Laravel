<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SpotifyTokenService
{
    /**
     * Get a valid Spotify token for the user, refreshing if necessary.
     *
     * @param \App\Models\User $user
     * @return string|null
     * @throws \Exception
     */
    public function getValidToken($user)
    {
        if (!$user->spotify_token) {
            throw new \Exception('No Spotify token found for user.', 401);
        }

        // Check if token is expired or expires in less than 5 minutes
        if (!$user->spotify_token_expires_at || Carbon::now()->addMinutes(5)->greaterThanOrEqualTo($user->spotify_token_expires_at)) {
            Log::info('Spotify token expired or expiring soon, attempting refresh for user: ' . $user->id);
            return $this->refreshToken($user);
        }

        // If we don't have expires_at but have token (legacy/first time), we assume it's valid 
        // but if API returns 401 later, controller will catch it.
        return $user->spotify_token;
    }

    /**
     * Force refresh the Spotify token.
     *
     * @param \App\Models\User $user
     * @return string
     * @throws \Exception
     */
    public function refreshToken($user)
    {
        if (!$user->spotify_refresh_token) {
            Log::error('No Spotify refresh token available for user: ' . $user->id);
            $user->update(['spotify_token' => null, 'spotify_token_expires_at' => null]);
            throw new \Exception('No Spotify refresh token available. User must re-authenticate.', 401);
        }

        try {
            Log::info('Sending refresh token request to Spotify for user: ' . $user->id);
            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Basic ' . base64_encode(config('services.spotify.client_id') . ':' . config('services.spotify.client_secret'))
            ])->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $user->spotify_refresh_token,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $newToken = $data['access_token'];
                // Usually Spotify token expires in 3600 seconds (1 hour)
                $expiresIn = $data['expires_in'] ?? 3600;
                
                $user->update([
                    'spotify_token' => $newToken,
                    'spotify_refresh_token' => $data['refresh_token'] ?? $user->spotify_refresh_token, // Sometimes refresh token is not rotated
                    'spotify_token_expires_at' => Carbon::now()->addSeconds($expiresIn)
                ]);

                return $newToken;
            }

            // If refresh fails (e.g., token revoked)
            Log::error('Spotify token refresh failed', ['response' => $response->body()]);
            $user->update(['spotify_token' => null, 'spotify_token_expires_at' => null]);
            throw new \Exception('Failed to refresh Spotify token. ' . $response->body(), 401);

        } catch (\Exception $e) {
            // Invalidate on hard failure
            $user->update(['spotify_token' => null, 'spotify_token_expires_at' => null]);
            throw new \Exception('Error refreshing Spotify token: ' . $e->getMessage(), 401);
        }
    }
}
