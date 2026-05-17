<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\EventService;
use App\Models\Event;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request)
    {
        $type = $request->query('type', 'fair');
        $events = $this->eventService->getEventsByType($type);
        
        $recentEvents = \App\Models\Event::where('event_type', $type)
            ->where('is_blocked', false)
            ->where('end_date', '>=', now()->toDateString())
            ->latest()->take(3)->get();
            
        return view('events.index', compact('events', 'type', 'recentEvents'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)
            ->where('is_blocked', false)
            ->with(['venue', 'resourceTypes'])
            ->firstOrFail();
        return view('events.show', compact('event'));
    }

    public function create()
    {
        $venues = \App\Models\Venue::all();
        return view('events.create', compact('venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|string',
            'start_date' => 'required|date',
            'venue_id' => 'required|exists:venues,id',
            'description' => 'required|string',
            'banner_image' => 'nullable|image|max:2048',
            'resources' => 'required|array|min:1',
            'resources.*.name' => 'required|string',
            'resources.*.category' => 'required|string|in:ticket,stand,booth',
            'resources.*.price' => 'required|numeric|min:0',
            'resources.*.quantity' => 'required|integer|min:1',
            'resources.*.image' => 'nullable|image|max:1024',
        ]);

        $validated['organizer_id'] = auth()->id();
        $validated['end_date'] = $request->input('end_date', $validated['start_date']);
        
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('banners', 'public');
            $validated['banner_image'] = '/storage/' . $path;
        }
        
        $event = $this->eventService->createEvent($validated);

        foreach ($request->input('resources') as $index => $res) {
            $imagePath = null;
            if ($request->hasFile("resources.{$index}.image")) {
                $path = $request->file("resources.{$index}.image")->store('resources', 'public');
                $imagePath = '/storage/' . $path;
            }

            $event->resourceTypes()->create([
                'name' => $res['name'],
                'category' => $res['category'],
                'price' => $res['price'],
                'total_quantity' => $res['quantity'],
                'available_quantity' => $res['quantity'],
                'image_path' => $imagePath,
                'is_active' => true,
            ]);
        }

        return redirect()->route('events.show', $event->slug)->with('success', 'Foire/Salon créé avec succès avec ses espaces.');
    }

    public function edit(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }
        $venues = \App\Models\Venue::all();
        return view('events.edit', compact('event', 'venues'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|string',
            'start_date' => 'required|date',
            'venue_id' => 'required|exists:venues,id',
            'description' => 'required|string',
            'banner_image' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('banners', 'public');
            $validated['banner_image'] = '/storage/' . $path;
        }
        
        $validated['end_date'] = $request->input('end_date', $validated['start_date']);
        $event->update($validated);
        
        return redirect()->route('organizer.concerts.manage')->with('success', 'Événement modifié avec succès.');
    }
}
