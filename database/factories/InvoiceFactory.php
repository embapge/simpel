<?php

namespace Database\Factories;

use App\Models\Invoice;
use Faker\Factory as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Invoice::class;
    public function definition(): array
    {
        $faker = Faker::create("id_ID");
        $numberDisplay = $faker->randomElement([null, $faker->bothify("######/SMPL/INV/####")]);
        return [
            "number_display" => $numberDisplay,
            "type" => $faker->randomElement(["kw", "keu", "inv", "pro"]),
            "status" => $faker->randomElement(['draft', 'unpaid', 'paid', 'lesspaid', 'cancel']),
            "customer_name" => $faker->company(),
            "customer_pic_name" => $faker->name(),
            "issue_date" => $faker->dateTimeThisYear()
        ];
    }
}
