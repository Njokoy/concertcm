<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard($user);
        }

        if ($user->hasRole('organizer')) {
            return $this->organizerDashboard($user);
        }

        if ($user->hasRole('exhibitor')) {
            return $this->exhibitorDashboard($user);
        }

        return $this->spectatorDashboard($user);
    }

    protected function adminDashboard($user)
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_concerts' => \App\Models\Concert::count(),
            'total_events' => \App\Models\Event::count(),
            'total_revenue' => \App\Models\Ticket::where('status', 'confirmed')->sum('price_paid'),
            'pending_concerts' => \App\Models\Concert::where('status', 'draft')->count(),
            'recent_users' => \App\Models\User::orderBy('created_at', 'desc')->limit(5)->get(),
        ];

        return view('dashboards.admin', compact('stats'));
    }

    protected function spectatorDashboard($user)
    {
        $tickets = \App\Models\Ticket::where('user_id', $user->id)
            ->with(['concert.venue'])
            ->orderBy('created_at', 'desc')
            ->get();

        $bookings = \App\Models\ResourceBooking::where('user_id', $user->id)
            ->with(['resourceType.resourceable'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSpent = $tickets->where('status', 'confirmed')->sum('price_paid') 
                    + $bookings->whereIn('status', ['confirmed', 'pending'])->sum('total_price');

        // Split into upcoming vs past
        $validTickets = $tickets->filter(function($t) {
            return in_array($t->status, ['confirmed', 'pending']) && \Carbon\Carbon::parse($t->concert->date)->isFuture();
        });
        $pastTickets = $tickets->diff($validTickets);

        $activeBookings = $bookings->filter(function($b) {
            $resourceable = $b->resourceType->resourceable;
            if ($resourceable instanceof \App\Models\Concert) {
                return \Carbon\Carbon::parse($resourceable->date)->isFuture();
            }
            if ($resourceable instanceof \App\Models\Event) {
                return \Carbon\Carbon::parse($resourceable->end_date ?? $resourceable->start_date)->isFuture();
            }
            return true;
        });
        $pastBookings = $bookings->diff($activeBookings);

        $stats = [
            'total_spent' => $totalSpent,
            'valid_tickets' => $validTickets->count(),
            'total_stands' => $bookings->count(),
        ];

        // Simple recommendation logic
        $recommendations = clone \App\Models\Concert::where('is_blocked', false)
            ->whereDate('date', '>=', now())
            ->limit(3)
            ->get();

        return view('dashboards.spectator', compact(
            'user', 'validTickets', 'pastTickets', 'activeBookings', 'pastBookings', 'stats', 'recommendations'
        ));
    }

    protected function organizerDashboard($user)
    {
        $concerts = Concert::where('organizer_id', $user->id)->with('venue')->get();
        $events = Event::where('organizer_id', $user->id)->get();
        
        $concertIds = $concerts->pluck('id');
        $eventIds = $events->pluck('id');

        $realRevenueConcerts = \App\Models\Ticket::whereIn('concert_id', $concertIds)
            ->where('status', 'confirmed')
            ->sum('price_paid');

        // Revenue from Foires (ResourceBookings) would require checking the polymorphic ResourceType.
        // For simplicity, assuming ResourceBooking holds total price.
        $realRevenueEvents = \App\Models\ResourceBooking::whereHas('resourceType', function ($query) use ($eventIds, $user) {
            $query->where('resourceable_type', Event::class)
                  ->whereIn('resourceable_id', $eventIds);
        })->where('status', 'confirmed')->sum('total_price');

        $ticketsSoldReal = \App\Models\Ticket::whereIn('concert_id', $concertIds)->where('status', 'confirmed')->count();

        $stats = [
            'total_revenue' => $realRevenueConcerts + $realRevenueEvents,
            'tickets_sold' => $ticketsSoldReal,
            'total_capacity' => $concerts->sum('capacity'),
            'active_events' => $concerts->where('status', 'published')->count() + $events->count(),
        ];

        return view('dashboards.organizer', compact('stats', 'concerts', 'events'));
    }

    public function concertStats($id)
    {
        $concert = Concert::where('id', $id)->where('organizer_id', Auth::id())->with('resourceTypes')->firstOrFail();
        return view('dashboards.organizer_stats', ['model' => $concert, 'type' => 'Concert']);
    }

    public function eventStats($id)
    {
        $event = Event::where('id', $id)->where('organizer_id', Auth::id())->with('resourceTypes')->firstOrFail();
        return view('dashboards.organizer_stats', ['model' => $event, 'type' => 'Événement']);
    }

    protected function exhibitorDashboard($user)
    {
        $bookings = \App\Models\ResourceBooking::where('user_id', $user->id)
            ->with(['event.venue', 'resourceType'])
            ->get();

        return view('dashboards.exhibitor', compact('bookings'));
    }

    public function toggleTheme(Request $request)
    {
        $user = Auth::user();
        $user->theme = $user->theme === 'dark' ? 'light' : 'dark';
        $user->save();

        return response()->json(['theme' => $user->theme]);
    }
}
