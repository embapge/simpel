<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;
    public function definition(): array
    {
        $faker = Faker::create("id_ID");
        $numberDisplay = $faker->randomElement([null, $faker->bothify("######/SMPL/INV/####")]);
        return [
            "number_display" => $numberDisplay,
            "type" => "document",
            "sub_type" => $faker->randomElement(["kk", "ppmkk", "ppkk", "kp", "rbsgmk"]),
            "status" => "draft"
        ];
    }
}
