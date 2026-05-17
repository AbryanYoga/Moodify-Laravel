<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'spotify_id',
    'spotify_token',
    'avatar'
])]

#[Hidden([
    'password',
    'remember_token'
])]
/**
 * @property string $email
 * @property string $name
 * @property string $spotify_id
 * @property string $avatar
 * @property string $spotify_token
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function moodLogs()
    {
        return $this->hasMany(MoodLog::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}