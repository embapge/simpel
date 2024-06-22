<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class VerificationDocument extends Model
{
    use HasFactory, HasUuids, BlameableTrait;
    protected $table = "verification_documents";
    protected $fillable = ["verification_id", "document_id", "date", "file", "is_verified"];

    public function verification()
    {
        return $this->belongsTo(Verification::class, "verification_id", "id");
    }

    public function document()
    {
        return $this->belongsTo(Document::class, "document_id", "id");
    }
}
