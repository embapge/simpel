<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create("id_ID");

        foreach (Transaction::whereNotNull("number_display")->get() as $transaction) {
            $transaction->calculate();

            $transaction->update([
                "status" => "cancel"
            ]);

            $transaction->histories()->create([
                "date" => $transaction->created_at,
                "status" => "progress",
                "type" => "payment-created",
                "description" => "Transaksi dibatalkan",
                "created_by" => User::first()->id
            ]);

            if (rand(1, 9) % 2 == 0) {
                continue;
            }

            $transaction->histories->last()->delete();

            Invoice::factory()->hasServices(5)->count(1)->for($transaction)->create();
            $transaction->refresh();

            foreach ($transaction->invoices as $invoice) {
                foreach ($invoice->services as $service) {
                    $service->update([
                        "price" => round($transaction->total / 5)
                    ]);
                }
                $invoice->calculate();
                $invoice->refresh();

                $payment = $invoice->payment()->create([
                    "amount" => $invoice->total,
                    "issue_date" => $invoice->issue_date,
                    "due_date" => $invoice->due_date,
                    "created_by" => User::first()->id
                ]);

                $vaNumber = mt_rand(10000000000, 99999999999);

                $payment->transactions()->create([
                    "response" => '{"status_code":"201","status_message":"Success, Bank Transfer transaction is created","transaction_id":"' . Str::ulid() . '","order_id":"$payment->id","merchant_id":"G428130947","gross_amount":"' . $invoice->total . '","currency":"IDR","payment_type":"bank_transfer","transaction_time":"' . $invoice->issue_date . '","transaction_status":"pending","fraud_status":"accept","va_numbers":[{"bank":"bca","va_number":"' . $vaNumber . '"}],"expiry_time":"' . $invoice->due_date . '"}',
                    "created_by" => User::first()->id
                ]);

                $invoice->update([
                    "status" => "unpaid"
                ]);

                $payment->update([
                    "status" => "in process"
                ]);

                $transaction->histories()->create([
                    "date" => $invoice->issue_date,
                    "status" => "progress",
                    "type" => "payment-created",
                    "description" => "Menunggu pembayaran pelanggan dengan nomor BCA VA: {$vaNumber} dan nominal sebesar Rp. " . number_format($invoice->total, 0, ",", "."),
                    "created_by" => User::first()->id
                ]);

                if (rand(1, 9) % 2 == 0) {
                    $invoice->update([
                        "number_display" => $faker->bothify("######/SMPL/" . Str::upper($invoice->type) . "/####")
                    ]);

                    $paymentDate = Carbon::parse($invoice->issue_date)->addHours(3);

                    $payment->transactions()->create([
                        "response" => '{"currency":"IDR","order_id":"' . Str::ulid() . '","va_numbers":[{"bank":"bca","va_number":"' . $vaNumber . '"}],"expiry_time":"' . $paymentDate . '","merchant_id":"G428130947","status_code":"200","fraud_status":"accept","gross_amount":"' . $invoice->total . '","payment_type":"bank_transfer","signature_key":"519577f86e23fc8504d9615424833d7f0f52c5f8dd3ed8f6894f213872a292257ef9bfec21577477b1d18c34a7575cc4458f366277ac4803882c635bd51aa2c8","status_message":"midtrans payment notification","transaction_id":"' . $payment->id . '","payment_amounts":[],"settlement_time":"' . $paymentDate . '","transaction_time":"' . $invoice->issue_date . '","transaction_status":"settlement"}',
                        "created_by" => User::first()->id
                    ]);

                    $invoice->update([
                        "status" => "paid"
                    ]);
                    $payment->update([
                        "status" => "paid"
                    ]);

                    $transaction->histories()->create([
                        "date" => $paymentDate,
                        "status" => "progress",
                        "type" => "payment-created",
                        "description" => "Pembayaran berhasil dilakukan sebesar Rp. " . number_format($invoice->total, 0, ",", "."),
                        "created_by" => User::first()->id
                    ]);
                }

                $payment->refresh();

                $invoice->refresh();
                $invoice->calculate();

                $invoice->transaction->refresh();
                $invoice->transaction->calculate();
            }
        }
    }
}
