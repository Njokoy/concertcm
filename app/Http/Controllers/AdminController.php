<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Concert;
use App\Models\Artist;

class AdminController extends Controller
{
    public function index()
    {
        // Handled by DashboardController@index for the main view
        // But we can add specific admin sub-pages here
    }

    public function manageEvents()
    {
        $events = Event::with('venue')->orderBy('created_at', 'desc')->paginate(10);
        $concerts = Concert::with('venue')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.events', compact('events', 'concerts'));
    }

    public function updateEventLabel(Request $request, $type, $id)
    {
        $validated = $request->validate([
            'label' => 'nullable|string',
            'is_verified' => 'boolean',
        ]);

        $model = ($type === 'concert') ? Concert::findOrFail($id) : Event::findOrFail($id);
        $model->update($validated);

        return back()->with('success', 'Événement mis à jour avec succès.');
    }

    public function verifyArtist(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'is_verified' => 'required|boolean',
            'verification_badge' => 'nullable|string',
        ]);

        $artist->update($validated);

        return back()->with('success', 'Artiste mis à jour avec succès.');
    }

    public function toggleEventBlock(Request $request, $type, $id)
    {
        $model = ($type === 'concert') ? Concert::findOrFail($id) : Event::findOrFail($id);
        
        $model->update([
            'is_blocked' => !$model->is_blocked,
            'blocked_reason' => $request->input('reason'),
        ]);

        $status = $model->is_blocked ? 'bloqué' : 'débloqué';
        return back()->with('success', "L'événement a été {$status} avec succès.");
    }

    public function manageUsers()
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.users', compact('users'));
    }
}
