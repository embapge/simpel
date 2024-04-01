<?php

namespace Database\Factories;

use App\Models\TransactionService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class TransactionServiceFactory extends Factory
{
    protected $model = TransactionService::class;
    public function definition(): array
    {
        $faker = Faker::create("id_ID");
        return [
            "name" => $faker->bothify("??????????"),
            "description" => $faker->randomElement([null, $faker->text(50)]),
            "price" => $faker->bothify("##########"),
        ];
    }
}
