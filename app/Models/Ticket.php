<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'concert_id', 'user_id', 'reference', 'price_paid',
        'status', 'qr_code_path', 'pdf_path', 'used_at', 'scanned_by', 'cancelled_at'
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'price_paid' => 'decimal:2',
    ];

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
