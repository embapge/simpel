<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class PaymentGateaway extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "payment_gateaways";
    protected $fillable = ["payment_id", "transaction_id", "token", "date", "status"];
}
