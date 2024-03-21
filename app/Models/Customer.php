<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Customer extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "customers";
    protected $fillable = ["name", "pic_name", "group", "type", "established", "website"];

    protected static function newFactory(): Factory
    {
        return CustomerFactory::new();
    }

    public function emails()
    {
        return $this->hasMany(CustomerEmail::class, "customer_id", "id");
    }

    public function phones()
    {
        return $this->hasMany(CustomerPhone::class, "customer_id", "id");
    }
}
