<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'commentable_id', 'commentable_type', 'body'];

    // Relation vers l'auteur (User) - Relation classique
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ⭐ RELATION POLYMORPHE ⭐
    // Ce commentaire appartient à un Post OU une Video
    public function commentable()
    {
        return $this->morphTo();
    }
}
