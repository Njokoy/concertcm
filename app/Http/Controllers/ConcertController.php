<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\ConcertService;
use App\Models\Concert;
use App\Models\Venue;
use App\Models\Artist;

class ConcertController extends Controller
{
    protected $concertService;

    public function __construct(ConcertService $concertService)
    {
        $this->concertService = $concertService;
    }

    public function index(Request $request)
    {
        $query = Concert::where('is_blocked', false)->with('venue');

        if ($city = $request->get('city')) {
            $query->whereHas('venue', fn($q) => $q->where('city', 'like', '%'.$city.'%'));
        }
        if ($genre = $request->get('genre')) {
            $query->where('category', 'like', '%'.$genre.'%');
        }
        if ($date = $request->get('date')) {
            $query->whereDate('date', '>=', $date);
        } else {
            $query->whereDate('date', '>=', now()->toDateString());
        }

        $concerts = $query->orderByRaw("CASE WHEN label IS NOT NULL THEN 0 ELSE 1 END")
                          ->orderBy('date')->paginate(12)->withQueryString();

        $recentConcerts = Concert::where('is_blocked', false)
            ->whereDate('date', '>=', now()->toDateString())
            ->latest()->take(3)->get();

        return view('concerts.index', compact('concerts', 'recentConcerts'));
    }

    public function show($slug)
    {
        $concert = Concert::where('slug', $slug)
            ->where('is_blocked', false)
            ->with(['venue', 'artists', 'resourceTypes'])
            ->firstOrFail();
        return view('concerts.show', compact('concert'));
    }

    public function create()
    {
        $venues = Venue::all();
        $artists = Artist::all();
        return view('concerts.create', compact('venues', 'artists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'capacity' => 'required|integer',
            'venue_id' => 'required|exists:venues,id',
            'description' => 'nullable|string',
            'poster' => 'nullable|image|max:2048',
            'resources' => 'required|array|min:1',
            'resources.*.name' => 'required|string',
            'resources.*.category' => 'required|string|in:ticket,stand,booth',
            'resources.*.price' => 'required|numeric|min:0',
            'resources.*.quantity' => 'required|integer|min:1',
            'resources.*.image' => 'nullable|image|max:1024',
        ]);

        $validated['organizer_id'] = auth()->id();
        $validated['start_time'] = $request->start_time ?? '20:00:00';
        
        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster'] = '/storage/' . $path;
        }
        
        $concert = $this->concertService->createConcert($validated);

        foreach ($request->input('resources') as $index => $res) {
            $imagePath = null;
            if ($request->hasFile("resources.{$index}.image")) {
                $path = $request->file("resources.{$index}.image")->store('resources', 'public');
                $imagePath = '/storage/' . $path;
            }

            $concert->resourceTypes()->create([
                'name' => $res['name'],
                'category' => $res['category'],
                'price' => $res['price'],
                'total_quantity' => $res['quantity'],
                'available_quantity' => $res['quantity'],
                'image_path' => $imagePath,
                'is_active' => true,
            ]);
        }

        return redirect()->route('concerts.show', $concert->slug)->with('success', 'Événement et plans de stands enregistrés avec succès.');
    }

    public function edit(Concert $concert)
    {
        if ($concert->organizer_id !== auth()->id()) {
            abort(403);
        }
        $venues = \App\Models\Venue::all();
        $artists = \App\Models\Artist::all();
        // Optionnel : passer les resourceTypes existants à la vue
        return view('concerts.edit', compact('concert', 'venues', 'artists'));
    }

    public function update(Request $request, Concert $concert)
    {
        if ($concert->organizer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'capacity' => 'required|integer',
            'venue_id' => 'required|exists:venues,id',
            'description' => 'required|string',
            'poster' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('posters', 'public');
            $validated['poster'] = '/storage/' . $path;
        }
        
        $concert->update($validated);
        return redirect()->route('organizer.concerts.manage')->with('success', 'Concert modifié avec succès.');
    }

    public function manage()
    {
        $user = auth()->user();
        $concerts = Concert::where('organizer_id', $user->id)
            ->with('venue')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $events = \App\Models\Event::where('organizer_id', $user->id)
            ->with('venue')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('organizer.concerts.index', compact('concerts', 'events'));
    }
}
