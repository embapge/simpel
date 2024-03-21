<?php

namespace App\Models;

use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class Document extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "documents";
    protected $fillable = ["name", "is_active", "description"];

    protected static function newFactory(): Factory
    {
        return DocumentFactory::new();
    }
}
