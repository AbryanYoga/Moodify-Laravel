<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Tampilkan halaman list favorite
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()->latest()->get();
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Simpan lagu ke favorite
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'spotify_track_id' => 'required|string',
            'track_name' => 'required|string',
            'artist_name' => 'required|string',
            'album_image' => 'nullable|string',
            'spotify_url' => 'nullable|string',
        ]);

        // Cegah duplikasi
        $exists = Auth::user()->favorites()->where('spotify_track_id', $validated['spotify_track_id'])->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Lagu sudah ada di daftar favorite Anda.'
            ], 400);
        }

        Auth::user()->favorites()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lagu berhasil disimpan ke favorite!'
        ]);
    }

    /**
     * Hapus lagu dari favorite
     */
    public function destroy($id)
    {
        $favorite = Auth::user()->favorites()->findOrFail($id);
        $favorite->delete();

        return redirect()->back()->with('success', 'Lagu berhasil dihapus dari favorite.');
    }
}
