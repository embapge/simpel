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
    protected $fillable = ["name", "pic_name", "address", "email", "verify_at", "phone_number", "website", "transaction_sub_type_id", "status", "link"];

    public function emails()
    {
        return $this->hasMany(VerificationEmail::class, "verification_id", "id");
    }

    public function phones()
    {
        return $this->hasMany(VerificationPhone::class, "verification_id", "id");
    }

    public function documents()
    {
        return $this->hasMany(VerificationDocument::class, "verification_id", "id");
    }

    public function subType()
    {
        return $this->belongsTo(TransactionSubType::class, "transaction_sub_type_id", "id");
    }
}
