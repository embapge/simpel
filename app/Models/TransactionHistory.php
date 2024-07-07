<?php

namespace App\Models;

use Database\Factories\TransactionHistoryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionHistory extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "transaction_histories";
    protected $fillable = ["transaction_id", "date", "type", "status", "description"];

    protected static function newFactory(): Factory
    {
        return TransactionHistoryFactory::new();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, "transaction_id", "id");
    }
}
