<?php

namespace App\Models;

use App\Casts\UangCast;
use Database\Factories\TransactionServiceFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionService extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "transaction_services";
    protected $fillable = ["transaction_id", "name", "description", "price"];

    protected static function newFactory(): Factory
    {
        return TransactionServiceFactory::new();
    }

    protected function casts()
    {
        return [
            "price" => UangCast::class,
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, "transaction_id", "id");
    }
}
