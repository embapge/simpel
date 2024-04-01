<?php

namespace App\Models;

use Database\Factories\CustomerEmailFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class CustomerEmail extends Model
{
    use HasFactory, HasUuids, BlameableTrait;
    protected $table = "customer_emails";
    protected $fillable = ["customer_id", "address", "name", "verify_at"];

    protected static function newFactory(): Factory
    {
        return CustomerEmailFactory::new();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, "id", "customer_id");
    }
}
