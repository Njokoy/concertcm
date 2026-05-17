<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['followedArtists', 'tickets.concert.venue']);
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'preferences' => 'nullable|array',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = '/storage/' . $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour !');
    }

    public function toggleFollow(Artist $artist)
    {
        $user = Auth::user();
        
        if ($user->followedArtists()->where('artist_id', $artist->id)->exists()) {
            $user->followedArtists()->detach($artist->id);
            return response()->json(['status' => 'unfollowed']);
        } else {
            $user->followedArtists()->attach($artist->id);
            return response()->json(['status' => 'followed']);
        }
    }
}
