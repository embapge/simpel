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

    public function transactionTemplates()
    {
        return $this->belongsToMany(TransactionDocumentTemplate::class, "transaction_document_template_details", "document_id", "transaction_document_template_id")->withPivot(["transaction_document_template_id", "transaction_sub_type_id", "document_id", "is_required"])->using(TransactionDocumentTemplateDetail::class);
    }

    public function transactionSubTypeTemplates()
    {
        return $this->belongsToMany(TransactionSubType::class, "transaction_document_template_details", "document_id", "transaction_sub_type_id")->withPivot(["transaction_document_template_id", "transaction_sub_type_id", "document_id"])->using(TransactionDocumentTemplateDetail::class);
    }
}
