<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function store(string $name, string $email, string $role = "customer")
    {
        $user = User::create([
            "name" => $name,
            "email" => $email,
            'email_verified_at' => now(),
            "role" => $role,
            'remember_token' => Str::random(10),
            "password" => Hash::make(Str::random(40)),
        ]);

        return $user;
    }
}
