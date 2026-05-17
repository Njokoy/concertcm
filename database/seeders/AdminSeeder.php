<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@concertcm.com'],
            [
                'name' => 'ConcertCM Admin',
                'password' => Hash::make('password'),
                'theme' => 'dark',
            ]
        );

        $admin->assignRole('admin');
    }
}
