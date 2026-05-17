<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Concert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'concert_id' => 'required|exists:concerts,id',
        ]);

        $concert = Concert::findOrFail($validated['concert_id']);
        
        // Simulate payment/booking
        $ticket = Ticket::create([
            'uuid' => Str::uuid(),
            'concert_id' => $concert->id,
            'user_id' => Auth::id(),
            'reference' => 'EP-' . rand(100, 999) . '-' . strtoupper(Str::random(3)),
            'price_paid' => 10000.00, // Fixed mock price for simulation
            'status' => 'confirmed',
            'qr_code_path' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . Str::random(10), // Mock QR
        ]);

        return redirect()->route('tickets.show', $ticket->uuid)
            ->with('success', 'Billet réservé avec succès !');
    }

    public function show($uuid)
    {
        $ticket = Ticket::where('uuid', $uuid)->with('concert.venue')->firstOrFail();
        return view('tickets.confirmation', compact('ticket'));
    }

    public function cancel(Ticket $ticket)
    {
        // Restriction: Only up to 2 days before event
        $eventDate = $ticket->concert->date;
        $cancellationLimit = $eventDate->copy()->subDays(2);

        if (now()->isAfter($cancellationLimit)) {
            return back()->with('error', 'Annulation impossible moins de 2 jours avant l\'événement.');
        }

        $ticket->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Réservation annulée avec succès.');
    }

    public function verifyManual(Request $request)
    {
        $request->validate(['ticket_code' => 'required|string']);

        $ticket = Ticket::where('reference', $request->ticket_code)->with('concert')->first();

        if (!$ticket) {
            return back()->with('error', 'Billet introuvable avec ce code.');
        }

        if ($ticket->concert->organizer_id !== Auth::id()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à gérer ce billet.');
        }

        if ($ticket->status === 'used') {
            return back()->with('error', 'Attention: Ce billet a déjà été scanné le ' . $ticket->used_at->format('d/m/Y à H:i'));
        }

        if ($ticket->status !== 'confirmed') {
            return back()->with('error', 'Ce billet n\'est pas valide (Statut: ' . ucfirst($ticket->status) . ').');
        }

        $ticket->update([
            'status' => 'used',
            'used_at' => now(),
            'scanned_by' => Auth::id(),
        ]);

        return back()->with('success', "Billet valide ! (Ref: {$ticket->reference}) L'accès est accordé.");
    }
}
