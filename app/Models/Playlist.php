<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'mood_id',
        'judul',
        'deskripsi',
        'spotify_link'
    ];

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }

    public function favorites() 
    {
        return $this->hasMany(Favorite::class);    
    }
}