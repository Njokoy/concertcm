<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
     protected $fillable = ['title', 'body'];

    // ⭐ RELATION POLYMORPHE ⭐
    // Un Post peut avoir plusieurs commentaires
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
