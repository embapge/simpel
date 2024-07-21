<?php

namespace Database\Factories;

use App\Models\CustomerPhone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerPhone>
 */
class CustomerPhoneFactory extends Factory
{
    protected $model = CustomerPhone::class;
    public function definition(): array
    {
        $faker = Faker::create("id_ID");
        return [
            "number" => $faker->phoneNumber(),
            "name" => $faker->name(),
            "created_by" => User::first()->id
        ];
    }
}
