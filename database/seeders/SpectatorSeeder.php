<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Concert;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpectatorSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'yves@example.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Yves Spectateur',
                'email' => 'yves@example.com',
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('spectator');
        }

        $concerts = Concert::where('status', 'published')->get();

        foreach ($concerts as $index => $concert) {
            if ($index < 2) { // Seed 2 tickets
                Ticket::create([
                    'uuid' => Str::uuid(),
                    'concert_id' => $concert->id,
                    'user_id' => $user->id,
                    'reference' => 'EP-' . rand(100, 999) . '-' . strtoupper(Str::random(3)),
                    'price_paid' => 15000.00,
                    'status' => 'confirmed',
                    'qr_code_path' => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . Str::random(10),
                ]);
            }
        }
    }
}
