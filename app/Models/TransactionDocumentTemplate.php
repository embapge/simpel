<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class TransactionDocumentTemplate extends Model
{
    use HasFactory, HasUuids, BlameableTrait;

    protected $table = "transaction_document_templates";
    protected $fillable = ["name", "description"];

    public function documents()
    {
        return $this->belongsToMany(Document::class, "transaction_document_template_details", "transaction_document_template_id", "document_id")->withPivot(["transaction_document_template_id", "transaction_sub_type_id", "document_id"])->using(TransactionDocumentTemplateDetail::class);
    }

    public function subTypes()
    {
        return $this->belongsToMany(TransactionSubType::class, "transaction_document_template_details", "transaction_document_template_id", "transaction_sub_type_id")->withPivot(["transaction_document_template_id", "transaction_sub_type_id", "document_id"])->using(TransactionDocumentTemplateDetail::class);
    }
}
