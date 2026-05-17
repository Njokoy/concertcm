<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'event_type', 'description', 'banner',
        'start_date', 'end_date', 'venue_id', 'organizer_id',
        'status', 'meta_data', 'label', 'is_verified', 'is_blocked', 'blocked_reason'
    ];

    protected $casts = [
        'meta_data' => 'json',
        'start_date' => 'date',
        'end_date' => 'date',
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

    public function resourceTypes()
    {
        return $this->morphMany(ResourceType::class, 'resourceable');
    }

    public function bookings()
    {
        return $this->hasMany(ResourceBooking::class);
    }
}
