<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'event_id', 'resource_type_id', 'reference',
        'total_price', 'status', 'booking_details', 'confirmed_at'
    ];

    protected $casts = [
        'booking_details' => 'json',
        'confirmed_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class);
    }
}
