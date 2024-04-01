<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class BankAccount extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "bank_accounts";
    protected $fillable = ["bank_id", "name", "account_number"];

    public function bank()
    {
        return $this->belongsTo(Bank::class, "bank_id", "id");
    }
}
