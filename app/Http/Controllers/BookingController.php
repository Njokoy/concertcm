<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\BookingService;
use App\Models\Concert;
use App\Models\ResourceType;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function bookTicket(Request $request, Concert $concert)
    {
        // Simple booking logic (Mobile Money integration for CinetPay would happen in Service)
        $booking = $this->bookingService->bookTicket(auth()->user(), $concert, $request->amount);

        return response()->json(['status' => 'success', 'booking' => $booking]);
    }

    public function bookResource(Request $request, ResourceType $resourceType)
    {
        try {
            $booking = $this->bookingService->bookResource(auth()->user(), $resourceType, $request->details ?? []);
            return response()->json(['status' => 'success', 'booking' => $booking]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_type_id' => 'required|exists:resource_types,id',
            'details' => 'nullable|array',
        ]);
        
        $resourceType = ResourceType::findOrFail($request->resource_type_id);
        
        try {
            $booking = $this->bookingService->bookResource(auth()->user(), $resourceType, $request->details ?? []);
            
            // Redirect based on what was created
            if ($booking instanceof \App\Models\Ticket) {
                return redirect()->route('tickets.show', $booking->uuid)
                    ->with('success', 'Billet réservé avec succès !');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Réservation de stand effectuée avec succès ! Retrouvez-la dans votre dashboard.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur de réservation : ' . $e->getMessage());
        }
    }
}
