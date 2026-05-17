<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceBooking;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class StandBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch all bookings that belong to resourceTypes associated with this organizer's events.
        $bookings = ResourceBooking::whereHas('resourceType', function($q) use ($user) {
            $q->whereHasMorph('resourceable', [Event::class, \App\Models\Concert::class], function($q2) use ($user) {
                $q2->where('organizer_id', $user->id);
            });
        })->with(['user', 'resourceType.resourceable'])->latest()->get();

        return view('organizer.stands.index', compact('bookings'));
    }

    public function updateStatus(Request $request, ResourceBooking $booking)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,pending',
        ]);

        // Security check
        $resourceable = $booking->resourceType->resourceable;
        if ($resourceable->organizer_id !== Auth::id()) {
            return back()->with('error', 'Non autorisé.');
        }

        $booking->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Statut de la demande mis à jour.');
    }
}
