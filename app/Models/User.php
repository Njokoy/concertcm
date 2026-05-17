<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'city',
        'preferences',
        'theme',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'array',
    ];

    public function followedArtists()
    {
        return $this->belongsToMany(Artist::class, 'follows');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id'); // Assuming user_id exists in tickets or linked via a booking
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
