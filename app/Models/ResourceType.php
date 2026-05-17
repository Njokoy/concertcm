<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'description', 'price',
        'total_quantity', 'available_quantity', 'attributes', 'is_active',
        'resourceable_id', 'resourceable_type'
    ];

    protected $casts = [
        'attributes' => 'json',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function resourceable()
    {
        return $this->morphTo();
    }

    public function bookings()
    {
        return $this->hasMany(ResourceBooking::class);
    }
}
