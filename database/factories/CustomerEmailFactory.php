<?php

namespace Database\Factories;

use App\Models\CustomerEmail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerEmail>
 */
class CustomerEmailFactory extends Factory
{
    protected $model = CustomerEmail::class;

    public function definition(): array
    {
        $faker = Faker::create("id_ID");
        return [
            "address" => $faker->safeEmail(),
            "name" => $faker->name(),
            "created_by" => User::first()->id
        ];
    }
}
