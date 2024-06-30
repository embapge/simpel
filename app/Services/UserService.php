<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function storeForCustomer(Customer $customer)
    {
        $user = User::create([
            "name" => $customer->name,
            "email" => $customer->email,
            'email_verified_at' => now(),
            "role" => "customer",
            'remember_token' => Str::random(10),
            "password" => Hash::make(Str::random(40)),
        ]);

        $customer->user()->attach($user->id);

        return $user;
    }
}
