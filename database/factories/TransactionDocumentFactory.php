<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Transaction;
use App\Models\TransactionDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionDocument>
 */
class TransactionDocumentFactory extends Factory
{
    protected $model = TransactionDocument::class;
    public function definition(): array
    {
        return [
            "transaction_id" => Transaction::factory(),
            "document_id" => Document::inRandomOrder()->first()
        ];
    }
}
