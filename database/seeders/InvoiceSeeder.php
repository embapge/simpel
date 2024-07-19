<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Transaction::all() as $transaction) {
            Invoice::factory()->count(3)->for($transaction)->create();
            $transaction->refresh();

            foreach ($transaction->invoices as $invoice) {
                $invoice->calculate();
            }
        }
    }
}
