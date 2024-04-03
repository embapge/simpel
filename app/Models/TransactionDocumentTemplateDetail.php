<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class TransactionDocumentTemplateDetail extends Pivot
{
    use HasFactory, BlameableTrait;

    protected $table = "transaction_document_template_details";
    protected $fillable = ["transaction_document_template_id", "transaction_sub_type_id", "document_id", "is_required"];

    public function template()
    {
        return $this->belongsTo(TransactionDocumentTemplate::class, "transaction_document_template_id", "id");
    }

    public function document()
    {
        return $this->belongsTo(Document::class, "document_id", "id");
    }

    public function transactionSubType()
    {
        return $this->belongsTo(TransactionSubType::class, "transaction_sub_type_id", "id");
    }
}
