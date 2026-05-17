<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'description', 'logo', 'website',
        'contact_email', 'contact_phone', 'social_links', 'is_verified', 'is_active'
    ];

    protected $casts = [
        'social_links' => 'json',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];
}
