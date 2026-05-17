<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EventService
{
    /**
     * Create a new generic event (Fair, Cultural, etc).
     */
    public function createEvent(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
            return Event::create($data);
        });
    }

    /**
     * Get active events by type.
     */
    public function getEventsByType(string $type, $limit = 10)
    {
        return Event::where('event_type', $type)
            ->where('is_blocked', false)
            ->where('end_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Update event status.
     */
    public function updateEventStatus(Event $event, string $status)
    {
        $event->update(['status' => $status]);
        return $event;
    }
}
