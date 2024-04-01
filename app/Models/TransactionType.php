<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class TransactionType extends Model
{
    use HasFactory, HasUuids, BlameableTrait;
    protected $table = "transaction_types";
    protected $fillable = ["name", "description"];

    public function subTypes()
    {
        return $this->hasMany(TransactionSubType::class, "transaction_type_id", "id");
    }
}
