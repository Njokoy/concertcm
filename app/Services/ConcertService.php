<?php

namespace App\Services;

use App\Models\Concert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ConcertService
{
    /**
     * Create a new concert.
     */
    public function createConcert(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
            return Concert::create($data);
        });
    }

    /**
     * Get upcoming concerts.
     */
    public function getUpcomingConcerts($limit = 10)
    {
        return Concert::where('status', 'published')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Cancel a concert.
     */
    public function cancelConcert(Concert $concert, string $reason)
    {
        return DB::transaction(function () use ($concert, $reason) {
            $concert->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_reason' => $reason
            ]);
            
            // Logic to notify ticket holders would go here
            
            return $concert;
        });
    }
}
