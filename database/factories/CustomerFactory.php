<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;
    public function definition(): array
    {
        $faker = Faker::create("id_ID");
        // protected $fillable = ["name", "pic_name", "group", "type", "established", "website"];
        return [
            "name" => $faker->company(),
            "pic_name" => $faker->name(),
            "group" => $faker->companySuffix(),
            "type" => $faker->randomElement(["homeEquipment", "garment", "stationary", "food", "shipping"]),
            "established" => $faker->dateTimeBetween(),
            "website" => $faker->url(),
        ];
    }
}
