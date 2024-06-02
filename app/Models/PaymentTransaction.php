<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class PaymentTransaction extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "payment_transactions";
    protected $fillable = ["payment_id", "response"];

    public function payment()
    {
        return $this->belongsTo(Payment::class, "payment_id", "id");
    }
}
