<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Venue;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::where('is_active', true)->get();
        return view('venues.index', compact('venues'));
    }

    public function show(Venue $venue)
    {
        return view('venues.show', compact('venue'));
    }
}
