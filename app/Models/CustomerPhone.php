<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerPhone extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "customer_phones";
    protected $fillable = ["customer_id", "number", "name"];

    public function customer()
    {
        return $this->belongsTo(Customer::class, "id", "customer_id");
    }
}
