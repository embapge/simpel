<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class VerificationEmail extends Model
{
    use HasFactory, BlameableTrait, HasUuids;

    protected $table = "verification_emails";
    protected $fillable = ["name", "address"];

    public function verification()
    {
        return $this->belongsTo(Verification::class, "verification_id", "id");
    }
}
