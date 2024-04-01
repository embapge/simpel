<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Bank extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "banks";
    protected $fillable = ["name", "code"];

    public function accounts()
    {
        return $this->hasMany(BankAccount::class, "bank_id", "id");
    }
}
