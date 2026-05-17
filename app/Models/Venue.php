<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'address', 'city', 'district', 'country',
        'capacity', 'latitude', 'longitude', 'description',
        'amenities', 'photos', 'website', 'phone', 'is_active', 'created_by'
    ];

    protected $casts = [
        'amenities' => 'json',
        'photos' => 'json',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function concerts()
    {
        return $this->hasMany(Concert::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
