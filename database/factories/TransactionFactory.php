<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\TransactionSubType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;
    public function definition(): array
    {
        $faker = Faker::create("id_ID");
        $numberDisplay = $faker->randomElement([null, $faker->bothify("######/SMPL/TRNSCTN/####")]);
        return [
            "number_display" => $numberDisplay,
            "transaction_sub_type_id" => $faker->randomElement(TransactionSubType::all()->pluck("id")->toArray()),
            "status" => "draft",
            "created_at" => $faker->dateTimeThisYear(),
            "created_by" => User::first()->id
        ];
    }
}
