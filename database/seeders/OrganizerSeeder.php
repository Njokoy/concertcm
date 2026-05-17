<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Venue;
use App\Models\Concert;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrganizerSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('email', 'organisateur@concertcm.com')->first();
        if (!$organizer) {
            $organizer = User::create([
                'name' => 'John Organizer',
                'email' => 'organisateur@concertcm.com',
                'password' => bcrypt('password'),
                'city' => 'Douala',
            ]);
            $organizer->assignRole('organizer');
        }

        $venue = Venue::first() ?? Venue::create([
            'name' => 'Palais des Sports',
            'slug' => 'palais-des-sports',
            'address' => 'Warda',
            'city' => 'Yaoundé',
            'capacity' => 5000,
            'latitude' => 3.8667,
            'longitude' => 11.5167,
            'created_by' => $organizer->id,
        ]);

        Concert::create([
            'title' => 'Urban Vibes Douala',
            'slug' => 'urban-vibes-douala',
            'description' => 'Le plus grand concert urbain de Douala.',
            'date' => now()->addDays(10),
            'start_time' => '19:00',
            'venue_id' => $venue->id,
            'organizer_id' => $organizer->id,
            'status' => 'published',
            'capacity' => 2000,
            'tickets_sold' => 1450,
            'revenue_total' => 3600000,
        ]);

        Concert::create([
            'title' => 'Makossa Nights: Legend Edition',
            'slug' => 'makossa-nights',
            'description' => 'Une nuit de légende avec les icônes du Makossa.',
            'date' => now()->addDays(30),
            'start_time' => '20:00',
            'venue_id' => $venue->id,
            'organizer_id' => $organizer->id,
            'status' => 'draft',
            'capacity' => 1500,
            'tickets_sold' => 420,
            'revenue_total' => 8400000,
        ]);
    }
}
