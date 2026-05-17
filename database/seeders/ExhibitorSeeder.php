<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use App\Models\ResourceType;
use App\Models\ResourceBooking;
use Illuminate\Database\Seeder;

class ExhibitorSeeder extends Seeder
{
    public function run(): void
    {
        $exhibitor = User::where('email', 'exposant@concertcm.com')->first();
        if (!$exhibitor) {
            $exhibitor = User::create([
                'name' => 'Sara Expo',
                'email' => 'exposant@concertcm.com',
                'password' => bcrypt('password'),
                'city' => 'Yaoundé',
            ]);
            $exhibitor->assignRole('exhibitor');
        }

        $admin = User::first(); // Assuming admin or organizer created the event
        $venue = Venue::first();

        $event = Event::create([
            'title' => 'Foire de Noël 2024',
            'slug' => 'foire-noel-2024',
            'event_type' => 'foire',
            'description' => 'La grande foire annuelle.',
            'start_date' => now()->addMonths(1),
            'end_date' => now()->addMonths(1)->addDays(5),
            'venue_id' => $venue->id,
            'organizer_id' => $admin->id,
            'status' => 'published',
        ]);

        $standType = ResourceType::create([
            'event_id' => $event->id,
            'name' => 'Stand Standard (9m²)',
            'description' => 'Stand avec électricité et une table.',
            'category' => 'stand',
            'price' => 150000,
            'total_quantity' => 50,
            'available_quantity' => 49,
        ]);

        ResourceBooking::create([
            'user_id' => $exhibitor->id,
            'event_id' => $event->id,
            'resource_type_id' => $standType->id,
            'reference' => 'BK-' . strtoupper(Str::random(8)),
            'total_price' => 150000,
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }
}
