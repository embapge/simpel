<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Verification extends Model
{
    use HasFactory, HasUuids, BlameableTrait;
    protected $table = "verifications";
    protected $fillable = ["name", "pic_name", "address", "email", "verify_at", "phone_number", "website", "transaction_sub_type_id"];
}
