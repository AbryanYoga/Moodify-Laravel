<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoodController extends Controller
{
    public function index()
    {
        $moods = Mood::all();
        return view('mood.index', compact('moods'));
    }

    public function show($id)
    {
        $mood = Mood::findOrFail($id);
        return view('mood.show', compact('mood'));
    }

    public function spotifyLogin()
    {
        $clientId = env('SPOTIFY_CLIENT_ID');
        $redirect = urlencode(env('SPOTIFY_REDIRECT_URI'));
        $scope = 'playlist-read-private';

        $url = "https://accounts.spotify.com/authorize?response_type=code&client_id={$clientId}&scope=playlist-read-private&redirect_uri={$redirect}";

        return redirect($url);
    }

    public function spotifyCallback(Request $request)
    {
        $code = $request->code;

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        ]);

        dd($response->json());
    }
}