<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Invoice extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "invoices";
    protected $fillable = ["transaction_id", "number_display", "type", "subtotal", "total", "total_bill", "total_payment", "excess_payment", "tax", "stamp", "customer_name", "customer_pic_name", "customer_address"];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, "transaction_id", "id");
    }
}
