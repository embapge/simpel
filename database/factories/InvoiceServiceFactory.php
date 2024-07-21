<?php

namespace Database\Factories;

use App\Models\InvoiceService;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceService>
 */
class InvoiceServiceFactory extends Factory
{
    protected $model = InvoiceService::class;
    public function definition(): array
    {
        $faker = Faker::create("id_ID");
        return [
            "name" => $faker->bothify("??????????"),
            "description" => $faker->randomElement([null, $faker->text(50)]),
            // "price" => $faker->bothify("##########"),
            "price" => 0,
            "created_by" => User::first()->id
        ];
    }
}
