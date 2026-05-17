<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'bio', 'photo', 'genre', 'genres', 'city', 'country',
        'instagram', 'facebook', 'youtube', 'spotify', 'soundcloud', 'website',
        'followers_count', 'concerts_count', 'avg_rating', 'is_verified', 'is_active', 'verification_badge'
    ];

    protected $casts = [
        'genres' => 'json',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'avg_rating' => 'decimal:2',
    ];

    public function concerts()
    {
        return $this->belongsToMany(Concert::class, 'concert_artist')->withPivot('is_headliner', 'order', 'fee', 'notes')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows')->withTimestamps();
    }
}
