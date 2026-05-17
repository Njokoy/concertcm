<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concert extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'poster', 'gallery', 'date',
        'start_time', 'end_time', 'timezone', 'venue_id', 'organizer_id',
        'category', 'tags', 'status', 'capacity', 'min_age', 'is_free',
        'featured', 'featured_until', 'views_count', 'tickets_sold',
        'revenue_total', 'avg_rating', 'cancelled_at', 'cancelled_reason',
        'published_at', 'label', 'is_verified', 'is_blocked', 'blocked_reason'
    ];

    protected $casts = [
        'gallery' => 'json',
        'tags' => 'json',
        'is_free' => 'boolean',
        'featured' => 'boolean',
        'date' => 'date',
        'featured_until' => 'datetime',
        'published_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'concert_artist')
            ->withPivot('is_headliner', 'order', 'fee', 'notes')
            ->withTimestamps()
            ->orderBy('is_headliner', 'desc')
            ->orderBy('order', 'asc');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function resourceTypes()
    {
        return $this->morphMany(ResourceType::class, 'resourceable');
    }
}
