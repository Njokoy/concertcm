<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use App\Models\Artist;

class HomeController extends Controller
{
    public function index()
    {
        // Hero: first featured/verified concert
        $heroEvent = Concert::where('status', 'published')
            ->where(function ($q) {
                $q->where('is_verified', true)->orWhereNotNull('label');
            })
            ->with('venue')
            ->latest()
            ->first()
            ?? Concert::where('status', 'published')->with('venue')->latest()->first();

        // Featured concerts: up to 6 published
        $concerts = Concert::where('status', 'published')
            ->with('venue')
            ->orderByRaw("CASE WHEN label IS NOT NULL THEN 0 ELSE 1 END")
            ->orderBy('date')
            ->limit(6)
            ->get();

        // Popular artists
        $artists = Artist::where('is_active', true)
            ->orderBy('followers_count', 'desc')
            ->limit(6)
            ->get();

        return view('home', compact('heroEvent', 'concerts', 'artists'));
    }
}
