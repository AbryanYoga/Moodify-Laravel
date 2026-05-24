<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminMoodController extends Controller
{
    /**
     * Tampilkan Dashboard Admin
     */
    public function dashboard()
    {
        $totalMoods = Mood::count();
        $totalFavorites = Favorite::count();
        $totalUsers = User::count();
        $latestMood = Mood::latest()->first();
        $latestFavorite = Favorite::latest()->first();

        // Cari genre terpopuler (berdasarkan jumlah mood per genre)
        $popularGenre = Mood::select('genre')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('genre')
            ->orderByDesc('count')
            ->first();

        return view('admin.dashboard', compact(
            'totalMoods', 
            'totalFavorites', 
            'totalUsers',
            'latestMood', 
            'latestFavorite',
            'popularGenre'
        ));
    }

    /**
     * Tampilkan list data Mood (Read)
     */
    public function index(Request $request)
    {
        $query = Mood::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
        }

        // Filter Genre
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        $moods = $query->latest()->paginate(10)->withQueryString();
        $genres = Mood::select('genre')->distinct()->pluck('genre');

        return view('admin.moods.index', compact('moods', 'genres'));
    }

    /**
     * Tampilkan form Create Mood
     */
    public function create()
    {
        return view('admin.moods.create');
    }

    /**
     * Proses simpan data Mood baru (Create)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color_theme' => 'nullable|string|max:50',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan ke public/images/moods
            $file->move(public_path('images/moods'), $filename);
            $validated['image'] = $filename;
        }

        Mood::create($validated);

        return redirect()->route('admin.moods.index')->with('success', 'Mood berhasil ditambahkan!');
    }

    /**
     * Tampilkan form Edit Mood
     */
    public function edit(Mood $mood)
    {
        return view('admin.moods.edit', compact('mood'));
    }

    /**
     * Proses update data Mood (Update)
     */
    public function update(Request $request, Mood $mood)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color_theme' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($mood->image && File::exists(public_path('images/moods/' . $mood->image))) {
                File::delete(public_path('images/moods/' . $mood->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/moods'), $filename);
            $validated['image'] = $filename;
        }

        $mood->update($validated);

        return redirect()->route('admin.moods.index')->with('success', 'Mood berhasil diperbarui!');
    }

    /**
     * Proses hapus data Mood (Delete)
     */
    public function destroy(Mood $mood)
    {
        // Hapus gambar lama jika ada
        if ($mood->image && File::exists(public_path('images/moods/' . $mood->image))) {
            File::delete(public_path('images/moods/' . $mood->image));
        }

        $mood->delete();

        return redirect()->route('admin.moods.index')->with('success', 'Mood berhasil dihapus!');
    }
}
