<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'spotify_track_id',
        'track_name',
        'artist_name',
        'album_image',
        'spotify_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}