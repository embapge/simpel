<?php

namespace App\Models;

use Database\Factories\TransactionDocumentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionDocument extends Pivot
{
    use HasFactory, BlameableTrait;

    protected $table = "transaction_documents";
    protected $fillable = ["transaction_id", "document_id", "date", "file", "created_by", "updated_by"];

    protected static function newFactory(): Factory
    {
        return TransactionDocumentFactory::new();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function document()
    {
        return $this->belongsTo(Transaction::class);
    }
}
