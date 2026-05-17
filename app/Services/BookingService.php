<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\ResourceBooking;
use App\Models\ResourceType;
use App\Models\Concert;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Book a specific concert ticket.
     */
    public function bookTicket(User $user, Concert $concert, float $pricePaid)
    {
        return DB::transaction(function () use ($user, $concert, $pricePaid) {
            return Ticket::create([
                'uuid' => (string) Str::uuid(),
                'concert_id' => $concert->id,
                'user_id' => $user->id,
                'reference' => 'TKT-' . strtoupper(Str::random(10)),
                'price_paid' => $pricePaid,
                'status' => 'confirmed'
            ]);
        });
    }

    /**
     * Book a resource (Stand, Ticket, etc) from the unified resource system.
     */
    public function bookResource(User $user, ResourceType $resourceType, array $details = [])
    {
        return DB::transaction(function () use ($user, $resourceType, $details) {
            if ($resourceType->available_quantity <= 0) {
                throw new \Exception("Désolé, cet article n'est plus disponible.");
            }

            $resourceable = $resourceType->resourceable;
            $booking = null;

            // If it's a ticket category for a concert, we create a record in the 'tickets' table
            // for compatibility with the QR code system and dedicated ticket view.
            if ($resourceType->category === 'ticket' && $resourceable instanceof Concert) {
                $booking = Ticket::create([
                    'uuid' => (string) Str::uuid(),
                    'concert_id' => $resourceable->id,
                    'user_id' => $user->id,
                    'reference' => 'TKT-' . strtoupper(Str::random(10)),
                    'price_paid' => $resourceType->price,
                    'status' => 'confirmed',
                    'qr_code_path' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . Str::random(12),
                ]);
            } else {
                // Otherwise use the generic ResourceBooking
                $booking = ResourceBooking::create([
                    'user_id' => $user->id,
                    'event_id' => $resourceable instanceof \App\Models\Event ? $resourceable->id : null,
                    'resource_type_id' => $resourceType->id,
                    'reference' => 'BK-' . strtoupper(Str::random(10)),
                    'total_price' => $resourceType->price,
                    'status' => 'confirmed',
                    'booking_details' => $details,
                    'confirmed_at' => now()
                ]);
            }

            $resourceType->decrement('available_quantity');

            return $booking;
        });
    }
}
