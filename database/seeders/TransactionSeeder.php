<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\TransactionService;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Customer::all() as $customer) {
            Transaction::factory()->has(TransactionService::factory()->count(5), "services")->hasDocuments(10)->count(3)->for($customer)->create();
        }
    }
}
