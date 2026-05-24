<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    protected $fillable = [
        'nama', 
        'genre', 
        'image', 
        'description', 
        'color_theme'
    ];

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function logs()
    {
        return $this->hasMany(MoodLog::class);
    }
}