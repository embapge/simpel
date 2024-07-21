<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Document;
use App\Models\Transaction;
use App\Models\TransactionService;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Customer::all() as $customer) {
            Transaction::factory()->has(TransactionService::factory()->count(5), "services")->hasDocuments(4)->count(3)->for($customer)->create();
        }

        foreach (Transaction::all() as $transaction) {
            $transaction->histories()->create([
                "date" => $transaction->created_at,
                "status" => "progress",
                "type" => "transaction-process",
                "description" => "Transaksi sedang di proses admin",
                "created_by" => User::first()->id
            ]);

            $transaction->refresh();
            $transaction->calculate();
        }
    }
}
