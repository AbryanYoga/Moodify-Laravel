<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodLog extends Model
{
    protected $fillable = [
        'user_id',
        'mood_id',
        'tanggal'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mood()
    {
        return $this->belongsTo(Mood::class);
    }
}
