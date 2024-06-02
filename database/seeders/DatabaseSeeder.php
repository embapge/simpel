<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            'name' => "SIMPEL",
            'email' => "mohammadbarata.mb@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make('simpel'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
            "role" => "admin"
        ]);

        $this->call([
            CustomerSeeder::class,
            DocumentSeeder::class,
            TransactionTypeSeeder::class
        ]);
    }
}
